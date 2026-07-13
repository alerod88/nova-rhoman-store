<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    use HasFactory;

    // Habilitamos los campos de forma segura para permitir el guardado masivo
    protected $fillable = [
        'titulo', 
        'subtitulo', 
        'ruta_imagen', 
        'libro_id', 
        'enlace_personalizado',
        'orden', 
        'activo'
    ];

    // Relación: Un banner pertenece a un Libro
    public function libro()
    {
        return $this->belongsTo(Libro::class, 'libro_id');
    }
}