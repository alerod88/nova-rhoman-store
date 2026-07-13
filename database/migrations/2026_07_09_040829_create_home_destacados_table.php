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
        Schema::create('home_destacados', function (Blueprint $table) {
            $table->id();
            // Llave foránea que conecta directo con tu tabla 'libros'
            $table->foreignId('libro_id')->constrained('libros')->onDelete('cascade');
            $table->string('seccion'); // 'carrusel' o 'grilla'
            $table->integer('orden');   // Posición jerárquica (1, 2, 3...)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('home_destacados');
    }
};
