<?php

namespace App\Http\Controllers;

use App\Models\Libro;
use App\Models\Banner;
use App\Models\Categoria;
use App\Models\Material; 
use App\Models\Catalogo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; // IMPORTACIÓN CLAVE PARA PODER USAR EL PROCESO JOIN

class LibroController extends Controller
{
    // Vista principal (Home con carrusel y destacados acoplados al Modo Editor)
    public function index()
    {
        $banners = \App\Models\Banner::where('activo', true)->orderBy('orden', 'asc')->get();
        $categorias = \App\Models\Categoria::all();
        
        // CORRECCIÓN CLAVE: Cruzamos la tabla de destacados con libros para respetar el orden visual del mouse
        $librosDestacados = Libro::with('imagenes')
            ->join('home_destacados', 'libros.id', '=', 'home_destacados.libro_id')
            ->where('home_destacados.seccion', 'grilla')
            ->orderBy('home_destacados.orden', 'asc') // Mantiene tu jerarquía (1, 2, 3...)
            ->select('libros.*') // Evita que se pisen columnas homónimas al unificar estructuras
            ->get();

        return view('home', compact('banners', 'categorias', 'librosDestacados'));
    }

    // EL NUEVO CATÁLOGO CON CONTADORES DINÁMICOS CRUZADOS
    public function catalogo(Request $request)
    {
        $buscar = $request->input('buscar');
        $temaSeleccionado = $request->input('tema');      
        $materialSeleccionado = $request->input('material'); 

        $query = Libro::with(['imagenes', 'categorias', 'materiales']);

        // Aplicar filtros de búsqueda, tema y material a la query principal
        if (!empty($buscar)) {
            $query->where(function($q) use ($buscar) {
                $q->where('titulo', 'LIKE', "%{$buscar}%")
                ->orWhere('descripcion', 'LIKE', "%{$buscar}%")
                ->orWhere('caracteristicas', 'LIKE', "%{$buscar}%")
                ->orWhereHas('categorias', function($subQuery) use ($buscar) {
                    $subQuery->where('nombre', 'LIKE', "%{$buscar}%");
                })
                ->orWhereHas('materiales', function($subQuery) use ($buscar) {
                    $subQuery->where('nombre', 'LIKE', "%{$buscar}%");
                });
            });
        }

        if (!empty($temaSeleccionado)) {
            $query->whereHas('categorias', function($q) use ($temaSeleccionado) {
                $q->where('slug', $temaSeleccionado);
            });
        }

        if (!empty($materialSeleccionado)) {
            $query->whereHas('materiales', function($q) use ($materialSeleccionado) {
                $q->where('slug', $materialSeleccionado);
            });
        }

        $libros = $query->latest()->paginate(12)->appends($request->all());

        // 🌟 CONTADOR DE TEMAS: Se adapta al buscador y al material seleccionado
        $categorias = Categoria::withCount(['libros' => function($q) use ($buscar, $materialSeleccionado) {
            if (!empty($buscar)) {
                $q->where(function($bQuery) use ($buscar) {
                    $bQuery->where('titulo', 'LIKE', "%{$buscar}%")
                           ->orWhere('descripcion', 'LIKE', "%{$buscar}%")
                           ->orWhere('caracteristicas', 'LIKE', "%{$buscar}%");
                });
            }
            if (!empty($materialSeleccionado)) {
                $q->whereHas('materiales', function($mQuery) use ($materialSeleccionado) {
                    $mQuery->where('slug', $materialSeleccionado);
                });
            }
        }])->get();

        // 🌟 CONTADOR DE MATERIALES: Se adapta al buscador y al tema seleccionado
        $materiales = Material::withCount(['libros' => function($q) use ($buscar, $temaSeleccionado) {
            if (!empty($buscar)) {
                $q->where(function($bQuery) use ($buscar) {
                    $bQuery->where('titulo', 'LIKE', "%{$buscar}%")
                           ->orWhere('descripcion', 'LIKE', "%{$buscar}%")
                           ->orWhere('caracteristicas', 'LIKE', "%{$buscar}%");
                });
            }
            if (!empty($temaSeleccionado)) {
                $q->whereHas('categorias', function($cQuery) use ($temaSeleccionado) {
                    $cQuery->where('slug', $temaSeleccionado);
                });
            }
        }])->get();

        return view('catalogo', compact('libros', 'categorias', 'materiales'));
    }

    // Vista de detalle de un producto específico
    public function detalle($id)
    {
        $categorias = Categoria::all();
        $libro = Libro::with(['imagenes', 'categorias', 'materiales'])->findOrFail($id);

        return view('detalle', compact('libro', 'categorias'));
    }

    public function enviarContacto(Request $request)
    {
        // 🌟 TRUCO HONEYPOT: Si este campo oculto NO está vacío, es un bot trucho.
        if (!empty($request->input('seguridad_validacion_check'))) {
            // Retornamos éxito simulado para engañar al bot y que no insista
            return redirect()->back()->with('exito', '¡Gracias por comunicarte con Nova Rhoman! Tu mensaje fue recibido con éxito.');
        }

        // Si pasó el filtro, procesamos el mensaje real del humano
        $request->validate([
            'nombre'  => 'required|string|max:255',
            'email'   => 'required|email',
            'asunto'  => 'required|string|max:255',
            'mensaje' => 'required|string',
            'confirmacion_humana'  => 'accepted',
        ]);

        $data = $request->only(['nombre', 'email', 'asunto', 'mensaje']);

        // Envío del mail corporativo
        \Illuminate\Support\Facades\Mail::to('ventas@novarhoman.com.ar')->send(new \App\Mail\ContactoMailable($data));

        return redirect()->back()->with('exito', '¡Gracias por comunicarte con Nova Rhoman! Tu mensaje fue recibido con éxito.');
    }

    public function descargarCatalogoView()
    {
        // Recuperamos todos los catálogos dados de alta por el admin
        $catalogos = Catalogo::orderBy('orden', 'asc')->get();
        return view('descargar-catalogo', compact('catalogos'));
    }
}