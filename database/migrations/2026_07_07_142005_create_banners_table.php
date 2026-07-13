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
        Schema::create('banners', function (Blueprint $table) { // <-- AQUÍ CORREGIMOS A Blueprint
            $table->id();
            $table->string('titulo')->nullable();
            $table->string('subtitulo')->nullable();
            $table->string('ruta_imagen');
            
            // Relación con libros
            $table->foreignId('libro_id')->nullable()->constrained('libros')->onDelete('set null');
            
            $table->integer('orden')->default(0);
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('banners');
    }
};
