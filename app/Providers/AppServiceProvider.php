<?php

namespace App\Providers;

use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::defaultView('vendor.pagination.mil-cumbres');
        Carbon::setLocale('es');

        // Categorías del desplegable "Experiencias" de la barra: disponibles en todas
        // las vistas que usan layouts.app (cliente, login, registro, solicitudes, etc.).
        View::composer('layouts.app', function ($vista) {
            $vista->with('categoriasMenu', \App\Models\CatalogoExperiencia::query()
                ->orderBy('id_catalogo_exp')
                ->get(['id_catalogo_exp', 'tipo']));

            // Experiencias con horario, agrupadas por tipo, para el riel lateral.
            $vista->with('rielGrupos', \App\Models\Experiencia::with(['zona', 'zonas', 'catalogo', 'disponibilidades'])
                ->whereHas('disponibilidades')
                ->orderBy('id_experiencia')
                ->get()
                ->groupBy(fn ($e) => $e->catalogo?->tipo ?? 'Otras', true));
        });

        // Correo de "Olvidé mi contraseña" (C14) en español.
        ResetPassword::toMailUsing(function ($persona, string $token) {
            $enlace = route('password.reset', ['token' => $token, 'email' => $persona->getEmailForPasswordReset()]);

            return (new MailMessage)
                ->subject('Restablece tu contraseña · Mil Cumbres')
                ->greeting("Hola, {$persona->nombre}")
                ->line('Recibimos una solicitud para restablecer la contraseña de tu cuenta.')
                ->action('Crear nueva contraseña', $enlace)
                ->line('El enlace vence en 60 minutos.')
                ->line('Si no pediste este cambio, ignora este correo; tu contraseña seguirá igual.')
                ->salutation('Equipo Mil Cumbres');
        });

        // Correo de verificación al crear cuenta (C15) en español.
        VerifyEmail::toMailUsing(function ($persona, string $enlace) {
            return (new MailMessage)
                ->subject('Confirma tu correo · Mil Cumbres')
                ->greeting("Hola, {$persona->nombre}")
                ->line('Gracias por crear tu cuenta en Mil Cumbres. Confirma que este correo es tuyo para poder solicitar experiencias.')
                ->action('Verificar mi correo', $enlace)
                ->line('El enlace vence en 60 minutos. Si no creaste esta cuenta, ignora este correo.')
                ->salutation('Equipo Mil Cumbres');
        });
    }
}
