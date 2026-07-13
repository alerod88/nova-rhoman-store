<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
{
    Schema::create('libros', function (Blueprint $table) {
        $table->id();
        $table->string('titulo');
        $table->text('descripcion');
        
        // El JSON para la ficha técnica variable (sonido, cantidad de páginas, etc.)
        $table->json('caracteristicas')->nullable(); 
        
        $table->string('video_url')->nullable(); 
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('libros');
    }
};
