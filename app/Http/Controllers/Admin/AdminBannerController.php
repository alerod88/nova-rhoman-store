<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\Libro;
use App\Models\Categoria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminBannerController extends Controller
{
    public function index()
    {
        // Traemos los banners ordenados por 'orden' para ver las posiciones reales
        $banners = Banner::with('libro')->orderBy('orden', 'asc')->get();
        $categorias = Categoria::all();
        return view('admin.banners.index', compact('banners', 'categorias'));
    }

    public function create()
    {
        $libros = Libro::orderBy('titulo', 'asc')->get();
        $categorias = Categoria::all();
        return view('admin.banners.create', compact('libros', 'categorias'));
    }

    public function store(Request $request)
    {
        // 1. AJUSTE: Si seleccionó un banner estático, convertimos la opción a null antes de validar
        if ($request->input('libro_id') === 'estatico') {
            $request->merge(['libro_id' => null]);
        }

        // 2. UNA SOLA VALIDACIÓN LIMPIA PARA TODO
        $request->validate([
            'titulo' => 'nullable|string|max:255',
            'subtitulo'          => 'nullable|string|max:255',
            'ruta_imagen'        => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
            'ruta_imagen_mobile' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048', // Validación mobile
            'libro_id'           => 'nullable|exists:libros,id',
            'enlace_personalizado'=> 'nullable|string|max:255',
            'orden'              => 'nullable|integer'
        ]);

        // 3. RECOLECCIÓN DE DATOS BASE

        $datos = $request->only(['titulo', 'subtitulo', 'libro_id', 'enlace_personalizado']);

        // AUTOMATIZACIÓN DEL ORDEN
        $datos['orden'] = $request->filled('orden') 
            ? $request->input('orden') 
            : (Banner::max('orden') !== null ? Banner::max('orden') + 1 : 0);

        // 4. PROCESAR IMAGEN ESCRITORIO
        if ($request->hasFile('ruta_imagen')) {
            $nombreImagen = time() . '_desktop_' . $request->file('ruta_imagen')->getClientOriginalName();
            $request->file('ruta_imagen')->move(public_path('storage/banners'), $nombreImagen);
            $datos['ruta_imagen'] = $nombreImagen;
        }

        // 5. PROCESAR IMAGEN MOBILE
        if ($request->hasFile('ruta_imagen_mobile')) {
            $nombreImagenMobile = time() . '_mobile_' . $request->file('ruta_imagen_mobile')->getClientOriginalName();
            $request->file('ruta_imagen_mobile')->move(public_path('storage/banners'), $nombreImagenMobile);
            $datos['ruta_imagen_mobile'] = $nombreImagenMobile;
        }

        // 6. CREACIÓN FINAL
        Banner::create($datos);

        return redirect()->route('admin.banners.index')->with('success', 'Banner creado con éxito.');
    }

    public function edit(Banner $banner)
    {
        $libros = Libro::orderBy('titulo', 'asc')->get();
        $categorias = Categoria::all();
        return view('admin.banners.edit', compact('banner', 'libros', 'categorias'));
    }

    public function update(Request $request, Banner $banner)
    {
        // AJUSTE: Si seleccionó banner estático al editar
        if ($request->input('libro_id') === 'estatico') {
            $request->merge(['libro_id' => null]);
        }

        // VALIDACIÓN DE EDICIÓN (Las imágenes son nullable porque pueden elegir no cambiarlas)
        $request->validate([
            'titulo'             => 'nullable|string|max:255',
            'subtitulo'          => 'nullable|string|max:255',
            'ruta_imagen'        => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'ruta_imagen_mobile' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'libro_id'           => 'nullable|exists:libros,id',
            'enlace_personalizado'=> 'nullable|string|max:255',
            'orden'              => 'nullable|integer'
        ]);

        $datos = $request->only(['titulo', 'subtitulo', 'libro_id', 'enlace_personalizado', 'orden']);

        // CONTROL DE IMAGEN ESCRITORIO NUEVA
        if ($request->hasFile('ruta_imagen')) {
            // Eliminamos la anterior física del disco para no dejar basura
            if (!empty($banner->ruta_imagen) && file_exists(public_path('storage/banners/' . $banner->ruta_imagen))) {
                @unlink(public_path('storage/banners/' . $banner->ruta_imagen));
            }

            $nombreImagen = time() . '_desktop_' . $request->file('ruta_imagen')->getClientOriginalName();
            $request->file('ruta_imagen')->move(public_path('storage/banners'), $nombreImagen);
            $datos['ruta_imagen'] = $nombreImagen;
        }

        // CONTROL DE IMAGEN MOBILE NUEVA
        if ($request->hasFile('ruta_imagen_mobile')) {
            // Eliminamos la anterior física del celular
            if (!empty($banner->ruta_imagen_mobile) && file_exists(public_path('storage/banners/' . $banner->ruta_imagen_mobile))) {
                @unlink(public_path('storage/banners/' . $banner->ruta_imagen_mobile));
            }

            $nombreImagenMobile = time() . '_mobile_' . $request->file('ruta_imagen_mobile')->getClientOriginalName();
            $request->file('ruta_imagen_mobile')->move(public_path('storage/banners'), $nombreImagenMobile);
            $datos['ruta_imagen_mobile'] = $nombreImagenMobile;
        }

        $banner->update($datos);

        return redirect()->route('admin.banners.index')->with('success', 'Banner actualizado correctamente.');
    }

    public function destroy(Banner $banner)
    {
        // Borramos la de escritorio si existe
        if (!empty($banner->ruta_imagen) && file_exists(public_path('storage/banners/' . $banner->ruta_imagen))) {
            @unlink(public_path('storage/banners/' . $banner->ruta_imagen));
        }

        // Borramos la de celular si existe
        if (!empty($banner->ruta_imagen_mobile) && file_exists(public_path('storage/banners/' . $banner->ruta_imagen_mobile))) {
            @unlink(public_path('storage/banners/' . $banner->ruta_imagen_mobile));
        }

        $banner->delete();

        return redirect()->route('admin.banners.index')->with('success', 'Banner eliminado completamente.');
    }

    public function guardarOrden(Request $request)
    {
        $items = $request->input('items', []); // Recibe el array de IDs en orden [3, 1, 2...]

        try {
            DB::transaction(function () use ($items) {
                foreach ($items as $indice => $idBanner) {
                    if (!empty($idBanner)) {
                        // Actualizamos el campo 'orden' de cada banner secuencialmente
                        DB::table('banners') // Reemplazar por el nombre exacto de tu tabla de banners si difiere
                            ->where('id', $idBanner)
                            ->update([
                                'orden' => $indice + 1,
                                'updated_at' => now()
                            ]);
                    }
                }
            });

            return response()->json(['status' => 'success']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }
}
