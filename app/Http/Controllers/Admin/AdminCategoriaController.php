<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Categoria;
use App\Models\Material; // IMPORTAMOS EL MODELO NUEVO
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminCategoriaController extends Controller
{
    public function index()
    {
        // CAMBIO CLAVE: Usamos withCount para que Laravel calcule automáticamente los libros vinculados
        $categorias = Categoria::withCount('libros')->orderBy('nombre', 'asc')->get();
        $materiales = Material::withCount('libros')->orderBy('nombre', 'asc')->get();

        // Enviamos ambos a la vista unificada
        return view('admin.categorias.index', compact('categorias', 'materiales'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255|unique:categorias,nombre'
        ]);

        // Creamos la categoría inyectando el slug de manera automática
        Categoria::create([
            'nombre' => $request->nombre,
            'slug'   => Str::slug($request->nombre) 
        ]);

        return redirect()->route('admin.categorias.index')->with('success', 'Tema creado con éxito.');
    }

    public function destroy(Categoria $categoria)
    {
        if ($categoria->libros()->count() > 0) {
            return redirect()->route('admin.categorias.index')->with('error', 'No se puede eliminar porque tiene libros asociados.');
        }

        $categoria->delete();
        return redirect()->route('admin.categorias.index')->with('success', 'Tema eliminado.');
    }
}