<?php

namespace App\Notifications;

use App\Models\Solicitud;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Carbon;
use Illuminate\Support\HtmlString;

/**
 * Invitación a calificar las experiencias de una solicitud completada.
 * Se envía cuando el administrador marca la solicitud como "completada".
 */
class InvitacionCalificar extends Notification
{
    public function __construct(public Solicitud $solicitud)
    {
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /** Experiencias de la solicitud cuya fecha ya llegó (solo esas se pueden calificar). */
    public static function experienciasVividas(Solicitud $solicitud)
    {
        return $solicitud->experiencias->filter(function ($exp) use ($solicitud) {
            $fecha = $exp->pivot->fecha_deseada ?? $solicitud->fecha_deseada;

            return $fecha && ! Carbon::parse($fecha)->startOfDay()->isFuture();
        })->values();
    }

    public function toMail(object $notifiable): MailMessage
    {
        $experiencias = self::experienciasVividas($this->solicitud);
        $primera = $experiencias->first();

        $asunto = $experiencias->count() === 1
            ? "¿Cómo te fue en {$primera->nombre}?"
            : '¿Cómo te fue en tus experiencias con Mil Cumbres?';

        $mensaje = (new MailMessage)
            ->subject($asunto.' · Mil Cumbres')
            ->greeting("Hola, {$notifiable->nombre}")
            ->line('Gracias por vivir Valle de Bravo con nosotros. Tu opinión nos ayuda a cuidar cada experiencia y la montaña. Solo toma un minuto.');

        foreach ($experiencias as $exp) {
            $fecha = $exp->pivot->fecha_deseada ?? $this->solicitud->fecha_deseada;
            $mensaje->line(new HtmlString(
                '<strong>'.e($exp->nombre).'</strong>'
                .($fecha ? ' · '.e(Carbon::parse($fecha)->translatedFormat('j \d\e F')) : '')
                .'<br>'.$this->estrellas($exp->pivot->id_asigna_exp)
            ));
        }

        return $mensaje
            ->action('Calificar mi experiencia', route('calificaciones.create', $primera->pivot->id_asigna_exp))
            ->line('Puedes calificar durante los próximos '.config('milcumbres.calificaciones.dias_para_calificar', 30).' días.')
            ->salutation('Equipo Mil Cumbres');
    }

    /** Cinco estrellas enlazadas: cada una abre el formulario con esa calificación marcada. */
    private function estrellas(int $idAsignacion): string
    {
        $html = '';
        foreach (range(1, 5) as $n) {
            $url = route('calificaciones.create', ['asignacion' => $idAsignacion, 'estrellas' => $n]);
            $html .= '<a href="'.e($url).'" title="'.$n.' de 5" '
                .'style="display:inline-block;margin:6px 4px 0 0;padding:6px 9px;border:1px solid #DCE5DB;'
                .'border-radius:6px;color:#D69A2D;font-size:20px;line-height:1;text-decoration:none">★</a>';
        }

        return $html;
    }
}
