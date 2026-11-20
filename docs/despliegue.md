# Despliegue y actualizaciones en cPanel

El sitio se publica a mano en un **cPanel de hosting compartido, sin SSH y sin Terminal**.
Todo se hace desde el Administrador de archivos, phpMyAdmin y Trabajos de cron. El
proyecto incluye un script **local** que prepara el paquete (`scripts/build-deploy.ps1`);
ese script no ejecuta despliegues, no llama a la API de cPanel y no toca ninguna base de
datos que no sea `espejuelos`.

Esta guía ya está ajustada a la instalación real. Las instrucciones genéricas usan el
patrón `https://DOMINIO/superoptica`; en este caso, `https://dtaamerica.com/superoptica`.

## 0. Datos de esta instalación

| Concepto | Valor verificado |
|---|---|
| Dominio del cPanel | `dtaamerica.com` (la aplicación no tiene dominio ni subdominio propio) |
| URL del sitio | `https://dtaamerica.com/superoptica` |
| Usuario de cPanel | `dtaameri` |
| Raíz de la aplicación (fuera de la web) | `/home/dtaameri/superoptica-app` |
| Carpeta publicada | `/home/dtaameri/public_html/superoptica` |
| PHP | 8.3 (`ea-php83`), confirmado en MultiPHP |
| Binario PHP para cron | `/usr/local/bin/ea-php83` (probado y funcionando) |
| Base de datos | `espejuelos`, que cPanel muestra como `dtaameri_espejuelos` |
| Logs | `superoptica-app/storage/logs/laravel.log`, `deploy.log`, `scheduler.log` |
| Zona horaria del cron | **No es la de Cuba**; programa con `*/5` o mira la hora del servidor |
| Enlace de imágenes | `public_html/superoptica/storage` → `superoptica-app/storage/app/public` (creado y verificado) |
| Estado | Publicada el 17/11/2026: migraciones, seeders y `optimize` ejecutados por cron |

La hora del servidor se ve en la columna **Last Modified** del Administrador de archivos.
Fiarse de la hora de Cuba para programar un cron ha fallado ya una vez: una tarea fijada a
las 17:00 del día 17 no se ejecutó porque en el servidor ya eran las 17:10, y con día y mes
fijos no se repite hasta el año siguiente.

```
/home/dtaameri/
├── superoptica-app/                  ← Laravel completo (.env, app, vendor, storage)
└── public_html/
    ├── otra-app/                     ← otras aplicaciones, no tocar
    └── superoptica/                  ← contenido de public/ (index.php adaptado)
        ├── .htaccess
        ├── index.php
        ├── build/
        ├── fonts/
        ├── css/
        ├── js/
        └── storage/                  ← enlace a superoptica-app/storage/app/public
```

La aplicación va fuera de `public_html` para que `.env`, `vendor`, `storage` y el código no
sean accesibles por URL. Solo se publica el contenido de `public/`.

## 1. Preparar el paquete en local

Desde la raíz del proyecto:

```powershell
.\scripts\build-deploy.ps1
```

El script:

1. `composer install` para tener las dependencias de desarrollo.
2. `php artisan test` y `pint --test`; si algo falla, se detiene.
3. `npm run build` (instala las dependencias de Node si falta `node_modules`).
4. `php artisan optimize:clear`, `filament:upgrade` y `livewire:publish --assets`; esto
   deja el JavaScript de Livewire en `public/vendor/livewire`.
5. Genera una `APP_KEY` nueva y la imprime. Solo se usa la primera vez; en las
   actualizaciones conserva la clave del `.env` del servidor.
6. Copia el proyecto a una carpeta temporal y quita `.env`, `database/*.sqlite`,
   `public/hot`, `public/storage`, `tests`, las cachés y los restos de pruebas.
7. Ejecuta `composer install --no-dev --optimize-autoloader` **solo en la copia**, así el
   `vendor` local mantiene phpunit y Pint.
