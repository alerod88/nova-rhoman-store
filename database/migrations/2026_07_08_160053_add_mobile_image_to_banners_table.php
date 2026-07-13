<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('banners', function (Blueprint $table) {
            // Creamos la columna para la ruta del celular justo al lado de la imagen común
            $table->string('ruta_imagen_mobile')->nullable()->after('ruta_imagen');
        });
    }

    public function down()
    {
        Schema::table('banners', function (Blueprint $table) {
            $table->dropColumn('ruta_imagen_mobile');
        });
    }
};
