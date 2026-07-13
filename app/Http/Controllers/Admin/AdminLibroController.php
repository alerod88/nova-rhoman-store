<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Libro;
use App\Models\Categoria;
use App\Models\Material;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class AdminLibroController extends Controller
{
    // Muestra el panel principal con buscador, filtros y paginación
    public function index(Request $request)
    {
        $categorias = Categoria::all();
        $query = Libro::with(['categorias', 'materiales', 'imagenes'])->latest();

        // 1. FILTRO: Buscador por título o descripción
        if ($request->filled('buscar')) {
            $buscar = $request->input('buscar');
            $query->where(function($q) use ($buscar) {
                $q->where('titulo', 'LIKE', "%{$buscar}%")
                  ->orWhere('descripcion', 'LIKE', "%{$buscar}%");
            });
        }

        // 2. FILTRO: Por Categoría / Obra
        if ($request->filled('categoria_id')) {
            $categoriaId = $request->input('categoria_id');
            $query->whereHas('categorias', function($q) use ($categoriaId) {
                $q->where('categorias.id', $categoriaId);
            });
        }

        $libros = $query->paginate(15)->appends($request->all());

        return view('admin.libros.index', compact('libros', 'categorias'));
    }

    // Muestra el formulario para crear un nuevo libro
    public function create()
    {
        $categorias = Categoria::all(); 
        $obras = Categoria::orderBy('nombre', 'asc')->get(); 
        $materiales = Material::orderBy('nombre', 'asc')->get(); 

        return view('admin.libros.crear', compact('categorias', 'obras', 'materiales'));
    }

    // Procesa el guardado del nuevo libro
    public function store(Request $request)
    {
        $request->validate([
            'titulo' => 'required|string|max:255',
            'subtitulo' => 'nullable|string|max:255', // 🔒 SE AGREGÓ LA VALIDACIÓN DEL SUBTÍTULO
            'descripcion' => 'nullable|string',
            'categorias' => 'required|array',
            'materiales' => 'nullable|array', 
            'imagenes' => 'nullable|array', 
            'imagenes.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:2048', 
            'video_url' => 'nullable|url',
            'video_archivo' => 'nullable|mimes:mp4,mov,avi|max:20480',
        ]);

        $caracteristicasJSON = [];

        if ($request->filled('json_valores_etiqueta')) {
            $caracteristicasJSON['Etiqueta'] = $request->input('json_valores_etiqueta');
        }

        if ($request->has('json_claves') && $request->has('json_valores')) {
            foreach ($request->json_claves as $index => $clave) {
                $valor = $request->json_valores[$index] ?? '';
                if (!empty($clave) && !empty($valor)) {
                    $claveLimpia = rtrim(trim($clave), ':');
                    if ($claveLimpia !== 'Etiqueta') {
                        $caracteristicasJSON[$claveLimpia] = trim($valor);
                    }
                }
            }
        }

        $libro = new Libro();
        $libro->titulo = $request->titulo;
        $libro->subtitulo = $request->subtitulo; // 🔒 ASIGNACIÓN DE LA VARIABLE EN BD
        $libro->descripcion = $request->descripcion;
        $libro->caracteristicas = $caracteristicasJSON;

        if ($request->hasFile('video_archivo')) {
            $nombreVideo = time() . '_' . uniqid() . '.' . $request->file('video_archivo')->getClientOriginalExtension();
            $request->file('video_archivo')->storeAs('videos', $nombreVideo, 'public');
            $libro->video_archivo = $nombreVideo;
        } elseif ($request->filled('video_url')) {
            $libro->video_url = $request->video_url;
        }

        $libro->save();

        $libro->categorias()->sync($request->categorias);
        $libro->materiales()->sync($request->input('materiales', [])); 

        if ($request->hasFile('imagenes')) {
            foreach ($request->file('imagenes') as $file) {
                $nombreImagen = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $file->storeAs('libros', $nombreImagen, 'public');
                $libro->imagenes()->create([
                    'ruta_imagen' => $nombreImagen
                ]);
            }
        }

        return redirect('/admin/libros')->with('exito', '¡El libro se cargó en el catálogo de forma impecable!');
    }

    // Muestra el formulario para editar un libro existente
    public function edit($id)
    {
        $libro = Libro::with(['categorias', 'materiales', 'imagenes'])->findOrFail($id);
        $categorias = Categoria::all(); 
        $obras = Categoria::where('slug', '!=', 'material')->orderBy('nombre', 'asc')->get(); 
        $materiales = Material::orderBy('nombre', 'asc')->get(); 
        
        return view('admin.libros.editar', compact('libro', 'categorias', 'obras', 'materiales'));
    }

    // Procesa la actualización de los datos del libro en la BD
    public function update(Request $request, $id)
    {
        $request->validate([
            'titulo' => 'required|string|max:255',
            'subtitulo' => 'nullable|string|max:255', // 🔒 SE AGREGÓ LA VALIDACIÓN DEL SUBTÍTULO
            'descripcion' => 'nullable|string',
            'categorias' => 'required|array',
            'materiales' => 'nullable|array', 
            'imagenes' => 'nullable|array', 
            'imagenes.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:2048', 
            'video_url' => 'nullable|url',
            'video_archivo' => 'nullable|mimes:mp4,mov,avi|max:20480',
        ]);

        $libro = Libro::findOrFail($id);
        $caracteristicasJSON = [];

        if ($request->filled('json_valores_etiqueta')) {
            $caracteristicasJSON['Etiqueta'] = $request->input('json_valores_etiqueta');
        }

        if ($request->has('json_claves') && $request->has('json_valores')) {
            foreach ($request->json_claves as $index => $clave) {
                $valor = $request->json_valores[$index] ?? '';
                if (!empty($clave) && !empty($valor)) {
                    $claveLimpia = rtrim(trim($clave), ':');
                    if ($claveLimpia !== 'Etiqueta') {
                        $caracteristicasJSON[$claveLimpia] = trim($valor);
                    }
                }
            }
        }

        $libro->titulo = $request->titulo;
        $libro->subtitulo = $request->subtitulo; // 🔒 ACTUALIZACIÓN DE LA VARIABLE EN BD
        $libro->descripcion = $request->descripcion;
        $libro->caracteristicas = $caracteristicasJSON;

        if ($request->boolean('quitar_video')) {
            if ($libro->video_archivo) {
                Storage::disk('public')->delete('videos/' . $libro->video_archivo);
            }
            $libro->video_archivo = null;
            $libro->video_url = null;
        } elseif ($request->hasFile('video_archivo')) {
            if ($libro->video_archivo) {
                Storage::disk('public')->delete('videos/' . $libro->video_archivo);
            }
            $nombreVideo = time() . '_' . uniqid() . '.' . $request->file('video_archivo')->getClientOriginalExtension();
            $request->file('video_archivo')->storeAs('videos', $nombreVideo, 'public');
            $libro->video_archivo = $nombreVideo;
            $libro->video_url = null;
        } elseif ($request->filled('video_url')) {
            if ($libro->video_archivo) {
                Storage::disk('public')->delete('videos/' . $libro->video_archivo);
            }
            $libro->video_archivo = null;
            $libro->video_url = $request->video_url;
        }

        $libro->save();

        $libro->categorias()->sync($request->categorias);
        $libro->materiales()->sync($request->input('materiales', [])); 

        if ($request->has('forces_borradas') || $request->has('fotos_borradas')) {
            $fotosBorradas = $request->input('fotos_borradas', []);
            foreach ($fotosBorradas as $fotoId) {
                if (!empty($fotoId)) {
                    $foto = $libro->imagenes()->find($fotoId);
                    if ($foto) {
                        Storage::disk('public')->delete('libros/' . $foto->ruta_imagen);
                        $foto->delete(); 
                    }
                }
            }
        }

        if ($request->hasFile('imagenes')) {
            foreach ($request->file('imagenes') as $file) {
                $nombreImagen = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $file->storeAs('libros', $nombreImagen, 'public');
                $libro->imagenes()->create([
                    'ruta_imagen' => $nombreImagen
                ]);
            }
        }

        return redirect('/admin/libros')->with('exito', '¡El libro se actualizó en el catálogo de forma impecable!');
    }
    
    // Acción para eliminar un libro de forma definitiva (Limpieza total de BD y Disco)
    public function destroy($id)
    {
        $libro = Libro::with('imagenes')->findOrFail($id);

        foreach ($libro->imagenes as $foto) {
            if (!empty($foto->ruta_imagen)) {
                Storage::disk('public')->delete('libros/' . $foto->ruta_imagen);
            }
        }

        if (!empty($libro->video_archivo)) {
            Storage::disk('public')->delete('videos/' . $libro->video_archivo);
        }

        $libro->categorias()->detach();
        $libro->materiales()->detach(); 

        $libro->imagenes()->delete(); 
        $libro->delete(); 

        return redirect('/admin/libros')->with('exito', 'El libro, sus videos y todas sus imágenes asociadas fueron eliminados del sistema correctamente.');
    }

    public function storeCategoria(Request $request)
    {
        $nombre = $request->input('nombre');
        $tipo = $request->input('tipo'); 

        if ($tipo === 'material') {
            $material = Material::create([
                'nombre' => $nombre,
                'slug'   => Str::slug($nombre)
            ]);
            return response()->json(['exito' => true, 'id' => $material->id, 'tipo' => 'material']);
        } else {
            $categoria = Categoria::create([
                'nombre' => $nombre,
                'slug'   => Str::slug($nombre)
            ]);
            return response()->json(['exito' => true, 'id' => $categoria->id, 'tipo' => 'obra']);
        }
    }
}