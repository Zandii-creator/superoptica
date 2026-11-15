# Proposal

## Why

SuperÓptica 20 20, óptica ubicada en Ciego de Ávila, Cuba, no cuenta con presencia web propia. Sus clientes potenciales buscan espejuelos y servicios ópticos en línea, pero hoy no encuentran un catálogo confiable ni una forma directa de contactar al negocio.

Se necesita una vitrina digital que muestre la marca y los productos, genere confianza mostrando la tienda física y convierta el interés en conversaciones por WhatsApp. El objetivo es promoción y captación de clientes, no la venta en línea.

## What Changes

- Se crea un sitio público en Laravel 12 + Livewire 3 + Tailwind 4, mobile-first y optimizado para conexiones lentas.
- **Home**: hero con sello "20 20" y CTA dual ("Ver catálogo" y "Consultar por WhatsApp"), productos destacados, bloque de tienda física y footer.
- **Catálogo**: cuadrícula de productos con filtros por marca, tipo, género, material y forma, con búsqueda y paginación indexable.
- **Ficha de producto**: galería, variantes de color, medidas, material y precio referencial con formato "Desde 1,500 CUP".
- **Servicios**: examen visual, ajuste y graduación, mostrados sin precio y con CTA de WhatsApp.
- **Tienda y contacto**: dirección, horarios, mapa, teléfono fijo, Telegram y WhatsApp con botón flotante global.
- **Panel de administración** (Filament): gestión de productos, marcas, variantes, imágenes y servicios, más un módulo de ajustes del sitio editable sin tocar código.
- Textos en español cubano (espejuelos, monturas, lentes de contacto) y precios siempre identificados como CUP.
- **Restricciones de operación**: la aplicación opera únicamente sobre la base de datos `espejuelos`; cualquier cambio en cPanel fuera de esa base se documenta para ejecución manual del usuario.
- **Calidad**: cada tarea de desarrollo se implementa con enfoque de pruebas (TDD) y la suite debe pasar al 100% antes de cerrar el cambio.
- **Fuera de alcance**: cuentas de cliente, carrito, checkout, pasarelas de pago, pedidos, inventario, rol de vendedor y solicitud de citas en línea.

## Capabilities

### New Capabilities

- `product-catalog`: catálogo público de productos con marcas, tipos, variantes, filtros, búsqueda y ficha de producto con precio referencial en CUP.
- `services`: presentación de los servicios ópticos sin precio, con descripción y llamada a la acción por WhatsApp.
- `store-contact`: información de la tienda física, horarios, mapa y canales de contacto (WhatsApp, teléfono, Telegram) con conversión directa.
- `site-settings`: configuración editable del sitio desde el panel, incluyendo identidad, ubicación, horarios, contactos, moneda y textos de la home.
- `brand-experience`: identidad visual y de contenido del sitio (paleta blanco y azul, sello "20 20", tono cubano, accesibilidad y rendimiento).
- `platform-operations`: restricciones de operación y despliegue en cPanel, incluido el uso exclusivo de la base de datos `espejuelos` y la ejecución manual de cambios fuera de ella.

### Modified Capabilities

<!-- Ninguna: el proyecto no tiene capacidades existentes. -->

## Impact

- **Código**: proyecto Laravel nuevo desde cero; rutas públicas, componentes Livewire, vistas Blade y panel Filament.
- **Dependencias**: Laravel 12, Livewire 3, Tailwind 4, Filament, Spatie Media Library, Intervention Image.
- **Datos**: base de datos MySQL en cPanel con tablas de marcas, productos, variantes, imágenes, servicios y ajustes.
- **Almacenamiento**: imágenes de producto en disco local, con conversiones a WebP y miniaturas generadas al subir.
- **Infraestructura**: cPanel 138 sin Redis ni Supervisor; caché y sesiones en archivos; assets compilados en local y desplegados manualmente por el usuario.
- **Base de datos**: una sola base, `espejuelos`; sin conexiones ni migraciones sobre otras bases del servidor.
- **Operación**: cualquier acción en cPanel fuera de la base `espejuelos` se entrega como instrucción escrita para ejecución manual.
- **Proceso**: desarrollo guiado por pruebas; la suite completa y su documentación son requisito para cerrar el cambio.
- **SEO**: URLs limpias, metadatos por página, datos estructurados de producto y sitemap.
- **Externo**: enlaces `wa.me` con mensaje precargado, enlaces `tel:` y `t.me`; mapa estático con enlace externo.
- **Contenido pendiente del negocio**: número de WhatsApp, teléfono fijo, usuario de Telegram, textos de "Quiénes somos" y fotografías de producto.
