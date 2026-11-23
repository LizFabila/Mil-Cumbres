<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ConfiguracionSitio;
use App\Support\Temas;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AparienciaController extends Controller
{
    /** A12 · Apariencia: paleta de colores del sitio */
    public function edit(): View
    {
        return view('Dashboard.apariencia.edit', [
            'temas' => Temas::catalogo(),
            'activo' => Temas::clave(),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $datos = $request->validate([
            'tema' => ['required', 'string', 'in:'.implode(',', array_keys(Temas::catalogo()))],
        ], [
            'tema.required' => 'Elige una paleta.',
            'tema.in' => 'La paleta elegida no existe.',
        ]);

        ConfiguracionSitio::firstOrCreate([], ['nombre' => 'Mil Cumbres'])
            ->update(['tema' => $datos['tema']]);

        return redirect()->route('admin.apariencia.edit')->with('ok', 'Paleta de colores actualizada.');
    }
}
