<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Persona;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Throwable;

/**
 * C15 · Verificación del correo electrónico.
 * Una cuenta sin verificar no puede iniciar sesión; el correo pendiente
 * se guarda en la sesión ("correo_por_verificar") para mostrarlo y reenviarlo.
 */
class VerificarCorreoController extends Controller
{
    /** Pantalla "Revisa tu correo". */
    public function aviso(Request $request): View|RedirectResponse
    {
        // Sesiones abiertas antes de este cambio: se cierran hasta verificar.
        if ($request->user() && ! $request->user()->hasVerifiedEmail()) {
            $correo = $request->user()->email;
            Auth::logout();
            $request->session()->regenerate();
            $request->session()->put('correo_por_verificar', $correo);
        }

        $correo = $request->session()->get('correo_por_verificar');

        if (! $correo) {
            return redirect()->route('login');
        }

        return view('Register.verificar-correo', ['correo' => $correo]);
    }

    /** Enlace del correo: la ruta valida la firma y el vencimiento. */
    public function verificar(Request $request, string $id, string $hash): RedirectResponse
    {
        $persona = Persona::findOrFail($id);

        abort_unless(hash_equals(sha1($persona->getEmailForVerification()), $hash), 403);

        if (! $persona->hasVerifiedEmail()) {
            $persona->markEmailAsVerified();
            event(new Verified($persona));
        }

        $request->session()->forget('correo_por_verificar');
        Auth::login($persona);
        $request->session()->regenerate();

        $destino = $persona->esAdmin() ? route('admin.resumen') : route('solicitudes.index');

        return redirect($destino)->with('ok', '¡Listo! Tu correo quedó verificado.');
    }

    /** Vuelve a enviar el correo de verificación. */
    public function reenviar(Request $request): RedirectResponse
    {
        $correo = $request->session()->get('correo_por_verificar');
        $persona = $correo ? Persona::where('email', $correo)->first() : null;

        if (! $persona) {
            return redirect()->route('login');
        }

        if ($persona->hasVerifiedEmail()) {
            $request->session()->forget('correo_por_verificar');

            return redirect()->route('login')->with('ok', 'Tu correo ya está verificado. Inicia sesión.');
        }

        try {
            $persona->sendEmailVerificationNotification();
        } catch (Throwable $e) {
            report($e);

            return back()->withErrors(['email' => 'No pudimos enviar el correo. Revisa tu conexión a internet e inténtalo de nuevo.']);
        }

        return back()->with('ok', 'Te enviamos un nuevo enlace de verificación.');
    }
}
