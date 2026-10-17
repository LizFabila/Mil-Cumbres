<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'admin' => \App\Http\Middleware\EsAdmin::class,
        ]);
        $middleware->redirectGuestsTo(fn () => route('login'));
        $middleware->redirectUsersTo(fn () => route('inicio'));
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // Si la sesión caduca o el token ya no coincide al enviar una solicitud
        // o un pago, regresamos al formulario con un aviso en lugar de mostrar
        // "419 Page Expired". Laravel convierte TokenMismatchException en un
        // HttpException 419 antes de llamar a estos renderizadores.
        $exceptions->render(function (HttpException $e, Request $request) {
            if ($e->getStatusCode() !== 419 || ! $request->isMethod('POST') || $request->expectsJson()) {
                return null;
            }

            if (! $request->routeIs('pagos.store', 'solicitudes.store')) {
                return null;
            }

            return redirect()->back()->withErrors([
                'sesion' => 'Tu sesión expiró antes de enviar el formulario. Revisa tus datos y vuelve a enviarlo.',
            ]);
        });
    })->create();
