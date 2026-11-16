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
            <span aria-current="page" class="text-slate-700">Catálogo</span>
        </nav>

        <header class="mt-4 max-w-3xl">
            <h1 class="font-display text-3xl font-bold text-navy sm:text-4xl">Catálogo</h1>
            <p class="mt-3 text-base leading-relaxed text-slate-600">
                Espejuelos, monturas, lentes de contacto y líquidos disponibles en nuestra tienda de Ciego de Ávila.
                Filtra por marca, tipo o material para encontrar el modelo que buscas.
            </p>
            <p class="mt-3 text-sm font-medium text-slate-500">{{ $settings->currency_notice }}</p>
        </header>

        <div class="mt-8 grid gap-8 lg:grid-cols-[280px_1fr]">
            <aside class="space-y-5" aria-label="Búsqueda y filtros">
                <livewire:catalog-search :active-filters="$queryFilters" />

                <form method="GET" action="{{ route('catalog.index') }}" class="rounded-2xl border border-slate-200 bg-white p-5">
                    @if ($search !== '')
                        <input type="hidden" name="q" value="{{ $search }}">
                    @endif

                    <h2 class="font-display text-sm font-semibold uppercase tracking-widest text-slate-500">
                        Filtrar
                    </h2>

                    <div class="mt-4 space-y-4">
                        <div>
                            <label for="filtro-marca" class="block text-sm font-medium text-navy">Marca</label>
                            <select
                                id="filtro-marca"
                                name="marca"
                                class="mt-1 w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm text-ink focus:border-brand focus:outline-none focus:ring-2 focus:ring-brand/30"
                            >
                                <option value="">Todas las marcas</option>
                                @foreach ($brands as $option)
                                    <option value="{{ $option->slug }}" @selected($brand?->slug === $option->slug)>
                                        {{ $option->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="filtro-tipo" class="block text-sm font-medium text-navy">Tipo</label>
                            <select
                                id="filtro-tipo"
                                name="tipo"
                                class="mt-1 w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm text-ink focus:border-brand focus:outline-none focus:ring-2 focus:ring-brand/30"
                            >
                                <option value="">Todos los tipos</option>
                                @foreach ($types as $option)
                                    <option value="{{ $option->value }}" @selected($type === $option)>
                                        {{ $option->getLabel() }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="filtro-genero" class="block text-sm font-medium text-navy">Género</label>
                            <select
                                id="filtro-genero"
                                name="genero"
                                class="mt-1 w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm text-ink focus:border-brand focus:outline-none focus:ring-2 focus:ring-brand/30"
                            >
                                <option value="">Todos</option>
                                @foreach ($genders as $option)
                                    <option value="{{ $option->value }}" @selected($gender === $option)>
                                        {{ $option->getLabel() }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="filtro-material" class="block text-sm font-medium text-navy">Material</label>
                            <select
                                id="filtro-material"
                                name="material"
                                class="mt-1 w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm text-ink focus:border-brand focus:outline-none focus:ring-2 focus:ring-brand/30"
                            >
                                <option value="">Todos los materiales</option>
                                @foreach ($materials as $option)
                                    <option value="{{ $option->slug }}" @selected($material?->slug === $option->slug)>
                                        {{ $option->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="filtro-forma" class="block text-sm font-medium text-navy">Forma</label>
                            <select
                                id="filtro-forma"
                                name="forma"
                                class="mt-1 w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm text-ink focus:border-brand focus:outline-none focus:ring-2 focus:ring-brand/30"
                            >
                                <option value="">Todas las formas</option>
                                @foreach ($shapes as $option)
                                    <option value="{{ $option->slug }}" @selected($shape?->slug === $option->slug)>
                                        {{ $option->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="mt-5 flex flex-col gap-2">
                        <button
                            type="submit"
                            class="inline-flex items-center justify-center rounded-lg bg-brand px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-brand-dark focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-brand"
                        >
                            Aplicar filtros
                        </button>

                        <a
                            href="{{ route('catalog.index') }}"
                            class="inline-flex items-center justify-center rounded-lg px-4 py-2 text-sm font-medium text-slate-600 underline-offset-4 hover:text-brand hover:underline"
                        >
                            Limpiar filtros
                        </a>
                    </div>
                </form>
            </aside>

            <section aria-live="polite">
                @if ($activeFilters !== [])
                    <div class="flex flex-wrap items-center gap-2">
                        <span class="text-sm font-medium text-slate-500">Filtros activos:</span>

                        @foreach ($activeFilters as $filter)
                            <a
                                href="{{ $filter['remove_url'] }}"
                                class="inline-flex items-center gap-2 rounded-full border border-brand/20 bg-brand-soft px-3 py-1 text-xs font-medium text-brand-dark transition hover:border-brand"
                                aria-label="Quitar filtro {{ $filter['label'] }}"
                            >
                                {{ $filter['label'] }}
                                <span aria-hidden="true">&times;</span>
                            </a>
                        @endforeach
                    </div>
                @endif

                <p class="mt-4 text-sm text-slate-500">
                    {{ $products->total() }} {{ $products->total() === 1 ? 'modelo' : 'modelos' }}
                </p>

                @if ($products->isEmpty())
                    <div class="mt-6 rounded-2xl border border-dashed border-slate-300 bg-slate-50 p-8 text-center">
                        @if ($activeFilters !== [])
                            <h2 class="font-display text-xl font-semibold text-navy">
                                No encontramos espejuelos con esos filtros
                            </h2>
                            <p class="mx-auto mt-2 max-w-md text-sm text-slate-600">
                                Prueba con menos filtros o con otra búsqueda. También puedes escribirnos y te
                                contamos qué modelos tenemos disponibles.
                            </p>
                        @else
                            <h2 class="font-display text-xl font-semibold text-navy">
                                Todavía no hay productos publicados
                            </h2>
                            <p class="mx-auto mt-2 max-w-md text-sm text-slate-600">
                                Estamos preparando el catálogo. Escríbenos y te contamos qué modelos tenemos
                                disponibles en la tienda.
                            </p>
                        @endif

                        <div class="mt-5 flex flex-wrap justify-center gap-3">
                            @if ($activeFilters !== [])
                                <a
                                    href="{{ route('catalog.index') }}"
                                    class="inline-flex items-center justify-center rounded-xl bg-brand px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-brand-dark"
                                >
                                    Limpiar filtros
                                </a>
                            @endif

                            @if ($settings->whatsapp_number)
                                <a
                                    href="{{ \App\Support\WhatsApp::link('Hola, quiero información sobre los espejuelos disponibles.') }}"
                                    class="inline-flex items-center justify-center rounded-xl border border-navy/15 bg-white px-5 py-2.5 text-sm font-semibold text-navy transition hover:border-brand hover:text-brand"
                                    rel="noopener"
                                    target="_blank"
                                >
                                    Consultar por WhatsApp
                                </a>
                            @endif
                        </div>
                    </div>
                @else
                    <div class="mt-6 grid gap-6 sm:grid-cols-2 xl:grid-cols-3">
                        @foreach ($products as $product)
                            <x-product-card :product="$product" />
                        @endforeach
                    </div>

                    <div class="mt-10">
                        {{ $products->onEachSide(1)->links() }}
                    </div>

                    <p class="mt-6 text-sm text-slate-500">{{ $settings->currency_notice }}</p>
                @endif
            </section>
        </div>
    </div>
</x-layout>
