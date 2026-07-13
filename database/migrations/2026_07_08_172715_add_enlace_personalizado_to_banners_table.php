<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('banners', function (Blueprint $table) {
            // Almacena URLs o rutas personalizadas de búsqueda
            $table->string('enlace_personalizado')->nullable()->after('libro_id');
        });
    }

    public function down()
    {
        Schema::table('banners', function (Blueprint $table) {
            $table->dropColumn('enlace_personalizado');
        });
    }
};
