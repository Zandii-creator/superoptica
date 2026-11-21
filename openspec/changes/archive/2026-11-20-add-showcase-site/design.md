# Design

## Context

Proyecto nuevo, sin código previo. Ver `proposal.md` para la motivación.

Restricciones que condicionan el diseño:

- Hosting en **cPanel 138**: sin Redis, sin Supervisor, sin websockets y con posibilidad de que `storage:link` esté deshabilitado.
- Público en **Ciego de Ávila, Cuba**: conexiones móviles costosas y variables; el sitio debe ser ligero y mobile-first.
- **Sin logotipo definitivo**: el sello «20 20» cubre la identidad hasta que exista uno.
- **Sin e-commerce**: no hay cuentas de cliente, carrito, pagos ni inventario.
- **Catálogo cargado a mano**: la ergonomía del panel es parte del éxito del proyecto.
- **Base de datos única**: solo se permite operar sobre `espejuelos`; ninguna otra base del servidor puede tocarse.
- **cPanel sin automatización**: todo cambio fuera de la base `espejuelos` lo ejecuta el usuario manualmente; el agente solo documenta.

## Goals / Non-Goals

**Goals:**

- Monolito Laravel desplegable en cPanel sin procesos en segundo plano.
- Catálogo indexable por buscadores, con filtros compartibles por URL.
- Panel que permita cargar un producto completo en menos de dos minutos.
- Peso de página bajo y buena experiencia en 3G.
- Todo el contenido editable sin tocar código.
- Cumplir las restricciones de operación: una sola base de datos y cero automatización sobre cPanel.
- Implementación guiada por pruebas, con cierre solo al 100% de la suite.

**Non-Goals:**

- Multi-idioma, multi-tienda o multi-moneda.
- PWA, modo offline o notificaciones.
- Importación masiva de catálogo desde archivos externos (queda como evolución futura).
- Analítica avanzada o personalización por visitante.

## Decisions

### 1. Monolito Laravel con Blade y Livewire por islas

Blade renderiza el HTML y Livewire se usa solo donde hay interacción real: búsqueda instantánea, galería y selector de color. El resto es HTML del servidor.

- **Por qué**: SEO nativo, menos JavaScript, despliegue simple en cPanel.
- **Alternativas**: SPA con Inertia/React o Next.js. Descartadas por peso, complejidad de despliegue y necesidad de indexación.
- **Consecuencia**: filtros y paginación viven en la URL y se resuelven en el servidor; no se depende de Livewire para el contenido indexable.

### 2. Filament como panel de administración

CRUD de productos, marcas, variantes, servicios y ajustes sobre Livewire.

- **Por qué**: reduce semanas de trabajo y ya resuelve relaciones, validación, subida y ordenación de imágenes.
- **Alternativas**: CRUD propio con Livewire (más control, mucho más coste) o un CMS externo (rompe el stack y añade integración).
- **Consecuencia**: el panel usa su propio diseño; la identidad visual se aplica solo al sitio público.

### 3. Modelo de datos

```
brands            id, name, slug, logo, is_active
products          id, brand_id, model, slug, type, gender,
                  material_id, shape_id, lens_width, bridge_width,
                  temple_length, price_from, short_description,
                  description, is_featured, is_active, published_at
variants          id, product_id, color_name, color_hex, price, sort
media             gestionada por Spatie Media Library (producto y variante)
services          id, name, slug, description, icon, sort, is_active
site_settings     fila unica con columnas tipadas
users             solo administradores
```

- **Precio como entero en CUP**, sin decimales. Un helper central formatea `1500` como `1,500 CUP` y añade «Desde».
- **Marcas, materiales y formas como tablas**, no como texto libre, para que los filtros no se rompan por variaciones de escritura. Tipo y género quedan como enums por ser listas estables.
- **Medidas** como tres enteros opcionales; si faltan, el bloque se oculta.
- **Alternativa descartada**: una sola tabla con atributos JSON. Más flexible, pero imposible de filtrar y validar con fiabilidad.

### 4. Imágenes: conversión al subir

Spatie Media Library + Intervention. Al subir se generan versiones WebP (tarjeta, ficha y miniatura) y se guardan dimensiones y `alt`.

- **Por qué**: el visitante no paga el coste de redimensionar; las imágenes viajan ya optimizadas.
- **Alternativa descartada**: conversión al vuelo. En cPanel sin caché de imágenes, castiga cada visita.
- **Almacenamiento**: disco `public` con `storage:link`; si el hosting lo bloquea, un disco alternativo con raíz en `public/media` y el mismo helper de URLs.

### 5. Ajustes como fila única tipada

Una sola fila en `site_settings` con columnas tipadas y validación en Filament; se cachea y se invalida al guardar.

