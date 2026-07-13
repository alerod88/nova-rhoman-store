<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Material extends Model
{
    protected $fillable = [
        'nombre',
        'slug'
    ];

    // NUEVA RELACIÓN INVERSA: Un material pertenece a muchos libros
    public function libros(): BelongsToMany
    {
        return $this->belongsToMany(Libro::class);
    }
}