<?php

namespace App\Http\Controllers;

use App\Models\CatalogoExperiencia;
use App\Models\Experiencia;
use Illuminate\View\View;

class InicioController extends Controller
{
    /** C01 · Inicio */
    public function index(): View
    {
        $tipos = CatalogoExperiencia::withCount('experiencias')->orderBy('id_catalogo_exp')->get();

        $destacadas = Experiencia::with(['zona', 'zonas', 'catalogo'])
            ->latest()
            ->take(3)
            ->get();

        // Carrusel del hero: experiencias con horario programado, en el mismo orden que el catálogo.
        $carrusel = Experiencia::with(['zona', 'zonas', 'catalogo', 'disponibilidades'])
            ->whereHas('disponibilidades')
            ->orderBy('id_experiencia')
            ->get();

        // Reseñas reales: solo opiniones publicadas con permiso del cliente y con comentario.
        $resenas = \App\Models\Calificacion::publicadas()
            ->whereNotNull('comentario')
            ->with(['persona', 'asignacion.experiencia'])
            ->latest()
            ->take(6)
            ->get();

        $esEjemplo = false;

        if ($resenas->isEmpty() && config('milcumbres.resenas_ejemplo')) {
            $esEjemplo = true;
            $resenas = collect([
                [5, 'Una mañana increíble. El guía nos enseñó a identificar cada especie y el ritmo fue perfecto para disfrutar sin prisa.', 'Ana', 'M.', 'Aviturismo guiado'],
                [5, 'Salí renovado. Se siente auténtico, con respeto por el lugar y por la gente que lo cuida.', 'Luis', 'R.', 'Temazcal tradicional'],
                [4, 'Muy bien organizado, desde la reservación hasta el recorrido. Repetiría con mi familia.', 'Sofía', 'G.', 'Sendero del bosque de niebla'],
            ])->map(fn ($r) => (object) [
                'estrellas' => $r[0],
                'comentario' => $r[1],
                'persona' => (object) ['nombre' => $r[2], 'ap' => $r[3]],
                'asignacion' => (object) ['experiencia' => (object) ['nombre' => $r[4]]],
            ]);
        }

        return view('inicio', compact('tipos', 'destacadas', 'carrusel', 'resenas', 'esEjemplo'));
    }

    /** C13 · Nuestro modelo */
    public function modelo(): View
    {
        return view('modelo');
    }
}
