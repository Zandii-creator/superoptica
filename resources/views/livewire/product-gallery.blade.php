@php
    $images = $this->images();
    $main = $this->selectedImage();
@endphp

<div>
    <div class="grid aspect-square place-items-center overflow-hidden rounded-2xl border border-slate-200 bg-slate-50">
        @if ($main)
            <img
                src="{{ $main->hasGeneratedConversion('detail') ? $main->getUrl('detail') : $main->getUrl() }}"
                alt="Montura {{ $product->displayName() }}"
                width="960"
                height="960"
                decoding="async"
                fetchpriority="high"
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

    @if ($images->count() > 1)
        <ul class="mt-3 grid grid-cols-4 gap-2" aria-label="Galería del producto">
            @foreach ($images as $image)
                <li>
                    <button
                        type="button"
                        wire:click="selectMedia({{ $image->id }})"
                        aria-pressed="{{ $main?->id === $image->id ? 'true' : 'false' }}"
                        aria-label="Ver imagen {{ $loop->iteration }} de {{ $product->displayName() }}"
                        class="block w-full overflow-hidden rounded-xl border-2 bg-slate-50 p-1 transition {{ $main?->id === $image->id ? 'border-brand' : 'border-transparent hover:border-slate-300' }}"
                    >
                        <img
                            src="{{ $image->hasGeneratedConversion('thumb') ? $image->getUrl('thumb') : $image->getUrl() }}"
                            alt=""
                            width="160"
                            height="120"
                            loading="lazy"
                            decoding="async"
                            class="h-20 w-full object-contain"
                        >
                    </button>
                </li>
            @endforeach
        </ul>
    @endif
</div>
