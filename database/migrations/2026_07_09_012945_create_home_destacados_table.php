<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('home_destacados', function (Blueprint $table) {
            $table->id();
            $table->foreignId('libro_id')->constrained('libros')->onDelete('cascade');
            $table->string('seccion');
            // CORREGIDO: Cambiado el punto (.) por la flecha (->)
            $table->integer('orden')->default(0);
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
