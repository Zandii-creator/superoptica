# SuperÓptica 20 20

Sitio vitrina de la óptica **SuperÓptica 20 20** (Ciego de Ávila, Cuba). Muestra la marca, el catálogo de monturas y los servicios; la venta se cierra en la tienda física o por WhatsApp. No hay cuentas, carrito, pagos ni inventario.

## Documentación

- [Manual de usuario](docs/manual-usuario.md) — uso del sitio y administración del contenido.
- [Manual de operación](docs/manual-operacion.md) — rutinas, respaldos, actualizaciones e incidencias.
- [Índice de documentación](docs/README.md) — qué leer y en qué orden.
- [Guía de despliegue](docs/despliegue.md) — comandos y procedimiento en cPanel.
- [Verificación](docs/verificacion.md) — resultados de la última publicación.

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

La suite cubre visibilidad de productos, filtros por URL, búsqueda sin JavaScript, estados vacíos, formato de precios en CUP, enlaces de WhatsApp, SEO, sitemap, accesibilidad y el panel.

## Despliegue

La guía completa está en [`docs/despliegue.md`](docs/despliegue.md) y los resultados de la última verificación en [`docs/verificacion.md`](docs/verificacion.md).

El hosting es compartido y **no tiene SSH ni Terminal**. El sitio está publicado en
`https://dtaamerica.com/superoptica` (cPanel `dtaameri`), con la aplicación fuera de la web:

| Concepto | Ruta |
|---|---|
| Aplicación | `/home/dtaameri/superoptica-app` |
| Carpeta publicada | `/home/dtaameri/public_html/superoptica` |
| Base de datos | `dtaameri_espejuelos` (es la base `espejuelos`) |
| PHP para cron | `/usr/local/bin/ea-php83` |
| Enlace de imágenes | `public_html/superoptica/storage` → `superoptica-app/storage/app/public` |

Primera publicación:

1. Ejecuta `.\scripts\build-deploy.ps1`: corre pruebas, formato, `npm run build`,
   `composer install --no-dev` en una copia y deja dos zip en `storage/app/deploy/`
   (`superoptica-app-*.zip` y `superoptica-public-*.zip`) más la `APP_KEY` generada.
   El script no sube nada ni toca cPanel.
2. Sube y extrae el zip de la app en `/home/dtaameri/superoptica-app`, y el zip público en
   `/home/dtaameri/public_html/superoptica`.
3. Configura el `.env` de producción (`APP_URL=https://dtaamerica.com/superoptica`, base
   `dtaameri_espejuelos`) y crea a mano el usuario de MySQL en cPanel.
4. Ejecuta `migrate --force`, `db:seed --force` y `optimize` mediante **Trabajos de cron**
   (minuto `*/5`, resto en `*`), que son la única forma de lanzar `artisan` sin SSH. Borra
   cada tarea puntual después de comprobar `storage/logs/deploy.log`.
5. Crea una sola vez el enlace de las imágenes:
   `ln -s /home/dtaameri/superoptica-app/storage/app/public /home/dtaameri/public_html/superoptica/storage`.
   Si el hosting bloquea enlaces simbólicos, copia los archivos después de cada subida.
6. Deja creado el cron permanente del planificador (cada minuto):

```bash
/usr/local/bin/ea-php83 /home/dtaameri/superoptica-app/artisan schedule:run >> /home/dtaameri/superoptica-app/storage/logs/scheduler.log 2>&1
```

Actualizaciones: genera un zip nuevo y extráelo **sobre** las carpetas existentes (conserva
`.env`, `storage` y el enlace), borra los `.php` de `bootstrap/cache`, lanza `migrate
--force` y `optimize` por cron y verifica el sitio. Para revertir, extrae el zip anterior y
conserva el respaldo de la base; nunca uses `migrate:fresh` en producción.

Livewire en el subdirectorio: `scripts/build-deploy.ps1` ejecuta
`php artisan livewire:publish --assets`, así que los dos zip incluyen
`public/vendor/livewire` (el público lo sirve y el de la app permite que Livewire detecte su
manifest). Además, `app/Providers/AppServiceProvider.php` enlaza una subclase de
`HandleRequests` para que la URL de actualización sea `url('/livewire/update')` e incluya
`/superoptica`; sin ese ajuste el formulario de `/admin` envía a `/livewire/update` y
devuelve 404. Si `/admin` deja de responder, revisa primero esas dos rutas.

## Operación

- Solo se opera sobre la base `espejuelos`; no hay conexiones auxiliares.
- Ningún cambio en cPanel se ejecuta desde el proyecto: se documenta para que lo aplique el usuario.
- `scripts/build-deploy.ps1` solo empaqueta en local: no sube archivos, no llama a la API de cPanel y no toca la base.
- Los assets compilados (`public/build`), las fuentes (`public/fonts`), los publicados de
  Filament y los de Livewire (`public/vendor/livewire`) se versionan porque el servidor no
  ejecuta Node ni puede publicarlos por sí solo.
