<x-layout
    :settings="$settings"
    :title="$title"
    :description="$description"
    :canonical="$canonical"
    :json-ld="$jsonLd"
>
    <div class="mx-auto max-w-6xl px-4 py-10 sm:px-6">
        <nav aria-label="Ruta de navegación" class="text-sm text-slate-500">
            <a href="{{ route('home') }}" class="underline-offset-4 hover:text-brand hover:underline">Inicio</a>
            <span aria-hidden="true"> / </span>
            <a href="{{ route('catalog.index') }}" class="underline-offset-4 hover:text-brand hover:underline">Catálogo</a>
            <span aria-hidden="true"> / </span>
            <span aria-current="page" class="text-slate-700">{{ $product->displayName() }}</span>
        </nav>

        <div class="mt-6 grid gap-10 md:grid-cols-2">
            <div>
                <div class="grid aspect-square place-items-center overflow-hidden rounded-2xl border border-slate-200 bg-slate-50">
                    @if ($product->coverUrl())
                        <img
                            src="{{ $product->coverUrl() }}"
                            alt="Montura {{ $product->displayName() }}"
                            width="720"
                            height="720"
                            decoding="async"
                            class="h-full w-full object-contain p-6"
                        >
                    @else
                        <svg
                            viewBox="0 0 120 60"
                            class="h-28 w-48 text-slate-300"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="3"
                            aria-hidden="true"
                        >
                            <circle cx="30" cy="30" r="17" />
                            <circle cx="90" cy="30" r="17" />
                            <path d="M47 30h26M13 22 4 16M107 22l9-6" stroke-linecap="round" />
                        </svg>
                    @endif
                </div>
            </div>

            <div>
                <p class="text-xs font-semibold uppercase tracking-widest text-slate-500">
                    {{ $product->brand?->name }}
                    @if ($product->type)
                        <span aria-hidden="true"> · </span>{{ $product->type->getLabel() }}
                    @endif
                </p>

                <h1 class="mt-2 font-display text-3xl font-bold text-navy sm:text-4xl">
                    {{ $product->displayName() }}
                </h1>

                <p class="mt-4 text-2xl font-semibold text-navy">
                    {{ \App\Support\Price::from($product->referencePrice()) }}
                </p>
                <p class="mt-1 text-sm text-slate-500">{{ $settings->currency_notice }}</p>

                @if ($product->short_description)
                    <p class="mt-5 text-base leading-relaxed text-slate-600">{{ $product->short_description }}</p>
                @endif

                @if ($product->variants->isNotEmpty())
                    <div class="mt-6">
                        <h2 class="font-display text-sm font-semibold uppercase tracking-widest text-slate-500">
                            Colores disponibles
                        </h2>
                        <ul class="mt-3 flex flex-wrap gap-2">
                            @foreach ($product->variants as $option)
                                <li class="inline-flex items-center gap-2 rounded-full border border-slate-200 bg-white px-3 py-1 text-sm text-slate-700">
                                    @if ($option->color_hex)
                                        <span
                                            aria-hidden="true"
                                            class="h-4 w-4 rounded-full border border-slate-300"
                                            style="background-color: {{ $option->color_hex }}"
                                        ></span>
                                    @endif
                                    {{ $option->color_name }}
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <dl class="mt-6 divide-y divide-slate-100 rounded-2xl border border-slate-200">
                    <div class="flex justify-between gap-4 px-4 py-3 text-sm">
                        <dt class="text-slate-500">Marca</dt>
                        <dd class="font-medium text-navy">{{ $product->brand?->name }}</dd>
                    </div>
                    <div class="flex justify-between gap-4 px-4 py-3 text-sm">
                        <dt class="text-slate-500">Modelo</dt>
                        <dd class="font-medium text-navy">{{ $product->model }}</dd>
                    </div>

                    @if ($product->material)
                        <div class="flex justify-between gap-4 px-4 py-3 text-sm">
                            <dt class="text-slate-500">Material</dt>
                            <dd class="font-medium text-navy">{{ $product->material->name }}</dd>
                        </div>
                    @endif

                    @if ($product->shape)
                        <div class="flex justify-between gap-4 px-4 py-3 text-sm">
                            <dt class="text-slate-500">Forma</dt>
                            <dd class="font-medium text-navy">{{ $product->shape->name }}</dd>
                        </div>
                    @endif

                    @if ($product->gender)
                        <div class="flex justify-between gap-4 px-4 py-3 text-sm">
                            <dt class="text-slate-500">Género</dt>
                            <dd class="font-medium text-navy">{{ $product->gender->getLabel() }}</dd>
                        </div>
                    @endif

                    @if ($product->measurementsLabel())
                        <div class="flex justify-between gap-4 px-4 py-3 text-sm">
                            <dt class="text-slate-500">Medidas (lente-puente-varilla)</dt>
                            <dd class="font-medium text-navy">{{ $product->measurementsLabel() }}</dd>
                        </div>
                    @endif
                </dl>

                @if ($settings->whatsapp_number)
                    <a
                        href="{{ \App\Support\WhatsApp::productLink($product, $variant) }}"
                        class="mt-6 inline-flex w-full items-center justify-center rounded-xl bg-brand px-6 py-3 font-semibold text-white transition hover:bg-brand-dark focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-brand sm:w-auto"
                        rel="noopener"
                        target="_blank"
                    >
                        Consultar por WhatsApp
                    </a>
                @endif

                <p class="mt-4 text-sm text-slate-500">
                    La compra se concreta en la tienda física o por WhatsApp.
                </p>
            </div>
        </div>

        @if ($product->description)
            <section class="mt-12 max-w-3xl" aria-labelledby="descripcion">
                <h2 id="descripcion" class="font-display text-xl font-bold text-navy">Descripción</h2>
                <p class="mt-3 whitespace-pre-line text-base leading-relaxed text-slate-600">{{ $product->description }}</p>
            </section>
        @endif

        <p class="mt-10">
            <a href="{{ route('catalog.index') }}" class="text-sm font-semibold text-brand underline-offset-4 hover:underline">
                &larr; Volver al catálogo
            </a>
        </p>
    </div>
</x-layout>
