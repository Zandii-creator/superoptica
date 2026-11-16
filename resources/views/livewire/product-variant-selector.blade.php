@php
    $whatsappUrl = $this->whatsappUrl();
@endphp

<div>
    @if ($product->variants->isNotEmpty())
        <div class="mt-6">
            <h2 class="font-display text-sm font-semibold uppercase tracking-widest text-slate-500">
                Colores disponibles
            </h2>

            <ul class="mt-3 flex flex-wrap gap-2">
                @foreach ($product->variants as $option)
                    <li>
                        <button
                            type="button"
                            wire:click="selectVariant({{ $option->id }})"
                            aria-pressed="{{ $selectedVariantId === $option->id ? 'true' : 'false' }}"
                            class="inline-flex items-center gap-2 rounded-full border px-3 py-1.5 text-sm transition {{ $selectedVariantId === $option->id ? 'border-brand bg-brand-soft font-medium text-brand-dark' : 'border-slate-200 bg-white text-slate-700 hover:border-brand/50' }}"
                        >
                            @if ($option->color_hex)
                                <span
                                    aria-hidden="true"
                                    class="h-4 w-4 rounded-full border border-slate-300"
                                    style="background-color: {{ $option->color_hex }}"
                                ></span>
                            @endif
                            {{ $option->color_name }}
                        </button>
                    </li>
                @endforeach
            </ul>
        </div>
    @endif

    @if ($whatsappUrl)
        <a
            href="{{ $whatsappUrl }}"
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