8. Genera dos zip con rutas `/` (compatibles con Linux) y un resumen con comandos.

Salida en `storage/app/deploy/`:

| Archivo | Para qué |
|---|---|
| `superoptica-app-AAAA-MM-DD-HHMM.zip` | Se extrae en `/home/dtaameri/superoptica-app` |
| `superoptica-public-AAAA-MM-DD-HHMM.zip` | Se extrae en `/home/dtaameri/public_html/superoptica` |
| `despliegue-AAAA-MM-DD-HHMM.txt` | `APP_KEY`, rutas y comandos de cron |

Opciones: `-SkipTests`, `-SkipAssets`, `-KeepStage`, `-PhpPath`, `-ComposerPath`.

Requisitos:

- La suite completa debe pasar al 100 %.
- `public/build` y `public/fonts` se compilan en local: el servidor no ejecuta Node.
- El zip público incluye `public/vendor/livewire`. Es obligatorio: sin esos archivos, el
  navegador pide `/livewire/livewire.min.js` en la raíz del dominio, que no existe, y el
  formulario de `/admin` queda sin JavaScript.
- El zip de la aplicación también incluye `public/vendor/livewire` (en
  `superoptica-app/public`). No se sirve desde ahí, pero Livewire comprueba
  `public_path('vendor/livewire/manifest.json')` para decidir la URL del script. Extrae
  **los dos** zip: si solo extraes el público, la página sigue pidiendo `/livewire/...` en
  la raíz.
- `app/Providers/AppServiceProvider.php` corrige la URL de actualización de Livewire para
  que incluya el subdirectorio (`/superoptica/livewire/update`). Sin ese ajuste, el login
  envía el formulario a `/livewire/update`, que da 404 en este hosting.
- El `vendor` debe generarse con PHP 8.3, la misma versión del servidor.
- Los zip no incluyen `.env` ni `database/espejuelos.sqlite`; si alguna vez empaquetas a
  mano, tampoco los incluyas.
- Si prefieres el modo manual, los pasos equivalentes en local son:

```bash
composer install --no-dev --optimize-autoloader
php artisan test
php artisan optimize:clear
```

  Y antes de empaquetar: `vendor/bin/pint --test`, `npm run build` y
  `php artisan key:generate --show`.

## 2. Actualizar una versión ya publicada

Este es el flujo normal a partir de ahora. La carpeta de la aplicación se **sobrescribe**
con el zip nuevo; no hace falta recrear el `.env` ni tocar las imágenes, porque el zip no
los incluye.

1. **Respaldo** (5 minutos):
   - phpMyAdmin → base `dtaameri_espejuelos` → exportar `.sql`.
   - Administrador de archivos → comprimir y descargar `superoptica-app` y
     `public_html/superoptica`.
   - Conserva además el zip que vas a subir.
2. **Archivos**:
   - Sube `superoptica-app-*.zip` a `/home/dtaameri/superoptica-app` y usa **Extract**
     sobre la carpeta existente. Se sobrescriben los archivos y se conservan `.env`,
     `storage/logs`, `storage/framework` y `storage/app/public`.
   - Sube `superoptica-public-*.zip` a `/home/dtaameri/public_html/superoptica` y extrae
     igual. Se conserva el enlace `storage`.
3. **Cachés**: borra los `.php` de `superoptica-app/bootstrap/cache` (equivale a
   `php artisan optimize:clear`). Si no lo haces, Laravel sigue usando la configuración,
   las rutas y las vistas de la versión anterior.
4. **Migraciones**: crea un cron puntual con `*/5`:

   ```bash
   /usr/local/bin/ea-php83 /home/dtaameri/superoptica-app/artisan migrate --force >> /home/dtaameri/superoptica-app/storage/logs/deploy.log 2>&1
   ```

   Espera hasta cinco minutos, revisa `deploy.log` y **borra la tarea**. Si no hay
   migraciones nuevas, dirá `Nothing to migrate`; es correcto.
