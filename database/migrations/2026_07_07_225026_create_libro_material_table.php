<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('libro_material', function (Blueprint $table) {
            $table->id();
            
            // Relación con la tabla libros (si se borra un libro, se limpia este registro)
            $table->foreignId('libro_id')->constrained('libros')->onDelete('cascade');
            
            // Relación con la tabla materials (si se borra un material, se limpia este registro)
            $table->foreignId('material_id')->constrained('materials')->onDelete('cascade');
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('libro_material');
    }
};