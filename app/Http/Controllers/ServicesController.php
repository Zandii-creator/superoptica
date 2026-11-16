<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\SiteSettings;
use Illuminate\Contracts\View\View;

class ServicesController extends Controller
{
    public function __invoke(): View
    {
        return view('services.index', [
            'services' => Service::query()->active()->ordered()->get(),
            'settings' => SiteSettings::current(),
            'title' => 'Servicios ópticos en Ciego de Ávila',
            'description' => 'Examen visual, ajuste de monturas y graduación en Ciego de Ávila. Consulta cada servicio por WhatsApp.',
            'canonical' => route('services.index'),
        ]);
    }
}