5. **Cachés de producción**: repite el cron cambiando `migrate --force` por `optimize`,
   revisa el log y borra la tarea.
6. **Verificar**: portada, `/catalogo`, una ficha con foto, `/admin` y `/sitemap.xml`.
   Si algo falla, mira `storage/logs/laravel.log`.

Si el `.env` necesita cambios (por ejemplo, una clave nueva), edítalo **antes** del paso 5:
`optimize` cachea la configuración y, mientras exista `bootstrap/cache/config.php`, los
cambios del `.env` no se aplican.

### Revertir a la versión anterior

1. Extrae el zip anterior de la aplicación sobre `superoptica-app` y el zip público
   anterior sobre `public_html/superoptica`.
2. Borra los `.php` de `superoptica-app/bootstrap/cache`.
3. Restaura el `.sql` de `dtaameri_espejuelos` **solo** si la versión nueva aplicó una
   migración incompatible. Las migraciones son aditivas; casi siempre basta con volver
   los archivos.
4. Verifica `/`, `/catalogo` y `/admin`.

Nunca ejecutes `migrate:fresh` en producción: borra los datos de `espejuelos`.

## 3. Primera instalación (referencia)

Solo para una instalación desde cero o una recuperación.

### 3.1 Base de datos

En **Bases de datos de MySQL**:

1. Crea la base `espejuelos`. cPanel la mostrará como `dtaameri_espejuelos`.
2. Crea el usuario y asígnale todos los privilegios **solo sobre esa base**.
3. Usa `localhost` como host; si falla, prueba `127.0.0.1`.

No crees ni importes nada en otras bases del servidor.

### 3.2 Archivos

1. Crea `/home/dtaameri/superoptica-app` y extrae allí el zip de la aplicación. El
   contenido debe quedar directamente en esa carpeta (`artisan`, `app/`, `vendor/`...).
2. Crea `/home/dtaameri/public_html/superoptica` y extrae allí el zip público. Ya incluye
   `.htaccess`, `build`, `fonts`, `css`, `js`, `vendor/livewire`, favicons, `robots.txt` y
   el `index.php` adaptado (plantilla `deploy/public-index.php`).
3. Si el zip supera el límite de subida, usa FTP o divide el archivo.

### 3.3 `.env`

Muestra los archivos ocultos, crea `/home/dtaameri/superoptica-app/.env` y ajusta:

```dotenv
APP_NAME="SuperÓptica 20 20"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://dtaamerica.com/superoptica
APP_KEY=base64:EL_VALOR_QUE_IMPRIMIO_EL_SCRIPT

APP_LOCALE=es
APP_FALLBACK_LOCALE=es
APP_TIMEZONE=America/Havana

LOG_CHANNEL=stack
LOG_LEVEL=warning

DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=dtaameri_espejuelos
DB_USERNAME=dtaameri_espejuelos
DB_PASSWORD=LA_CLAVE_DEL_USUARIO

SESSION_DRIVER=file
SESSION_DOMAIN=null

CACHE_STORE=file
QUEUE_CONNECTION=sync
FILESYSTEM_DISK=local

MEDIA_DISK=public
IMAGE_DRIVER=gd

ADMIN_NAME=Administrador
ADMIN_EMAIL=admin@dtaamerica.com
ADMIN_PASSWORD=UNA_CLAVE_LARGA
```

Notas:

- `APP_URL` incluye `/superoptica` y no lleva barra final. Sin ese sufijo, el sitemap y las
  URLs de las imágenes apuntan a la raíz del dominio.
- `DB_DATABASE` y `DB_USERNAME` llevan el nombre completo con prefijo. Sigue siendo la
  misma base `espejuelos`; no se configura ninguna otra.
