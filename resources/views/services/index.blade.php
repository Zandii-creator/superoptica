<x-layout
    :settings="$settings"
    :title="$title"
    :description="$description"
    :canonical="$canonical"
>
    <div class="mx-auto max-w-6xl px-4 py-10 sm:px-6">
        <nav aria-label="Ruta de navegación" class="text-sm text-slate-500">
            <a href="{{ route('home') }}" class="underline-offset-4 hover:text-brand hover:underline">Inicio</a>
            <span aria-hidden="true"> / </span>
            <span aria-current="page" class="text-slate-700">Servicios</span>
        </nav>

        <header class="mt-4 max-w-3xl">
            <h1 class="font-display text-3xl font-bold text-navy sm:text-4xl">Servicios</h1>
            <p class="mt-3 text-base leading-relaxed text-slate-600">
                Te acompañamos antes, durante y después de elegir tus espejuelos. Estos son los servicios que
                ofrecemos en la tienda de Ciego de Ávila.
            </p>
        </header>

        @if ($services->isEmpty())
            <div class="mt-10 rounded-2xl border border-dashed border-slate-300 bg-slate-50 p-8 text-center">
                <h2 class="font-display text-xl font-semibold text-navy">Todavía no hay servicios publicados</h2>
                <p class="mx-auto mt-2 max-w-md text-sm text-slate-600">
                    Estamos actualizando esta sección. Escríbenos y te contamos cómo podemos ayudarte.
                </p>

                @if ($settings->whatsapp_number)
                    <a
                        href="{{ \App\Support\WhatsApp::link('Hola, quiero información sobre los servicios de la óptica.') }}"
                        class="mt-5 inline-flex items-center justify-center rounded-xl bg-brand px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-brand-dark"
                        rel="noopener"
                        target="_blank"
                    >
                        Consultar por WhatsApp
                    </a>
                @endif
            </div>
        @else
            <ul class="mt-10 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($services as $service)
                    <li class="flex flex-col rounded-2xl border border-slate-200 bg-white p-6">
                        <span class="grid h-11 w-11 place-items-center rounded-xl bg-brand-soft text-brand">
                            <x-service-icon :name="$service->icon" />
                        </span>

                        <h2 class="mt-4 font-display text-lg font-semibold text-navy">{{ $service->name }}</h2>

                        <p class="mt-2 flex-1 text-sm leading-relaxed text-slate-600">{{ $service->description }}</p>

                        @if ($settings->whatsapp_number)
                            <a
                                href="{{ \App\Support\WhatsApp::serviceLink($service) }}"
                                class="mt-5 inline-flex items-center justify-center rounded-xl border border-brand/20 bg-white px-4 py-2.5 text-sm font-semibold text-brand transition hover:border-brand hover:bg-brand-soft focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-brand"
                                rel="noopener"
                                target="_blank"
                            >
                                Consultar por WhatsApp
                            </a>
                        @endif
                    </li>
                @endforeach
            </ul>
        @endif

        <section class="mt-12 rounded-3xl border border-slate-200 bg-slate-50 p-6 sm:p-10">
            <div class="flex flex-wrap items-center justify-between gap-4">
                <div class="max-w-xl">
                    <h2 class="font-display text-2xl font-bold text-navy">¿Buscas un modelo concreto?</h2>
                    <p class="mt-2 text-sm leading-relaxed text-slate-600">
                        Revisa el catálogo o visítanos en la tienda. La compra se concreta en persona o por WhatsApp.
                    </p>
                </div>

                <a
                    href="{{ route('catalog.index') }}"
                    class="inline-flex items-center justify-center rounded-xl bg-brand px-6 py-3 font-semibold text-white transition hover:bg-brand-dark focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-brand"
                >
                    Ver el catálogo
                </a>
            </div>
        </section>
    </div>
</x-layout>
