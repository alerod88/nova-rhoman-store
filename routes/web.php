<?php

use App\Http\Controllers\LibroController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AuthExpressController;
use App\Http\Controllers\Admin\AdminLibroController;
use App\Http\Controllers\Admin\AdminBannerController;
use App\Http\Controllers\Admin\AdminCategoriaController;
use App\Http\Controllers\Admin\AdminMaterialController;
use App\Http\Controllers\Admin\AdminDestacadosController;
use App\Http\Controllers\Admin\AdminCatalogoController;


// Ruta del Home (Vista principal con carrusel)
Route::get('/', [LibroController::class, 'index'])->name('home');

// Ruta del Catálogo completo y resultados de búsqueda
Route::get('/catalogo', [LibroController::class, 'catalogo'])->name('catalogo');

// Ruta para ver el detalle de un libro específico
Route::get('/libro/{id}', [LibroController::class, 'detalle'])->name('libro.detalle');

// Ruta de la sección Nosotros
Route::get('/nosotros', function () {
    return view('nosotros', ['categorias' => App\Models\Categoria::all()]);
})->name('nosotros');

// Ruta de Contacto
Route::get('/contacto', function (Illuminate\Http\Request $request) {
    $categorias = App\Models\Categoria::all();
    $libroReferencia = null;
    if ($request->has('libro_id')) {
        $libroReferencia = App\Models\Libro::find($request->libro_id);
    }
    return view('contacto', compact('categorias', 'libroReferencia'));
})->name('contacto');

Route::post('/contacto/enviar', [LibroController::class, 'enviarContacto'])->name('contacto.enviar');

// RUTA PÚBLICA (Modificá o reemplazá la que tenías de pruebas)
Route::get('/descargar-catalogo', [LibroController::class, 'descargarCatalogoView'])->name('descargar-catalogo');

// RUTAS ABIERTAS DE AUTENTICACIÓN
Route::get('/admin/login', [AuthExpressController::class, 'mostrarLogin'])->name('login');
Route::post('/admin/login', [AuthExpressController::class, 'conectar'])->name('login.conectar');
Route::post('/admin/logout', [AuthExpressController::class, 'salir'])->name('logout');

Route::get('/descargar-catalogo', [App\Http\Controllers\LibroController::class, 'descargarCatalogoView'])->name('descargar-catalogo');  

// GRUPO DE ADMINISTRACIÓN PROTEGIDO
Route::prefix('admin')->name('admin.')->middleware('auth')->group(function () {
    
    // Gestión de Libros
    Route::get('/libros', [AdminLibroController::class, 'index'])->name('libros.index');
    Route::get('/libros/crear', [AdminLibroController::class, 'create'])->name('libros.create');
    Route::post('/libros', [AdminLibroController::class, 'store'])->name('libros.store');
    Route::get('/libros/{id}/editar', [AdminLibroController::class, 'edit'])->name('libros.edit');
    Route::put('/libros/{id}', [AdminLibroController::class, 'update'])->name('libros.update');
    Route::delete('/libros/{id}', [AdminLibroController::class, 'destroy'])->name('libros.destroy');
    
    Route::post('/categorias/rapido', [AdminLibroController::class, 'storeCategoria'])->name('categorias.rapido');
    
    // Gestión de Banners
    Route::prefix('banners')->name('banners.')->group(function () {
        Route::get('/', [AdminBannerController::class, 'index'])->name('index');
        Route::get('/crear', [AdminBannerController::class, 'create'])->name('create');
        Route::post('/guardar', [AdminBannerController::class, 'store'])->name('store');
        Route::get('/{banner}/editar', [AdminBannerController::class, 'edit'])->name('edit');
        Route::put('/{banner}/actualizar', [AdminBannerController::class, 'update'])->name('update');
        Route::delete('/{banner}', [AdminBannerController::class, 'destroy'])->name('destroy');
        Route::post('/guardar-orden', [AdminBannerController::class, 'guardarOrden'])->name('guardar-orden');
    });

    // Panel Unificado de Clasificaciones
    Route::prefix('categorias')->name('categorias.')->group(function () {
        Route::get('/', [AdminCategoriaController::class, 'index'])->name('index');
        Route::post('/guardar', [AdminCategoriaController::class, 'store'])->name('store');
        Route::delete('/{categoria}', [AdminCategoriaController::class, 'destroy'])->name('destroy');
    });

    // Gestión de Materiales
    Route::prefix('materiales')->name('materiales.')->group(function () {
        Route::get('/', [AdminMaterialController::class, 'index'])->name('index');
        Route::post('/guardar', [AdminMaterialController::class, 'store'])->name('store');
        Route::delete('/{material}', [AdminMaterialController::class, 'destroy'])->name('destroy');
    });

    // Gestión de Destacados del Home
    Route::prefix('destacados')->name('destacados.')->group(function () {
        Route::get('/datos', [AdminDestacadosController::class, 'datos'])->name('datos');
        Route::post('/guardar', [AdminDestacadosController::class, 'store'])->name('store');
    });

    // 🌟 GESTIÓN DE CATÁLOGOS UNIFICADA Y CORREGIDA
    Route::prefix('catalogos')->name('catalogos.')->group(function () {
        Route::get('/', [AdminCatalogoController::class, 'index'])->name('index');
        Route::get('/crear', [AdminCatalogoController::class, 'create'])->name('create');
        Route::post('/guardar', [AdminCatalogoController::class, 'store'])->name('store');
        // Usamos explícitamente {id} para que coincida directo con el controlador
        Route::delete('/eliminar/{id}', [AdminCatalogoController::class, 'destroy'])->name('destroy');
    });
    
});