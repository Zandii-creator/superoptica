# SuperÓptica 20 20

Sitio vitrina de la óptica **SuperÓptica 20 20** (Ciego de Ávila, Cuba). Muestra la marca, el catálogo de monturas y los servicios; la venta se cierra en la tienda física o por WhatsApp. No hay cuentas, carrito, pagos ni inventario.

## Stack

- Laravel 12 (PHP 8.3), Livewire 3 y Blade.
- Tailwind CSS 4 compilado con Vite.
- Panel de administración con Filament 4.
- Imágenes con Spatie Media Library e Intervention Image.
- SQLite en local (`database/espejuelos.sqlite`) y MySQL en producción (base `espejuelos`).

## Puesta en marcha

```powershell
$php = "$env:LOCALAPPDATA\php\8.3\php.exe"
$composer = "$env:LOCALAPPDATA\composer\composer.phar"

& $php $composer install
npm install
& $php artisan migrate:fresh --seed
npm run build
& $php -S 127.0.0.1:8000 -t public server.php
```

`php artisan serve` no funciona en este equipo; usa el servidor embebido de PHP como arriba.

## Rutas públicas

| Ruta | Descripción |
|---|---|
| `GET /` | Página de inicio: portada, modelos destacados, servicios y tienda. |
| `GET /catalogo` | Catálogo paginado de productos publicados y activos. |
| `GET /catalogo/{slug}` | Ficha del producto. Devuelve 404 si está en borrador o inactivo. |
| `GET /servicios` | Servicios activos con su descripción y contacto por WhatsApp. |
| `GET /tienda` | Dirección, horarios, mapa externo y canales de contacto. |
| `GET /sitemap.xml` | Mapa del sitio para buscadores. |

### Parámetros del catálogo

Todos los filtros viven en la URL, se combinan entre sí y se conservan al paginar. Los valores desconocidos se ignoran.

| Parámetro | Valores | Ejemplo |
|---|---|---|
| `marca` | Slug de la marca | `/catalogo?marca=ray-ban` |
| `tipo` | `monturas`, `espejuelos`, `lentes-de-contacto`, `liquidos` | `/catalogo?tipo=monturas` |
| `genero` | `unisex`, `femenino`, `masculino`, `infantil` | `/catalogo?genero=femenino` |
| `material` | Slug del material | `/catalogo?material=acetato` |
| `forma` | Slug de la forma | `/catalogo?forma=rectangular` |
| `q` | Texto libre: marca o modelo | `/catalogo?q=82278` |
| `page` | Número de página (12 productos por página) | `/catalogo?marca=ray-ban&page=2` |

Ejemplo combinado:

```
/catalogo?marca=ray-ban&tipo=monturas&genero=unisex&material=acetato&forma=rectangular
```

La búsqueda funciona sin JavaScript: el formulario es un `GET` normal. Con JavaScript, Livewire actualiza la misma URL mientras se escribe (mejora progresiva).

## Panel de administración

- `/admin` — acceso con el usuario administrador.
- `/admin/ajustes` — identidad, dirección, horarios, contactos, redes, moneda, textos de portada y SEO.
- Catálogo: marcas, productos (con variantes de color e imágenes) y servicios.

El usuario inicial se crea con:

```powershell
& $php artisan db:seed --class=AdminUserSeeder
```

En producción, `AdminUserSeeder` exige `ADMIN_PASSWORD`.

## Pruebas y estilo

```powershell
& $php artisan test
& $php vendor/bin/pint --test
```

La suite cubre visibilidad de productos, filtros por URL, búsqueda sin JavaScript, estados vacíos, formato de precios en CUP, enlaces de WhatsApp, SEO, sitemap y el panel.

## Operación

- Solo se opera sobre la base `espejuelos`; no hay conexiones auxiliares.
- Ningún cambio en cPanel se ejecuta desde el proyecto: se documenta para que lo aplique el usuario.
- Los assets compilados (`public/build`) y los publicados de Filament se versionan porque el servidor no ejecuta Node.
