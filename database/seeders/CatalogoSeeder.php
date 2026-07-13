<?php

namespace database\seeders;

use App\Models\Categoria;
use App\Models\Libro;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CatalogoSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Creamos las Categorías base (tanto por edad/tema como por material)
        $infantil = Categoria::create(['nombre' => 'Obras Infantiles', 'slug' => Str::slug('Obras Infantiles')]);
        $educativo = Categoria::create(['nombre' => 'Obras Educativas', 'slug' => Str::slug('Obras Educativas')]);
        $conSonido = Categoria::create(['nombre' => 'Con Sonido', 'slug' => Str::slug('Con Sonido')]);
        $gomaEva = Categoria::create(['nombre' => 'Goma Eva', 'slug' => Str::slug('Goma Eva')]);

        // 2. Libro 1: La Granja de Zenón (Interactivo, con sonido, cartoné)
        $libro1 = Libro::create([
            'titulo' => 'La Granja de Zenón - Una Mañana Muy Particular',
            'descripcion' => 'Ayuda a Zenón a entender qué ha pasado en la granja con todos sus amigos. Descubrí sus cantos, mugidos, relinchos y parloteos a través de una increíble botonera.',
            'video_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ', // Link de ejemplo
            'caracteristicas' => [
                'Ficha técnica' => '1 Tomo',
                'Páginas' => '20 Páginas acartonadas a todo color',
                'Formato' => '28 x 38 cm',
                'Detalles' => 'Láminas para troquelar y jugar',
                'Interactivo' => 'Módulo de sonido con 5 botones. Incluye pilas.'
            ]
        ]);
        // Vinculamos el libro a sus múltiples categorías
        $libro1->categorias()->attach([$infantil->id, $conSonido->id]);

        // Guardamos un par de fotos de prueba en la tabla secundaria para este libro
        $libro1->imagenes()->createMany([
            ['ruta_imagen' => 'la_granja_tapa.jpg'],
            ['ruta_imagen' => 'la_granja_interior1.jpg'],
            ['ruta_imagen' => 'la_granja_interior2.jpg'],
        ]);


        // 3. Libro 2: Cubolandia (Educativo, cubos de tela, estuche)
        $libro2 = Libro::create([
            'titulo' => 'Cubolandia - Cuerpo Humano',
            'descripcion' => 'Hermosa colección de cubos de goma espuma en los cuales podrás armar y aprender sobre los diferentes sistemas: Circulatorio, Respiratorio, Digestivo, Muscular, Óseo y Nervioso.',
            'video_url' => null, // Este no tiene video
            'caracteristicas' => [
                'Ficha técnica' => '8 Cubos de tela',
                'Impresión' => 'A todo color',
                'Packaging' => 'Estuche de acrílico'
            ]
        ]);
        // Vinculamos este libro a Infantil y Educativo
        $libro2->categorias()->attach([$infantil->id, $educativo->id]);

        $libro2->imagenes()->createMany([
            ['ruta_imagen' => 'cubolandia_tapa.jpg'],
            ['ruta_imagen' => 'cubolandia_detalle.jpg'],
        ]);
    }
}