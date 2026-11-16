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
                <livewire:product-gallery :product="$product" />
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

                <livewire:product-variant-selector :product="$product" />

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
