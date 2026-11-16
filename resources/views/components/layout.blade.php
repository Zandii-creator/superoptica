@props([
    'settings',
    'title' => null,
    'description' => null,
    'canonical' => null,
    'jsonLd' => null,
])

@php
    $siteName = $settings->site_name ?: 'SuperÓptica 20 20';
    $pageTitle = filled($title) ? $title.' | '.$siteName : $siteName;
    $metaDescription = $description ?: $settings->seo_description ?: 'Espejuelos, monturas, lentes de contacto y líquidos en Ciego de Ávila.';
    $canonicalUrl = $canonical ?: url()->current();
@endphp

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#0b2545">

    <title>{{ $pageTitle }}</title>
    <meta name="description" content="{{ $metaDescription }}">
    <link rel="canonical" href="{{ $canonicalUrl }}">

    <meta property="og:type" content="website">
    <meta property="og:site_name" content="{{ $siteName }}">
    <meta property="og:title" content="{{ $pageTitle }}">
    <meta property="og:description" content="{{ $metaDescription }}">
    <meta property="og:url" content="{{ $canonicalUrl }}">
    <meta property="og:locale" content="es_CU">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @if ($jsonLd)
        <script type="application/ld+json">{!! json_encode($jsonLd, JSON_UNESCAPED_UNICODE) !!}</script>
    @endif
</head>
<body class="flex min-h-screen flex-col bg-white font-sans text-ink antialiased">
    <a
        href="#contenido"
        class="sr-only focus:not-sr-only focus:absolute focus:left-4 focus:top-4 focus:z-50 focus:rounded-lg focus:bg-brand focus:px-4 focus:py-2 focus:font-semibold focus:text-white"
    >
        Saltar al contenido
    </a>

    <header class="sticky top-0 z-40 border-b border-slate-200 bg-white/95 backdrop-blur">
        <div class="mx-auto flex max-w-6xl flex-wrap items-center justify-between gap-x-6 gap-y-3 px-4 py-3 sm:px-6">
            <a href="{{ route('home') }}" class="flex items-center gap-3" aria-label="{{ $siteName }}, ir al inicio">
                <span
                    aria-hidden="true"
                    class="grid h-11 w-11 shrink-0 place-items-center rounded-xl border-2 border-brand font-display text-sm font-bold tracking-tight text-navy"
                >
                    20 20
                </span>
                <span class="leading-tight">
                    <span class="block font-display text-base font-semibold text-navy">{{ $siteName }}</span>
                    @if ($settings->tagline)
                        <span class="block text-xs text-slate-500">{{ $settings->tagline }}</span>
                    @endif
                </span>
            </a>

            <nav aria-label="Navegación principal">
                <ul class="flex flex-wrap items-center gap-x-5 gap-y-2 text-sm font-medium">
                    <li>
                        <a
                            href="{{ route('home') }}"
                            @class([
                                'rounded-md py-1 transition hover:text-brand',
                                'text-brand' => request()->routeIs('home'),
                                'text-navy' => ! request()->routeIs('home'),
                            ])
                        >
                            Inicio
                        </a>
                    </li>
                    <li>
                        <a
                            href="{{ route('catalog.index') }}"
                            @class([
                                'rounded-md py-1 transition hover:text-brand',
                                'text-brand' => request()->routeIs('catalog.*', 'products.*'),
                                'text-navy' => ! request()->routeIs('catalog.*', 'products.*'),
                            ])
                        >
                            Catálogo
                        </a>
                    </li>
                    <li>
                        <a
                            href="{{ route('services.index') }}"
                            @class([
                                'rounded-md py-1 transition hover:text-brand',
                                'text-brand' => request()->routeIs('services.*'),
                                'text-navy' => ! request()->routeIs('services.*'),
                            ])
                        >
                            Servicios
                        </a>
                    </li>
                    <li>
                        <a
                            href="{{ route('store.index') }}"
                            @class([
                                'rounded-md py-1 transition hover:text-brand',
                                'text-brand' => request()->routeIs('store.*'),
                                'text-navy' => ! request()->routeIs('store.*'),
                            ])
                        >
                            Tienda
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
    </header>

    <main id="contenido" class="flex-1">
        {{ $slot }}
    </main>

    <footer class="mt-16 border-t border-slate-200 bg-slate-50">
        <div class="mx-auto grid max-w-6xl gap-10 px-4 pt-12 pb-24 sm:px-6 sm:pb-12 md:grid-cols-3">
            <div>
                <p class="font-display text-lg font-semibold text-navy">{{ $siteName }}</p>
                @if ($settings->tagline)
                    <p class="mt-2 text-sm text-slate-600">{{ $settings->tagline }}</p>
                @endif
                <p class="mt-4 text-sm text-slate-600">
                    Espejuelos, monturas, lentes de contacto y líquidos en Ciego de Ávila.
                </p>
            </div>

            <div>
                <h2 class="font-display text-sm font-semibold uppercase tracking-widest text-slate-500">Visítanos</h2>
                @if ($settings->fullAddress())
                    <p class="mt-3 text-sm text-slate-700">{{ $settings->fullAddress() }}</p>
                @endif

                <dl class="mt-3 space-y-1 text-sm text-slate-600">
                    @foreach ($settings->weeklyHours() as $day)
                        <div class="flex justify-between gap-4">
                            <dt>{{ $day['day'] }}</dt>
                            <dd class="{{ $day['hours'] ? 'text-slate-700' : 'text-slate-400' }}">
                                {{ $day['hours'] ?? 'Cerrado' }}
                            </dd>
                        </div>
                    @endforeach
                </dl>
            </div>

            <div>
                <h2 class="font-display text-sm font-semibold uppercase tracking-widest text-slate-500">Contacto</h2>

                <ul class="mt-3 space-y-2 text-sm">
                    @if ($settings->whatsapp_number)
                        <li>
                            <a
                                href="{{ \App\Support\WhatsApp::link('Hola, quiero información sobre los espejuelos.') }}"
                                class="font-medium text-brand underline-offset-4 hover:underline"
                                rel="noopener"
                                target="_blank"
                            >
                                Escríbenos por WhatsApp
                            </a>
                        </li>
                    @endif

                    @if ($settings->phoneUrl())
                        <li>
                            <a href="{{ $settings->phoneUrl() }}" class="text-slate-700 underline-offset-4 hover:underline">
                                {{ $settings->phone_number }}
                            </a>
                        </li>
                    @endif

                    @if ($settings->telegramUrl())
                        <li>
                            <a
                                href="{{ $settings->telegramUrl() }}"
                                class="text-slate-700 underline-offset-4 hover:underline"
                                rel="noopener"
                                target="_blank"
                            >
                                Telegram
                            </a>
                        </li>
                    @endif

                    @foreach ($settings->socialLinks() as $link)
                        <li>
                            <a
                                href="{{ $link['url'] }}"
                                class="text-slate-700 underline-offset-4 hover:underline"
                                rel="noopener"
                                target="_blank"
                            >
                                {{ $link['label'] }}
                            </a>
                        </li>
                    @endforeach
                </ul>

                <p class="mt-6 text-xs text-slate-500">{{ $settings->currency_notice }}</p>

                @if ($settings->hasPrivacyNotice())
                    <details class="mt-3 text-xs text-slate-500">
                        <summary class="cursor-pointer font-medium text-slate-600">Aviso de privacidad</summary>
                        <p class="mt-2">{{ $settings->privacy_notice }}</p>
                    </details>
                @endif
            </div>
        </div>
    </footer>

    <x-whatsapp-button :settings="$settings" />
</body>
</html>
