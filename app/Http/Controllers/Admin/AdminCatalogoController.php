<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Catalogo;
use Illuminate\Http\Request;

class AdminCatalogoController extends Controller
{
    public function index()
    {
        $catalogos = Catalogo::orderBy('orden', 'asc')->get();
        return view('admin.catalogos.index', compact('catalogos'));
    }

    public function create()
    {
        return view('admin.catalogos.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'titulo'       => 'required|string|max:255',
            'subtitulo'    => 'nullable|string|max:255',
            'ruta_portada' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
            'ruta_pdf'     => 'required|mimes:pdf|max:45000', 
        ]);

        $datos = $request->only(['titulo', 'subtitulo']);
        $datos['orden'] = Catalogo::max('orden') !== null ? Catalogo::max('orden') + 1 : 1;

        // Subir Portada
        if ($request->hasFile('ruta_portada')) {
            $nombrePortada = time() . '_portada_' . $request->file('ruta_portada')->getClientOriginalName();
            $request->file('ruta_portada')->move(public_path('storage/catalogos'), $nombrePortada);
            $datos['ruta_portada'] = $nombrePortada;
        }

        // Subir PDF
        if ($request->hasFile('ruta_pdf')) {
            $nombrePdf = time() . '_' . $request->file('ruta_pdf')->getClientOriginalName();
            $request->file('ruta_pdf')->move(public_path('storage/catalogos'), $nombrePdf);
            $datos['ruta_pdf'] = $nombrePdf;
        }

        Catalogo::create($datos);

        return redirect()->route('admin.catalogos.index')->with('success', 'Catálogo físico subido con éxito.');
    }

    // 🌟 CORREGIDO: Recibe el ID explícito para evitar problemas de Route Model Binding
    public function destroy($id)
    {
        $catalogo = Catalogo::findOrFail($id);

        // Borrar archivos físicos
        if (!empty($catalogo->ruta_portada) && file_exists(public_path('storage/catalogos/' . $catalogo->ruta_portada))) {
            @unlink(public_path('storage/catalogos/' . $catalogo->ruta_portada));
        }
        if (!empty($catalogo->ruta_pdf) && file_exists(public_path('storage/catalogos/' . $catalogo->ruta_pdf))) {
            @unlink(public_path('storage/catalogos/' . $catalogo->ruta_pdf));
        }

        $catalogo->delete();

        return redirect()->route('admin.catalogos.index')->with('success', 'Catálogo eliminado por completo.');
    }
}