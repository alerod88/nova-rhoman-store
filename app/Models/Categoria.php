<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Categoria extends Model
{
    protected $fillable = ['nombre', 'slug'];

    // Relación inversa: Una categoría pertenece a muchos libros
    public function libros(): BelongsToMany
    {
        return $this->belongsToMany(Libro::class);
    }
}