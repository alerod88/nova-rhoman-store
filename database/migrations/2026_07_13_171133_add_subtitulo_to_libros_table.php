<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('libros', function (Blueprint $table) {
            // Agrega la columna de forma opcional justo después del título
            $table->string('subtitulo')->nullable()->after('titulo');
        });
    }

    public function down(): void
    {
        Schema::table('libros', function (Blueprint $table) {
            $table->dropColumn('subtitulo');
        });
    }
};