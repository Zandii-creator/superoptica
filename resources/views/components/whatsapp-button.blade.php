@props(['settings'])

@if ($settings->whatsapp_number)
    <a
        href="{{ \App\Support\WhatsApp::link('Hola, quiero información sobre los espejuelos.') }}"
        aria-label="Escríbenos por WhatsApp"
        class="fixed bottom-4 right-4 z-40 grid h-12 w-12 place-items-center rounded-full bg-brand text-white shadow-lg shadow-brand/30 transition hover:bg-brand-dark focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-brand sm:h-14 sm:w-14"
        rel="noopener"
        target="_blank"
    >
        <svg class="h-6 w-6 sm:h-7 sm:w-7" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
            <path d="M12.04 2.5a9.42 9.42 0 0 0-8.06 14.3L2.5 21.5l4.83-1.44a9.42 9.42 0 1 0 4.71-17.56Zm0 1.8a7.62 7.62 0 0 1 0 15.24 7.6 7.6 0 0 1-3.87-1.06l-.35-.2-2.87.85.86-2.8-.23-.36a7.62 7.62 0 0 1 6.46-11.68Zm-2.9 3.6c-.16 0-.42.06-.64.3-.22.24-.84.82-.84 2 0 1.18.86 2.32.98 2.48.12.16 1.68 2.66 4.12 3.62 2.02.8 2.44.64 2.88.6.44-.04 1.42-.58 1.62-1.14.2-.56.2-1.04.14-1.14-.06-.1-.22-.16-.46-.28-.24-.12-1.42-.7-1.64-.78-.22-.08-.38-.12-.54.12-.16.24-.62.78-.76.94-.14.16-.28.18-.52.06-.24-.12-1.02-.38-1.94-1.2-.72-.64-1.2-1.42-1.34-1.66-.14-.24-.02-.38.1-.5.1-.1.24-.28.36-.42.12-.14.16-.24.24-.4.08-.16.04-.3-.02-.42-.06-.12-.54-1.3-.74-1.78-.2-.46-.4-.4-.54-.4-.14-.01-.3-.01-.46-.01Z" />
        </svg>
    </a>
@endif
