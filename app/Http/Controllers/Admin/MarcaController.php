<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ConfiguracionSitio;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class MarcaController extends Controller
{
    /** A10 · Identidad del sitio (nombre y logotipo) */
    public function edit(): View
    {
        return view('Dashboard.marca.edit', [
            'config' => ConfiguracionSitio::firstOrCreate([], [
                'nombre' => 'Mil Cumbres',
                'lema' => 'Turismo Regenerativo',
            ]),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $datos = $request->validate([
            'nombre' => ['required', 'string', 'max:60'],
            'lema' => ['nullable', 'string', 'max:80'],
            'logo_archivo' => ['nullable', 'image', 'mimes:png,jpg,jpeg,webp', 'max:2048'],
            'quitar_logo' => ['nullable', 'boolean'],
        ], [
            'logo_archivo.image' => 'El logotipo debe ser una imagen.',
            'logo_archivo.mimes' => 'El logotipo debe ser PNG, JPG o WEBP.',
            'logo_archivo.max' => 'El logotipo no puede pesar más de 2 MB.',
        ]);

        $config = ConfiguracionSitio::firstOrCreate([], ['nombre' => 'Mil Cumbres']);
        $campos = ['nombre' => $datos['nombre'], 'lema' => $datos['lema'] ?? null];

        if ($request->hasFile('logo_archivo') || $request->boolean('quitar_logo')) {
            if ($config->logo) {
                Storage::disk('public')->delete($config->logo);
            }
            $campos['logo'] = $request->hasFile('logo_archivo')
                ? $request->file('logo_archivo')->store('marca', 'public')
                : null;
        }

        $config->update($campos);

        return redirect()->route('admin.marca.edit')->with('ok', 'Identidad del sitio actualizada.');
    }

    /** Regresa al logotipo original y borra el personalizado. */
    public function restaurarLogo(): RedirectResponse
    {
        $config = ConfiguracionSitio::firstOrCreate([], ['nombre' => 'Mil Cumbres']);

        if ($config->logo) {
            Storage::disk('public')->delete($config->logo);
            $config->update(['logo' => null]);
        }

        return redirect()->route('admin.marca.edit')->with('ok', 'Se restauró el logotipo original.');
    }
}
