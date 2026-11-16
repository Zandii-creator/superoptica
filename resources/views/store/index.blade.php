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
            <span aria-current="page" class="text-slate-700">Tienda</span>
        </nav>

        <header class="mt-4 max-w-3xl">
            <h1 class="font-display text-3xl font-bold text-navy sm:text-4xl">Visítanos en Ciego de Ávila</h1>
            <p class="mt-3 text-base leading-relaxed text-slate-600">
                Estamos en el centro de la ciudad. Puedes venir a probarte las monturas, consultar por un
                servicio o escribirnos antes de pasar.
            </p>
        </header>

        <div class="mt-10 grid gap-8 lg:grid-cols-[1fr_1fr]">
            <section aria-labelledby="direccion" class="rounded-3xl border border-slate-200 bg-white p-6 sm:p-8">
                <h2 id="direccion" class="font-display text-xl font-semibold text-navy">Dirección</h2>

                <address class="mt-3 not-italic text-base leading-relaxed text-slate-700">
                    @if ($settings->address_street)
                        <span class="block font-medium text-navy">{{ $settings->address_street }}</span>
                    @endif
                    @if ($settings->address_between)
                        <span class="block">entre {{ $settings->address_between }}</span>
                    @endif
                    @if ($settings->city || $settings->province || $settings->country)
                        <span class="block">
                            {{ collect([$settings->city, $settings->province, $settings->country])->filter()->unique()->implode(', ') }}
                        </span>
                    @endif
                </address>

                <div
                    role="img"
                    aria-label="Referencia de ubicación de la tienda"
                    class="relative mt-6 overflow-hidden rounded-2xl border border-slate-200 bg-brand-soft/60"
                >
                    <div class="absolute inset-0" aria-hidden="true">
                        <div class="h-full w-full bg-[linear-gradient(to_right,rgba(11,37,69,0.08)_1px,transparent_1px),linear-gradient(to_bottom,rgba(11,37,69,0.08)_1px,transparent_1px)] bg-[size:28px_28px]"></div>
                    </div>

                    <div class="relative flex min-h-44 flex-col items-center justify-center gap-2 p-6 text-center">
                        <svg
                            class="h-9 w-9 text-brand"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="1.6"
                            aria-hidden="true"
                        >
                            <path d="M12 21s7-6.1 7-11a7 7 0 1 0-14 0c0 4.9 7 11 7 11Z" stroke-linecap="round" stroke-linejoin="round" />
                            <circle cx="12" cy="10" r="2.6" />
                        </svg>
                        <p class="font-display text-sm font-semibold text-navy">
                            {{ $settings->city ?: 'Ciego de Ávila' }}
                        </p>
                        @if ($settings->address_street)
                            <p class="text-xs text-slate-600">{{ $settings->address_street }}</p>
                        @endif
                    </div>
                </div>

                @if ($settings->map_url)
                    <a
                        href="{{ $settings->map_url }}"
                        class="mt-5 inline-flex items-center justify-center rounded-xl bg-brand px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-brand-dark focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-brand"
                        rel="noopener"
                        target="_blank"
                    >
                        Abrir en el mapa
                    </a>
                @endif
            </section>

            <section aria-labelledby="horarios" class="rounded-3xl border border-slate-200 bg-white p-6 sm:p-8">
                <h2 id="horarios" class="font-display text-xl font-semibold text-navy">Horario de atención</h2>

                <dl class="mt-4 divide-y divide-slate-100">
                    @foreach ($settings->weeklyHours() as $day)
                        <div class="flex items-center justify-between gap-4 py-2.5 text-sm">
                            <dt class="text-slate-600">{{ $day['day'] }}</dt>
                            <dd class="{{ $day['hours'] ? 'font-medium text-navy' : 'text-slate-400' }}">
                                {{ $day['hours'] ?? 'Cerrado' }}
                            </dd>
                        </div>
                    @endforeach
                </dl>

                <p class="mt-4 text-xs leading-relaxed text-slate-500">
                    Los horarios pueden variar en días festivos. Si vienes desde lejos, escríbenos antes.
                </p>
            </section>
        </div>

        <section aria-labelledby="contacto" class="mt-10 rounded-3xl border border-slate-200 bg-slate-50 p-6 sm:p-8">
            <h2 id="contacto" class="font-display text-xl font-semibold text-navy">Contacto</h2>
            <p class="mt-2 max-w-2xl text-sm leading-relaxed text-slate-600">
                Elige el canal que prefieras. Respondemos en horario de tienda.
            </p>

            <ul class="mt-5 grid gap-3 sm:grid-cols-2 lg:grid-cols-3">
                @if ($settings->whatsapp_number)
                    <li>
                        <a
                            href="{{ \App\Support\WhatsApp::link('Hola, quiero información sobre los espejuelos.') }}"
                            class="flex items-center gap-3 rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm font-medium text-navy transition hover:border-brand hover:text-brand"
                            rel="noopener"
                            target="_blank"
                        >
                            <span class="grid h-9 w-9 shrink-0 place-items-center rounded-lg bg-brand-soft text-brand" aria-hidden="true">
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                    <path d="M12.04 2.5a9.42 9.42 0 0 0-8.06 14.3L2.5 21.5l4.83-1.44a9.42 9.42 0 1 0 4.71-17.56Zm0 1.8a7.62 7.62 0 0 1 0 15.24 7.6 7.6 0 0 1-3.87-1.06l-.35-.2-2.87.85.86-2.8-.23-.36a7.62 7.62 0 0 1 6.46-11.68Zm-2.9 3.6c-.16 0-.42.06-.64.3-.22.24-.84.82-.84 2 0 1.18.86 2.32.98 2.48.12.16 1.68 2.66 4.12 3.62 2.02.8 2.44.64 2.88.6.44-.04 1.42-.58 1.62-1.14.2-.56.2-1.04.14-1.14-.06-.1-.22-.16-.46-.28-.24-.12-1.42-.7-1.64-.78-.22-.08-.38-.12-.54.12-.16.24-.62.78-.76.94-.14.16-.28.18-.52.06-.24-.12-1.02-.38-1.94-1.2-.72-.64-1.2-1.42-1.34-1.66-.14-.24-.02-.38.1-.5.1-.1.24-.28.36-.42.12-.14.16-.24.24-.4.08-.16.04-.3-.02-.42-.06-.12-.54-1.3-.74-1.78-.2-.46-.4-.4-.54-.4-.14-.01-.3-.01-.46-.01Z" />
                                </svg>
                            </span>
                            WhatsApp
                        </a>
                    </li>
                @endif

                @if ($settings->phoneUrl())
                    <li>
                        <a
                            href="{{ $settings->phoneUrl() }}"
                            class="flex items-center gap-3 rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm font-medium text-navy transition hover:border-brand hover:text-brand"
                        >
                            <span class="grid h-9 w-9 shrink-0 place-items-center rounded-lg bg-brand-soft text-brand" aria-hidden="true">
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" aria-hidden="true">
                                    <path d="M6.6 3.5h3l1.5 3.8-1.9 1.4a12.3 12.3 0 0 0 6.1 6.1l1.4-1.9 3.8 1.5v3a2.1 2.1 0 0 1-2.3 2.1A16.8 16.8 0 0 1 4.5 5.8 2.1 2.1 0 0 1 6.6 3.5Z" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </span>
                            {{ $settings->phone_number }}
                        </a>
                    </li>
                @endif

                @if ($settings->telegramUrl())
                    <li>
                        <a
                            href="{{ $settings->telegramUrl() }}"
                            class="flex items-center gap-3 rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm font-medium text-navy transition hover:border-brand hover:text-brand"
                            rel="noopener"
                            target="_blank"
                        >
                            <span class="grid h-9 w-9 shrink-0 place-items-center rounded-lg bg-brand-soft text-brand" aria-hidden="true">
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                    <path d="M21.3 4.3 18.4 19c-.2 1-.8 1.2-1.6.8l-4.4-3.3-2.1 2c-.2.2-.4.4-.9.4l.3-4.5 8.2-7.4c.4-.3-.1-.5-.6-.2L7.2 13.3l-4.3-1.4c-.9-.3-1-.9.2-1.3L20 3.9c.8-.3 1.5.2 1.3.4Z" />
                                </svg>
                            </span>
                            Telegram
                        </a>
                    </li>
                @endif

                @foreach ($settings->socialLinks() as $link)
                    <li>
                        <a
                            href="{{ $link['url'] }}"
                            class="flex items-center gap-3 rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm font-medium text-navy transition hover:border-brand hover:text-brand"
                            rel="noopener"
                            target="_blank"
                        >
                            <span class="grid h-9 w-9 shrink-0 place-items-center rounded-lg bg-brand-soft text-brand" aria-hidden="true">
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" aria-hidden="true">
                                    <circle cx="12" cy="12" r="8.5" />
                                    <path d="M3.5 9.5h17M3.5 14.5h17M12 3.5c2.2 2.4 3.2 5.3 3.2 8.5s-1 6.1-3.2 8.5c-2.2-2.4-3.2-5.3-3.2-8.5s1-6.1 3.2-8.5Z" stroke-linecap="round" />
                                </svg>
                            </span>
                            {{ $link['label'] }}
                        </a>
                    </li>
                @endforeach
            </ul>

            <p class="mt-5 text-xs text-slate-500">{{ $settings->currency_notice }}</p>
        </section>
    </div>
</x-layout>