- `ADMIN_NAME` es solo el nombre visible del usuario (por ejemplo, `Alexander`); no se usa para iniciar sesión.
- `ADMIN_EMAIL` es solo el correo con el que se entra en `/admin`. **No es una cuenta de correo de cPanel**
  y no hace falta crearla: `MAIL_MAILER=log`, el panel no tiene recuperación de contraseña
  y el sitio no envía correos. Usa una dirección que controles por si algún día activas avisos.
- Define `ADMIN_EMAIL` antes de ejecutar `db:seed`. El seeder es idempotente por correo: si
  lo cambias después y vuelves a sembrar, crea un segundo usuario.
- `ADMIN_PASSWORD` es obligatorio: el seeder falla en producción si está vacío.

### 3.4 Permisos

- Carpetas `755` y archivos `644`.
- `superoptica-app/storage` y `superoptica-app/bootstrap/cache`: `775` con **Recurse**.
- `.env`: `600` o `644`.

Deben existir `storage/framework/cache/data`, `storage/framework/sessions`,
`storage/framework/views`, `storage/logs` y `bootstrap/cache`.

### 3.5 Crear las tablas y los datos iniciales

Con la aplicación ya subida, ejecuta los crons de la sección 4 en este orden: `migrate
--force`, `db:seed --force` y `optimize`. El log de la primera ejecución se ve así:

```
INFO  Preparing database.
INFO  Running migrations.
2026_11_16_000001_create_brands_table .... DONE
...
INFO  Seeding database.
Database\Seeders\AdminUserSeeder .... DONE
Database\Seeders\CatalogDataSeeder .. DONE
INFO  Caching framework bootstrap, configuration, and metadata.
```

### 3.6 Enlace de imágenes

Se crea **una sola vez** con un cron puntual (`*/5`):

```bash
ln -s /home/dtaameri/superoptica-app/storage/app/public /home/dtaameri/public_html/superoptica/storage
```

Comprueba en el Administrador de archivos que `public_html/superoptica/storage` aparece como
enlace y, al abrirlo, muestra las carpetas de las imágenes. **Borra la tarea inmediatamente**:
si vuelve a ejecutarse, `ln -s` crea un enlace anidado llamado `public` dentro de `storage`
y luego falla con `File exists`.

`php artisan storage:link` no sirve en esta variante: crearía el enlace dentro de
`superoptica-app/public`, que la web no publica. No lo ejecutes; usa el `ln -s` de arriba.

Si el hosting bloqueara los enlaces simbólicos (aquí funciona), la alternativa es copiar el
contenido de `storage/app/public` a `public_html/superoptica/storage` después de cada subida
de imágenes.

### 3.7 Cron del planificador

Este es el único cron permanente. Debe quedar cada minuto:

```bash
/usr/local/bin/ea-php83 /home/dtaameri/superoptica-app/artisan schedule:run >> /home/dtaameri/superoptica-app/storage/logs/scheduler.log 2>&1
```

La frecuencia `cada minuto` se configura en cPanel como `* * * * *`.

## 4. Ejecutar `php artisan` sin SSH

El binario que funciona en este servidor es `/usr/local/bin/ea-php83`. La alternativa
`/opt/cpanel/ea-php83/root/usr/bin/php` existe en muchos cPanel, pero aquí no hace falta.

Reglas aprendidas:

- Programa las tareas puntuales con **minuto `*/5`** y hora, día, mes y día de la semana en
  `*`. Así no dependes de la zona horaria del servidor.
- El comando se ejecuta en la hora del servidor (la de **Last Modified**), no en la de Cuba.
- Añade siempre `>> .../storage/logs/deploy.log 2>&1`: evita correos de cron y deja el
  resultado a la vista.
- El shell crea `deploy.log` **antes** de ejecutar PHP. Si el archivo no aparece, el cron no
  se ejecutó o la ruta no existe; si aparece vacío, PHP falló al arrancar.
- Borra cada tarea puntual en cuanto compruebes el log.

