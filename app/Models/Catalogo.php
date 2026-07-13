<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Catalogo extends Model
{
    protected $fillable = ['titulo', 'subtitulo', 'ruta_portada', 'ruta_pdf', 'orden'];
}