- **Por qué**: validación fuerte, sin claves huérfanas ni tipos inconsistentes.
- **Alternativa descartada**: tabla clave-valor. Cómoda de extender, pero sin validación ni autocompletado en el panel.

### 6. Enlaces de WhatsApp centralizados

Un helper construye `https://wa.me/<numero>?text=<mensaje>` con el contexto codificado (modelo, color o servicio). El número sale de los ajustes.

- **Por qué**: un solo lugar para cambiar el número y para garantizar mensajes bien codificados.
- **Alternativa descartada**: enlaces escritos en cada vista. Duplicación y errores de codificación.

### 7. SEO con render del servidor

Título y descripción por página, `canonical`, `sitemap.xml`, datos estructurados `Product` y `LocalBusiness`, y URLs limpias con filtros en query string.

- **Por qué**: la promoción de marca depende de aparecer en búsquedas locales.
- **Consecuencia**: la búsqueda instantánea de Livewire es una mejora progresiva; sin JavaScript, el formulario sigue funcionando por URL.

### 8. Despliegue en cPanel

- `document root` apuntando a `public/`.
- Assets compilados en local (`npm run build`) y subidos; el servidor no ejecuta Node.
- `php artisan config:cache`, `route:cache` y `view:cache` en cada despliegue.
- Sin colas ni workers: `SESSION_DRIVER=file` y `CACHE_STORE=file`.
- Una entrada de cron para `php artisan schedule:run` (aunque al inicio no haya tareas programadas).
- Copia de la release anterior para revertir.

### 9. Operación restringida y desarrollo guiado por pruebas

La aplicación usa una única conexión a la base `espejuelos` y no define conexiones adicionales. Los cambios en cPanel —dominio, document root, cron, correo, SSL, versión de PHP y archivos del servidor— se entregan como instrucciones escritas para el usuario; no se ejecutan desde el agente ni desde scripts del proyecto.

Cada tarea empieza por una prueba que falla y termina cuando esa prueba y la suite completa pasan. Los resultados se documentan antes de cerrar el cambio.

- **Por qué**: evita daños irreversibles en otras bases del servidor y mantiene un historial verificable de la calidad.
- **Alternativas descartadas**: automatizar el despliegue con scripts (prohibido por la operación) y conectar a bases auxiliares para pruebas (viola el aislamiento exigido).

## Risks / Trade-offs

- **Carga manual del catálogo** → panel con duplicar producto, alta rápida de marca/material, subida múltiple y borradores; importación CSV como evolución.
- **Peso de imágenes en conexión lenta** → conversiones WebP, `loading="lazy"`, dimensiones explícitas y límite de peso por imagen.
- **`storage:link` bloqueado en cPanel** → disco alternativo en `public/media` y helper único de URLs.
- **Mapas de terceros lentos o bloqueados** → mapa estático con enlace externo, sin iframe en la carga inicial.
- **Ambigüedad del peso frente al dólar** → «CUP» siempre visible y aviso de moneda nacional junto al precio.
- **Datos de contacto desactualizados** → edición desde el panel y validación de formato.
- **Dependencia de Google para SEO** → no hay alternativa realista; se mitiga con metadatos correctos y contenido local.
- **Crecimiento del catálogo** → índices en `slug`, `is_active`, `published_at` y en las claves foráneas de filtros; paginación del servidor.
- **Conexión accidental a otra base de datos** → una sola entrada de conexión, `DB_DATABASE=espejuelos`, revisión previa de comandos y prohibición explícita de conexiones auxiliares.
- **Dependencia de acciones manuales en cPanel** → instrucciones paso a paso, verificación posterior por el usuario y respaldo de la release anterior.
- **Pérdida de disciplina TDD bajo presión** → cada tarea incluye su prueba en la descripción y el cierre exige la suite completa en verde documentada.

## Migration Plan

Proyecto greenfield, sin datos que migrar.

1. Desarrollar y verificar en local con la base `espejuelos`, sin conexiones auxiliares.
2. Entregar al usuario las instrucciones para confirmar la base `espejuelos`, el dominio y el `document root` a `public/` en cPanel.
3. El usuario sube la release y ejecuta las migraciones contra `espejuelos`; el agente no ejecuta pasos en cPanel.
4. Cargar marcas, materiales, formas y los primeros productos desde el panel.
5. Revisar SEO, rendimiento y enlaces de contacto antes de publicar.
6. El usuario publica el dominio definitivo y conserva la release anterior para revertir.

## Open Questions

- Número de WhatsApp, teléfono fijo y usuario de Telegram reales.
- Dominio definitivo y acceso al cPanel.
- Textos de «Quiénes somos» y aviso de privacidad.
- Protocolo fotográfico (fondo, proporción y número mínimo de ángulos).
- Analítica: si se usará alguna y con qué nivel de privacidad.