Comandos de esta instalación. En el resto de la guía, `php artisan migrate --force` y
`php artisan optimize` son la forma corta de estos comandos; en el servidor se escriben con
la ruta completa del binario y la del `artisan`.

| Objetivo | Comando |
|---|---|
| Crear o actualizar tablas | `/usr/local/bin/ea-php83 /home/dtaameri/superoptica-app/artisan migrate --force` |
| Datos iniciales y administrador | `/usr/local/bin/ea-php83 /home/dtaameri/superoptica-app/artisan db:seed --force` |
| Cachear configuración, rutas y vistas | `/usr/local/bin/ea-php83 /home/dtaameri/superoptica-app/artisan optimize` |

Añade a cada uno `>> /home/dtaameri/superoptica-app/storage/logs/deploy.log 2>&1`.

Si sospechas que el cron no corre, crea una tarea de prueba con:

```bash
{ date; /usr/local/bin/ea-php83 -v; } > /home/dtaameri/cron-prueba.txt 2>&1
```

El archivo muestra la hora y la versión de PHP del cron. Si no aparece, el cron no se está
ejecutando en la cuenta y hay que consultar al hosting. Borra la prueba al terminar.

### Alternativa: script PHP temporal

Si el cron no responde, sube `/home/dtaameri/public_html/superoptica/_setup.php`:

```php
<?php

// Archivo temporal de instalación. BORRARLO en cuanto termine.
$token = 'CAMBIA_ESTE_TOKEN_POR_UNO_LARGO_Y_UNICO';

if (($_GET['token'] ?? '') !== $token) {
    http_response_code(404);
    exit;
}

require __DIR__.'/../../superoptica-app/vendor/autoload.php';

$app = require_once __DIR__.'/../../superoptica-app/bootstrap/app.php';

$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo '<pre>';

foreach (['migrate --force', 'db:seed --force', 'optimize'] as $command) {
    echo "== php artisan {$command}\n";
    Illuminate\Support\Facades\Artisan::call($command);
    echo Illuminate\Support\Facades\Artisan::output()."\n";
}

echo '</pre>';
```

Ábrelo una sola vez en
`https://dtaamerica.com/superoptica/_setup.php?token=...` y **bórralo inmediatamente**.
Dejarlo publicado permite ejecutar comandos a cualquiera.

## 5. De SQLite local a MariaDB

La base local (`database/espejuelos.sqlite`) es solo de desarrollo y no se sube. No hace
falta convertir el archivo ni usar ninguna herramienta de migración de motores:

- **El esquema lo crean las migraciones.** Son las mismas para SQLite y MariaDB; `migrate
  --force` ya creó las tablas en `espejuelos`.
- **El contenido inicial lo crean los seeders.** `db:seed --force` inserta el usuario del
  panel, los materiales, las formas y los servicios de arranque, y es idempotente.
- **Las monturas y las fotos se cargan desde `/admin`.**

Si algún día hay que trasladar contenido real creado en local, exporta cada tabla como CSV
con un visor de SQLite y practicala en phpMyAdmin, en este orden: `brands`, `materials`,
`shapes`, `services`, `site_settings`, `products`, `product_variants` y, si hay fotos,
`media`. Conserva los `id` y sube los archivos de `storage/app/public`. El usuario del panel
se recrea con el seeder, no se importa.

## 6. Comprobar tras publicar o actualizar

- `https://dtaamerica.com/superoptica/` — portada con estilos e imágenes.
- `/catalogo`, `/servicios`, `/tienda` — navegación, filtros por URL y paginación.
- `/sitemap.xml` — URLs con el prefijo `/superoptica`.
- `/robots.txt` y `/up` (debe responder 200).
- `/admin` — inicio de sesión con `ADMIN_EMAIL` y `ADMIN_PASSWORD`. El formulario debe
  responder al enviar; si no lo hace, revisa que exista
  `superoptica-app/public/vendor/livewire/manifest.json` y que los archivos estén
  publicados en `public_html/superoptica/vendor/livewire/`.
