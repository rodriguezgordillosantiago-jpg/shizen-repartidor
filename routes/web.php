<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InicioController;
use App\Http\Controllers\PedidoController;
use App\Http\Controllers\PerfilController;
use Illuminate\Http\Request;

// Inicio
Route::get('/', function() {
    return view('auth.login');
})->name('login');
Route::get('/login', function() { return view('auth.login'); });
Route::get('/php/login.php', function() { return view('auth.login'); });
Route::get('/index.html', function() { return redirect('/'); });
Route::get('/index.php', function() { return redirect('/'); });
Route::get('/php/index.html', function() { return redirect('/'); });
Route::get('/php/index.php', function() { return redirect('/'); });
Route::get('/pages/index.html', function() { return redirect('/'); });
Route::get('/pages/index.php', function() { return redirect('/'); });

Route::get('/inicio', [InicioController::class, 'index'])->name('inicio');
Route::get('/pages/inicio.html', [InicioController::class, 'index']);

// Pedidos y Entregas
Route::get('/pedidos', [PedidoController::class, 'pedidos'])->name('pedidos');
Route::get('/pages/pedidos.html', [PedidoController::class, 'pedidos']);
Route::get('/activos', [PedidoController::class, 'activos'])->name('activos');
Route::get('/pages/activos.html', [PedidoController::class, 'activos']);
Route::get('/historial', [PedidoController::class, 'historial'])->name('historial');
Route::get('/pages/historial.html', [PedidoController::class, 'historial']);

// Perfil y Configuración
Route::get('/perfil', [PerfilController::class, 'index'])->name('perfil');
Route::get('/pages/perfil.html', [PerfilController::class, 'index']);
Route::get('/php/perfil.php', [PerfilController::class, 'index']);
Route::get('/configuracion', [PerfilController::class, 'configuracion'])->name('configuracion');
Route::get('/pages/configuracion.html', [PerfilController::class, 'configuracion']);
Route::post('/configuracion', [PerfilController::class, 'guardarConfiguracion'])->name('configuracion.guardar');
Route::post('/perfil', [PerfilController::class, 'update'])->name('perfil.update');
Route::post('/logout', function (Request $request) {
    $request->session()->flush();
    return redirect()->route('login');
})->name('logout');

// Formularios y Login
Route::post('/auth/login.php', [\App\Http\Controllers\AuthController::class, 'login']);
Route::post('/auth/login', [\App\Http\Controllers\AuthController::class, 'login']);
Route::post('/login', [\App\Http\Controllers\AuthController::class, 'login'])->name('login.submit');
