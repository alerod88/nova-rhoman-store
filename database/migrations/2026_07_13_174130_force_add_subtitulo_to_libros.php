<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Forzamos la creación de la columna directamente en la tabla libros
        if (Schema::hasTable('libros')) {
            Schema::table('libros', function (Blueprint $table) {
                if (!Schema::hasColumn('libros', 'subtitulo')) {
                    $table->string('subtitulo')->nullable()->after('titulo');
                }
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('libros')) {
            Schema::table('libros', function (Blueprint $table) {
                if (Schema::hasColumn('libros', 'subtitulo')) {
                    $table->dropColumn('subtitulo');
                }
            });
        }
    }
};