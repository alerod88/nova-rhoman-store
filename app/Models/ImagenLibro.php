<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ImagenLibro extends Model
{
    // Definimos explícitamente el nombre de la tabla ya que Laravel por defecto buscaría "imagen_libros"
    protected $table = 'imagenes_libros';

    protected $fillable = ['libro_id', 'ruta_imagen'];

    // Relación inversa: Una imagen pertenece a un libro
    public function libro(): BelongsTo
    {
        return $this->belongsTo(Libro::class);
    }
}