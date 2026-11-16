<x-layout :settings="$settings" :title="$title" :description="$description" :canonical="$canonical" :json-ld="$jsonLd">
    <section class="relative overflow-hidden border-b border-slate-200 bg-gradient-to-b from-brand-soft/70 via-white to-white">
        <div class="mx-auto grid max-w-6xl gap-10 px-4 py-14 sm:px-6 md:grid-cols-[1.15fr_0.85fr] md:items-center md:py-20">
            <div>
                <p class="inline-flex items-center gap-2 rounded-full border border-brand/20 bg-white px-3 py-1 text-xs font-semibold uppercase tracking-widest text-brand">
                    <span aria-hidden="true" class="h-2 w-2 rounded-full bg-brand"></span>
                    Ciego de Ávila, Cuba
                </p>

                <h1 class="mt-5 font-display text-3xl font-bold leading-tight text-navy sm:text-4xl md:text-5xl">
                    {{ $settings->heroTitle() }}
                </h1>

                <p class="mt-4 max-w-xl text-base leading-relaxed text-slate-600 sm:text-lg">
                    {{ $settings->heroSubtitle() }}
                </p>

                <div class="mt-8 flex flex-wrap gap-3">
                    <a
                        href="{{ route('catalog.index') }}"
                        class="inline-flex items-center justify-center rounded-xl bg-brand px-6 py-3 font-semibold text-white transition hover:bg-brand-dark focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-brand"
                    >
                        Ver el catálogo
                    </a>

                    @if ($settings->whatsapp_number)
                        <a
                            href="{{ \App\Support\WhatsApp::link('Hola, quiero información sobre los espejuelos.') }}"
                            class="inline-flex items-center justify-center rounded-xl border border-navy/15 bg-white px-6 py-3 font-semibold text-navy transition hover:border-brand hover:text-brand focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-brand"
                            rel="noopener"
                            target="_blank"
                        >
                            Consultar por WhatsApp
                        </a>
                    @endif
                </div>

                <p class="mt-5 text-sm text-slate-500">{{ $settings->currency_notice }}</p>
            </div>

            <div class="relative mx-auto w-full max-w-sm">
                <div class="absolute inset-6 rounded-full bg-brand/10 blur-2xl" aria-hidden="true"></div>
                <div class="relative grid aspect-square place-items-center rounded-[2rem] border border-brand/20 bg-white shadow-xl shadow-brand/10">
                    <div class="grid h-40 w-40 place-items-center rounded-[1.75rem] border-4 border-brand font-display text-4xl font-bold tracking-tight text-navy sm:h-48 sm:w-48 sm:text-5xl">
                        20 20
                    </div>
                    <p class="absolute bottom-6 text-xs font-semibold uppercase tracking-[0.3em] text-slate-400">
                        Visión de futuro
                    </p>
                </div>
            </div>
        </div>
    </section>

    @if ($featured->isNotEmpty())
        <section class="mx-auto max-w-6xl px-4 py-14 sm:px-6" aria-labelledby="destacados">
            <div class="flex flex-wrap items-end justify-between gap-4">
                <div>
                    <h2 id="destacados" class="font-display text-2xl font-bold text-navy sm:text-3xl">
                        Modelos destacados
                    </h2>
                    <p class="mt-2 text-slate-600">Una muestra de lo que puedes encontrar en la tienda.</p>
                </div>

                <a href="{{ route('catalog.index') }}" class="text-sm font-semibold text-brand underline-offset-4 hover:underline">
                    Ver todos los modelos
                </a>
            </div>

            <div class="mt-8 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($featured as $product)
                    <x-product-card :product="$product" />
                @endforeach
            </div>

            <p class="mt-6 text-sm text-slate-500">{{ $settings->currency_notice }}</p>
        </section>
    @endif

    @if ($services->isNotEmpty())
        <section id="servicios" class="border-y border-slate-200 bg-slate-50">
            <div class="mx-auto max-w-6xl px-4 py-14 sm:px-6">
                <div class="flex flex-wrap items-end justify-between gap-4">
                    <div>
                        <h2 class="font-display text-2xl font-bold text-navy sm:text-3xl">Servicios</h2>
                        <p class="mt-2 max-w-2xl text-slate-600">
                            Te acompañamos antes, durante y después de elegir tus espejuelos.
                        </p>
                    </div>

                    <a
                        href="{{ route('services.index') }}"
                        class="text-sm font-semibold text-brand underline-offset-4 hover:underline"
                    >
                        Ver todos los servicios
                    </a>
                </div>

                <ul class="mt-8 grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach ($services as $service)
                        <li class="rounded-2xl border border-slate-200 bg-white p-6">
                            <span
                                aria-hidden="true"
                                class="grid h-10 w-10 place-items-center rounded-xl bg-brand-soft font-display text-sm font-bold text-brand"
                            >
                                {{ str_pad((string) $loop->iteration, 2, '0', STR_PAD_LEFT) }}
                            </span>
                            <h3 class="mt-4 font-display text-lg font-semibold text-navy">{{ $service->name }}</h3>
                            <p class="mt-2 text-sm leading-relaxed text-slate-600">{{ $service->description }}</p>
                        </li>
                    @endforeach
                </ul>
            </div>
        </section>
    @endif

    <section id="tienda" class="mx-auto max-w-6xl px-4 py-14 sm:px-6">
        <div class="grid gap-8 rounded-3xl border border-slate-200 bg-white p-6 sm:p-10 md:grid-cols-2">
            <div>
                <h2 class="font-display text-2xl font-bold text-navy sm:text-3xl">Visítanos en Ciego de Ávila</h2>

                @if ($settings->fullAddress())
                    <p class="mt-3 text-base leading-relaxed text-slate-600">{{ $settings->fullAddress() }}</p>
                @endif

                @if ($settings->map_url)
                    <a
                        href="{{ $settings->map_url }}"
                        class="mt-5 inline-flex items-center gap-2 text-sm font-semibold text-brand underline-offset-4 hover:underline"
                        rel="noopener"
                        target="_blank"
                    >
                        Abrir la ubicación en el mapa
                    </a>
                @endif

                <p class="mt-4">
                    <a
                        href="{{ route('store.index') }}"
                        class="text-sm font-semibold text-brand underline-offset-4 hover:underline"
                    >
                        Ver horarios y cómo llegar
                    </a>
                </p>
            </div>

            <div>
                <h3 class="font-display text-sm font-semibold uppercase tracking-widest text-slate-500">Horario</h3>
                <dl class="mt-3 space-y-1.5 text-sm">
                    @foreach ($settings->weeklyHours() as $day)
                        <div class="flex justify-between gap-4 border-b border-slate-100 pb-1.5">
                            <dt class="text-slate-600">{{ $day['day'] }}</dt>
                            <dd class="{{ $day['hours'] ? 'font-medium text-navy' : 'text-slate-400' }}">
                                {{ $day['hours'] ?? 'Cerrado' }}
                            </dd>
                        </div>
                    @endforeach
                </dl>
            </div>
        </div>
    </section>
</x-layout>
