<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Material;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminMaterialController extends Controller
{
    // Si por algún motivo se entra a /admin/materiales, redirige al panel unificado
    public function index()
    {
        return redirect()->route('admin.categorias.index');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255|unique:materials,nombre'
        ]);

        Material::create([
            'nombre' => $request->nombre,
            'slug'   => Str::slug($request->nombre) // Aseguramos el slug aquí también
        ]);

        return redirect()->route('admin.categorias.index')->with('success', 'Material creado con éxito.');
    }

    public function destroy(Material $material)
    {
        // Validamos si algún libro lo está usando antes de dejar borrarlo
        if ($material->libros()->count() > 0) {
            return redirect()->route('admin.categorias.index')->with('error', 'No se puede eliminar porque tiene libros asociados.');
        }

        $material->delete();
        return redirect()->route('admin.categorias.index')->with('success', 'Material eliminado correctamente.');
    }
}   