<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DiagramaController;
use App\Http\Controllers\MascotaController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\VentaController;
use Illuminate\Support\Facades\Route;

// Solo invitados (RF01)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'mostrarLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'iniciarSesion'])->name('login.post');
});

// Todo el sistema protegido con sesión (RF01)
Route::middleware('auth')->group(function () {
    Route::get('/', fn () => redirect()->route('dashboard'));

    Route::post('/logout', [AuthController::class, 'cerrarSesion'])->name('logout');

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('categorias', CategoriaController::class)->except('show');
    Route::resource('clientes', ClienteController::class)->except('show');
    Route::resource('mascotas', MascotaController::class)->except('show');
    Route::resource('productos', ProductoController::class)->except('show');
    Route::resource('ventas', VentaController::class)->only(['index', 'create', 'store', 'show']);

    // RF11: repositorio de diseño (StarUML)
    Route::get('/diagramas', [DiagramaController::class, 'index'])->name('diagramas.index');
    Route::post('/diagramas', [DiagramaController::class, 'store'])->name('diagramas.store');
});