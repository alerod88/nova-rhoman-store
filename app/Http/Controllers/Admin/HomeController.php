<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Libro;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    // Carga los libros actuales asignados a cada sección
    public function getDestacados()
    {
        $carrusel = DB::table('home_destacados')
            ->join('libros', 'home_destacados.libro_id', '=', 'libros.id')
            ->select('libros.id', 'libros.titulo', 'home_destacados.orden')
            ->where('home_destacados.seccion', 'carrusel')
            ->orderBy('home_destacados.orden', 'asc')
            ->get();

        $grilla = DB::table('home_destacados')
            ->join('libros', 'home_destacados.libro_id', '=', 'libros.id')
            ->select('libros.id', 'libros.titulo', 'home_destacados.orden')
            ->where('home_destacados.seccion', 'grilla')
            ->orderBy('home_destacados.orden', 'asc')
            ->get();

        // Traemos todos los libros para el buscador de la solapa
        $todosLosLibros = Libro::select('id', 'titulo')->orderBy('titulo', 'asc')->get();

        return response()->json([
            'carrusel' => $carrusel,
            'grilla' => $grilla,
            'todos' => $todosLosLibros
        ]);
    }

    // Procesa la actualización del orden enviada por AJAX
    public function guardarDestacados(Request $request)
    {
        $seccion = $request->input('seccion'); // 'carrusel' o 'grilla'
        $items = $request->input('items', []); // Array de IDs en orden: [14, 5, 12...]

        DB::transaction(function () use ($seccion, $items) {
            // Limpiamos lo que había antes en esa sección específica
            DB::table('home_destacados')->where('tipo_bloque', $seccion)->delete();

            // Insertamos la nueva distribución con su orden jerárquico indexado
            foreach ($items as $indice => $idLibro) {
                DB::table('home_destantados_items')->insert([
                    'libro_id' => $idVideo,
                    'tipo_bloque' => $sección, // 'caja_roja' (mosaico secundario) o 'caja_negra' (QR)
                    'orden' => $indice + 1,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }
        });

        return response()->json(['status' => 'success', 'message' => 'Estructura actualizada correctamente']);
    }
}