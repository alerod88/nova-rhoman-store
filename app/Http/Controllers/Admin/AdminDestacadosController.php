<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Libro;

class AdminDestacadosController extends Controller
{
    // Solo sirve para tirar el catálogo en JSON al Modo Editor
    public function datos()
    {
        // Cargamos la relación 'imagenes' que definiste en tu modelo
        $todosLosLibros = Libro::with('imagenes:id,libro_id,ruta_imagen')
            ->select('id', 'titulo')
            ->orderBy('titulo', 'asc')
            ->get();

        return response()->json([
            'todos' => $todosLosLibros
        ]);
    }

    // Guarda los cambios masivos desde el Home real
    public function store(Request $request)
{
    $seccion = $request->input('seccion', 'grilla'); 
    $items = $request->input('items', []); 

    try {
        \Illuminate\Support\Facades\DB::transaction(function () use ($seccion, $items) {
            // Limpia la sección antigua
            \Illuminate\Support\Facades\DB::table('home_destacados')->where('seccion', $seccion)->delete();

            // Inserta con la nueva jerarquía establecida por el administrador con el mouse
            foreach ($items as $indice => $idLibro) {
                if (!empty($idLibro)) {
                    \Illuminate\Support\Facades\DB::table('home_destacados')->insert([
                        'libro_id' => $idLibro,
                        'seccion' => $seccion,
                        'orden' => $indice + 1,
                        'created_at' => now(),
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