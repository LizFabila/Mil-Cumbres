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

        $destacadas = Experiencia::with(['zona', 'catalogo'])
            ->latest()
            ->take(3)
            ->get();

        return view('inicio', compact('tipos', 'destacadas'));
    }

    /** C13 · Nuestro modelo */
    public function modelo(): View
    {
        return view('modelo');
    }
}
