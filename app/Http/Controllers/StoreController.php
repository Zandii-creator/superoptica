<?php

namespace App\Http\Controllers;

use App\Models\SiteSettings;
use Illuminate\Contracts\View\View;

class StoreController extends Controller
{
    public function __invoke(): View
    {
        return view('store.index', [
            'settings' => SiteSettings::current(),
            'title' => 'Tienda y contacto en Ciego de Ávila',
            'description' => 'Dirección, horarios y canales de contacto de SuperÓptica 20 20 en Ciego de Ávila.',
            'canonical' => route('store.index'),
        ]);
    }
}
