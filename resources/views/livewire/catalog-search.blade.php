<div>
    <form
        method="GET"
        action="{{ route('catalog.index') }}"
        role="search"
        wire:submit="search"
    >
        @foreach ($activeFilters as $name => $value)
            <input type="hidden" name="{{ $name }}" value="{{ $value }}">
        @endforeach

        <label for="catalog-search" class="sr-only">Buscar por marca o modelo</label>

        <div class="flex flex-col gap-2 sm:flex-row">
            <input
                id="catalog-search"
                name="q"
                type="search"
                value="{{ $q }}"
                wire:model.live.debounce.500ms="q"
                placeholder="Marca o modelo. Ejemplo: 82278"
                autocomplete="off"
                class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-base text-ink placeholder:text-slate-400 focus:border-brand focus:outline-none focus:ring-2 focus:ring-brand/30"
            >

            <button
                type="submit"
                class="inline-flex items-center justify-center rounded-xl bg-brand px-5 py-3 font-semibold text-white transition hover:bg-brand-dark focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-brand"
            >
                Buscar
            </button>
        </div>
    </form>
</div>
