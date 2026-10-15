<?php

use App\Http\Controllers\Admin;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RecuperarContrasenaController;
use App\Http\Controllers\Auth\RegistroController;
use App\Http\Controllers\Auth\VerificarCorreoController;
use App\Http\Controllers\ConsultoriaController;
use App\Http\Controllers\ExperienciaController;
use App\Http\Controllers\InicioController;
use App\Http\Controllers\PagoController;
use App\Http\Controllers\SolicitudController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Sitio público (C01 – C13)
|--------------------------------------------------------------------------
*/
Route::get('/', [InicioController::class, 'index'])->name('inicio');                                   // C01
Route::get('/nuestro-modelo', [InicioController::class, 'modelo'])->name('modelo');                     // C13
Route::view('/aviso-de-privacidad', 'privacidad')->name('privacidad');

Route::get('/experiencias', [ExperienciaController::class, 'index'])->name('experiencias.index');       // C02
Route::get('/experiencias/{experiencia}/{slug?}', [ExperienciaController::class, 'show'])
    ->whereNumber('experiencia')->name('experiencias.show');                                            // C03

Route::get('/consultoria', [ConsultoriaController::class, 'index'])->name('consultoria.index');         // C10

Route::middleware('guest')->group(function () {
    Route::get('/ingresar', [LoginController::class, 'create'])->name('login');                        // C04
    Route::post('/ingresar', [LoginController::class, 'store'])->name('login.store');
    Route::get('/registro', [RegistroController::class, 'create'])->name('registro');                  // C05
    Route::post('/registro', [RegistroController::class, 'store'])->name('registro.store');

    Route::get('/olvide-contrasena', [RecuperarContrasenaController::class, 'create'])->name('password.request'); // C14
    Route::post('/olvide-contrasena', [RecuperarContrasenaController::class, 'store'])->name('password.email');
    Route::get('/restablecer-contrasena/{token}', [RecuperarContrasenaController::class, 'edit'])->name('password.reset');
    Route::post('/restablecer-contrasena', [RecuperarContrasenaController::class, 'update'])->name('password.update');
});

// C15 · Verificación de correo: funciona sin sesión, porque sin verificar no se puede iniciar sesión.
Route::get('/verificar-correo', [VerificarCorreoController::class, 'aviso'])->name('verification.notice');
Route::get('/verificar-correo/{id}/{hash}', [VerificarCorreoController::class, 'verificar'])
    ->middleware(['signed', 'throttle:6,1'])->name('verification.verify');
Route::post('/verificar-correo/reenviar', [VerificarCorreoController::class, 'reenviar'])
    ->middleware('throttle:3,1')->name('verification.send');

Route::middleware('auth')->group(function () {
    Route::post('/salir', [LoginController::class, 'destroy'])->name('logout');
});

// Solo cuentas con el correo verificado pueden solicitar, pagar y pedir consultorías.
Route::middleware(['auth', 'verified'])->group(function () {

    Route::get('/solicitar', [SolicitudController::class, 'create'])->name('solicitudes.create');       // C06
    Route::post('/solicitar', [SolicitudController::class, 'store'])->name('solicitudes.store');
    Route::get('/solicitudes/{solicitud}/acuse', [SolicitudController::class, 'acuse'])->name('solicitudes.acuse'); // C07
    Route::get('/mis-solicitudes', [SolicitudController::class, 'index'])->name('solicitudes.index');   // C08
    Route::patch('/solicitudes/{solicitud}/cancelar', [SolicitudController::class, 'cancelar'])->name('solicitudes.cancelar');

    Route::get('/solicitudes/{solicitud}/pago', [PagoController::class, 'create'])->name('pagos.create'); // C09
    Route::post('/solicitudes/{solicitud}/pago', [PagoController::class, 'store'])->name('pagos.store');

    Route::get('/consultoria/solicitar', [ConsultoriaController::class, 'create'])->name('consultoria.create'); // C11
    Route::post('/consultoria/solicitar', [ConsultoriaController::class, 'store'])->name('consultoria.store');
    Route::get('/consultoria/{consultoria}/acuse', [ConsultoriaController::class, 'acuse'])->name('consultoria.acuse'); // C12
});

/*
|--------------------------------------------------------------------------
| Panel admin (A01 – A09)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::redirect('/', '/admin/resumen');

    Route::get('/solicitudes', [Admin\SolicitudController::class, 'index'])->name('solicitudes.index');           // A01
    Route::get('/solicitudes/{solicitud}', [Admin\SolicitudController::class, 'show'])->name('solicitudes.show');  // A02
    Route::patch('/solicitudes/{solicitud}/estado', [Admin\SolicitudController::class, 'estado'])->name('solicitudes.estado');

    Route::get('/pagos', [Admin\PagoController::class, 'index'])->name('pagos.index');                            // A03
    Route::patch('/pagos/{pago}', [Admin\PagoController::class, 'update'])->name('pagos.update');

    Route::get('/consultorias', [Admin\ConsultoriaController::class, 'index'])->name('consultorias.index');       // A04
    Route::get('/consultorias/{consultoria}', [Admin\ConsultoriaController::class, 'show'])->name('consultorias.show');
    Route::patch('/consultorias/{consultoria}/estado', [Admin\ConsultoriaController::class, 'estado'])->name('consultorias.estado');
    Route::post('/consultorias/{consultoria}/seguimiento', [Admin\ConsultoriaController::class, 'seguimiento'])->name('consultorias.seguimiento');

    Route::get('/calendario', [Admin\CalendarioController::class, 'index'])->name('calendario');                  // A05

    Route::resource('experiencias', Admin\ExperienciaController::class)->except('show');                          // A06 / A07

    Route::get('/resumen', [Admin\ResumenController::class, 'index'])->name('resumen');                           // A08

    Route::get('/usuarios', [Admin\UsuarioController::class, 'index'])->name('usuarios.index');                   // A09
    Route::patch('/usuarios/{persona}', [Admin\UsuarioController::class, 'update'])->name('usuarios.update');
    Route::delete('/usuarios/{persona}', [Admin\UsuarioController::class, 'destroy'])->name('usuarios.destroy');
});
