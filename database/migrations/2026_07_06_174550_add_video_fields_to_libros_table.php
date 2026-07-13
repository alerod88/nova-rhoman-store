<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('libros', function (Blueprint $table) {
            // Comentamos la que ya existe en MySQL para saltarla
            // $table->string('video_url')->nullable()->after('caracteristicas');
            
            // Dejamos que cree solo la que falta
            $table->string('video_archivo')->nullable()->after('caracteristicas');
        });
    }

    public function down(): void
    {
        Schema::table('libros', function (Blueprint $table) {
            $table->dropColumn(['video_archivo']);
        });
    }
};