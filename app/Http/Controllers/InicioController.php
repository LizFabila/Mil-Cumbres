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
        $carrusel = Experiencia::with(['zona', 'zonas', 'catalogo'])
            ->whereHas('disponibilidades')
            ->orderBy('nombre')
            ->get();

        return view('inicio', compact('tipos', 'destacadas', 'carrusel'));
    }

    /** C13 · Nuestro modelo */
    public function modelo(): View
    {
        return view('modelo');
    }
}
