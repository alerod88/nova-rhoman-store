<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Libro extends Model
{
    // Permitimos la carga masiva de estos campos desde los formularios
    protected $fillable = [
        'titulo', 
        'subtitulo',
        'descripcion', 
        'caracteristicas', 
        'video_url', 
        'video_archivo'
    ];

    // Convertimos automáticamente el JSON de la BD a un array asociativo de PHP
    protected $casts = [
        'caracteristicas' => 'array',
        'video_archivo' => 'string',
    ];

    // Relación: Un libro tiene muchas imágenes asociadas
    public function imagenes(): HasMany
    {
        return $this->hasMany(ImagenLibro::class);
    }

    // Relación Muchos a Muchos: Un libro pertenece a muchas categorías (Temas)
    public function categorias(): BelongsToMany
    {
        return $this->belongsToMany(Categoria::class);
    }

    // NUEVA RELACIÓN: Un libro pertenece a muchos materiales (Formatos)
    public function materiales(): BelongsToMany
    {
        // Esto asume que tu tabla intermedia se llama 'libro_material' por convención
        return $this->belongsToMany(Material::class);
    }
}