- Una ficha con foto: imagen visible y enlace de WhatsApp con modelo y color.
- HTTPS sin contenido mixto.

Si algo falla, revisa `superoptica-app/storage/logs/laravel.log` desde el Administrador de
archivos. Antes de tener tablas, ese log acumula errores normales de tablas inexistentes.

## 7. Problemas ya encontrados

| Síntoma | Causa | Solución |
|---|---|---|
| La tarea de cron no se ejecuta y no hay `deploy.log` | Se programó una hora/día fijos y ese instante ya había pasado en la hora del servidor | Usa `*/5` con el resto en `*`; la tarea no se repite hasta el año siguiente |
| `deploy.log` no existe | El cron no corrió, o la ruta de `superoptica-app` no existe | Prueba con `date` y `php -v`; revisa la ruta |
| `deploy.log` vacío | El binario PHP no arrancó | Usa `/usr/local/bin/ea-php83` |
| Aparece un enlace `public` dentro de `storage` | El cron de `ln -s` se ejecutó más de una vez | Bórralo; el enlace bueno es `storage` |
| Los cambios del `.env` no se aplican | `optimize` dejó `bootstrap/cache/config.php` | Borra los `.php` de `bootstrap/cache` y repite `optimize` |
| 500 tras actualizar | Cachés o permisos | Borra `bootstrap/cache/*.php`; revisa `laravel.log` |
| Imágenes rotas | Enlace de `storage` ausente o roto | Repite el `ln -s`; si el hosting no lo permite, copia los archivos |
| 404 en `/catalogo` pero `/` responde | Falta `.htaccess` o `mod_rewrite` | Extrae de nuevo el zip público; si persiste, añade `RewriteBase /superoptica/` |
| El login no se mantiene | `APP_URL` o `SESSION_DOMAIN` | `APP_URL` con `/superoptica` y `SESSION_DOMAIN=null`; limpia caché |
| No deja entrar en `/admin` y marca el campo de correo | Se escribió el nombre (`ADMIN_NAME`) en el campo de correo | Entra con el valor de `ADMIN_EMAIL`, que es el `email` de la tabla `users`; el nombre no sirve como usuario |
| El formulario de `/admin` no responde o rechaza credenciales correctas | Livewire carga su JS desde `/livewire/...` en la raíz del dominio y da 404 | Deben existir `public_html/superoptica/vendor/livewire/` y `superoptica-app/public/vendor/livewire/manifest.json`; extrae los dos zip (el público sirve el JS y el de la app permite detectarlo) |
| Tras enviar el login aparece un 404 en `https://DOMINIO/livewire/update` | Livewire genera la URL de actualización sin el subdirectorio | Sube `app/Providers/AppServiceProvider.php` actualizado; devuelve `url('/livewire/update')` y no requiere tocar `.env` |
| `could not find driver` | Falta `pdo_mysql` | Actívalo en **Select PHP Version** → Extensiones |

## 8. Restricciones permanentes

- Solo se opera sobre la base `espejuelos` (`dtaameri_espejuelos`).
- Ningún cambio de cPanel se ejecuta desde el proyecto: base de datos, archivos, dominios,
  cron, SSL, correo y versión de PHP se crean a mano.
- No hay SSH ni Terminal: `artisan` se ejecuta con Trabajos de cron o con el script
  temporal, y ambos se documentan aquí.
- Los assets se compilan en local; el servidor no ejecuta Node ni Composer.
- `scripts/build-deploy.ps1` solo empaqueta en local: no sube archivos, no llama a la API
  de cPanel y no toca la base de datos.
- La aplicación nunca vive dentro de `public_html`; solo su carpeta `public` se publica
  como `public_html/superoptica`.
- Ante cualquier error, no archives el cambio ni publiques: registra la incidencia en
  `docs/verificacion.md` y vuelve a la versión anterior.
