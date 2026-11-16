@props(['product'])

@php
    $url = route('products.show', $product);
    $cover = $product->coverUrl();
    $alt = 'Montura '.$product->displayName();
@endphp

<article class="group relative flex flex-col overflow-hidden rounded-2xl border border-slate-200 bg-white transition hover:-translate-y-0.5 hover:border-brand/40 hover:shadow-lg hover:shadow-brand/5">
    <div class="grid aspect-[4/3] place-items-center bg-slate-50">
        @if ($cover)
            <img
                src="{{ $cover }}"
                alt="{{ $alt }}"
                loading="lazy"
                decoding="async"
                width="480"
                height="360"
                class="h-full w-full object-contain p-4"
            >
        @else
            <svg
                viewBox="0 0 120 60"
                class="h-20 w-36 text-slate-300"
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

    <div class="flex flex-1 flex-col gap-2 p-5">
        <p class="text-xs font-semibold uppercase tracking-widest text-slate-500">
            {{ $product->brand?->name }}
        </p>

        <h3 class="font-display text-lg font-semibold text-navy">
            <a
                href="{{ $url }}"
                class="after:absolute after:inset-0 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-brand"
            >
                {{ $product->model }}
            </a>
        </h3>

        @if ($product->short_description)
            <p class="text-sm leading-relaxed text-slate-600">{{ $product->short_description }}</p>
        @endif

        <div class="mt-auto flex items-baseline justify-between gap-3 pt-3">
            <span class="font-semibold text-navy">
                {{ \App\Support\Price::from($product->referencePrice()) }}
            </span>

            @if ($product->type)
                <span class="rounded-full bg-brand-soft px-2.5 py-1 text-xs font-medium text-brand-dark">
                    {{ $product->type->getLabel() }}
                </span>
            @endif
        </div>
    </div>
</article>
