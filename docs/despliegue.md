# Despliegue manual en cPanel

Esta guía se ejecuta **a mano** desde cPanel o su Terminal. El proyecto no incluye
scripts de despliegue, no llama a la API de cPanel y no toca ninguna base de datos
que no sea `espejuelos`.

> Sustituye `USUARIO` por tu usuario de cPanel y `superoptica` por la carpeta del
> proyecto. Sustituye también el dominio de ejemplo por el real.

## 1. Preparación local (antes de subir)

```powershell
$php = "$env:LOCALAPPDATA\php\8.3\php.exe"

& $php artisan test
& $php vendor/bin/pint --test
npm run build
```

Requisitos:

- La suite completa debe pasar al 100 %.
- `public/build` y `public/fonts` se generan en local y se suben ya compilados,
  porque el servidor no ejecuta Node.
- Etiqueta la release para poder volver atrás:

```powershell
git tag -a v1.0.0 -m "Primera publicación"
git push origin main --tags
```

## 2. Respaldo en cPanel (manual)

1. En **phpMyAdmin**, selecciona la base `espejuelos` y exporta un `.sql`.
2. En **Administrador de archivos**, comprime y descarga `storage/app/public`.
3. Si ya existe una publicación, descarga o renombra la carpeta actual de
   `public_html` (por ejemplo `public_html-anterior`).

## 3. Variables de entorno

Copia `.env.example` como `.env` en el servidor y ajusta:

```dotenv
APP_ENV=production
APP_DEBUG=false
APP_URL=https://TU-DOMINIO
APP_KEY=

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=espejuelos
DB_USERNAME=espejuelos
DB_PASSWORD=LA_CLAVE_DE_CPANEL

MEDIA_DISK=public
ADMIN_PASSWORD=UNA_CLAVE_LARGA
```

Genera la clave una sola vez:

```bash
php artisan key:generate
```

No cambies `DB_DATABASE`: la aplicación solo opera sobre `espejuelos`.

## 4. Subida de archivos

1. Crea `/home/USUARIO/releases/AAAA-MM-DD-HHMM/` y sube el proyecto sin
   `node_modules`, sin `.git` y sin la base SQLite local.
2. Sube `public/build` y `public/fonts` tal como quedaron en local.
3. Instala dependencias de producción en el servidor (Terminal de cPanel):

```bash
cd /home/USUARIO/releases/AAAA-MM-DD-HHMM
composer install --no-dev --optimize-autoloader
```

Si el servidor no permite Composer, sube la carpeta `vendor` generada en local con
`composer install --no-dev --optimize-autoloader`.

## 5. Base de datos y almacenamiento

```bash
php artisan migrate --force
```

Esto solo crea o actualiza tablas en `espejuelos`. **Nunca** ejecutes
`migrate:fresh` en producción.

Enlaza las imágenes:

```bash
php artisan storage:link
```

Si cPanel no permite enlaces simbólicos, crea la carpeta `public/storage` y copia
allí el contenido de `storage/app/public` desde el Administrador de archivos.
Repite la copia cada vez que subas imágenes nuevas desde el panel.

## 6. Cachés de producción

```bash
php artisan optimize
```

Eso cachea configuración, rutas y vistas. Para limpiar:

```bash
php artisan optimize:clear
```

Si cambias `.env`, vuelve a ejecutar `php artisan optimize`.

## 7. Publicar el dominio

En **Dominios** de cPanel, apunta el document root a la carpeta `public` de la
release nueva. Si usas un enlace `current`, cambia el enlace y conserva la release
anterior intacta.

Verifica estas rutas:

- `/`
- `/catalogo`
- `/servicios`
- `/tienda`
- `/sitemap.xml`
- `/admin` (con el usuario administrador)

## 8. Cron del planificador (crear a mano en cPanel)

Laravel usa el planificador para tareas futuras. Aunque hoy no haya tareas
programadas, deja la entrada lista. En **Trabajos de cron** crea una tarea:

- **Frecuencia:** cada minuto (`* * * * *`).
- **Comando:**

```bash
/usr/local/bin/php /home/USUARIO/superoptica/artisan schedule:run >> /home/USUARIO/superoptica/storage/logs/scheduler.log 2>&1
```

Si el binario de PHP 8.3 está en otra ruta, usa la que muestre **Select PHP
Version** en cPanel (por ejemplo
`/opt/cpanel/ea-php83/root/usr/bin/php`). La ruta del `artisan` debe ser la de la
release publicada.

## 9. Revertir a la release anterior

1. Vuelve a apuntar el dominio (o el enlace `current`) a la release anterior.
2. Si la release nueva subió imágenes, restaura `storage/app/public` desde el
   respaldo.
3. Restaura el `.sql` de `espejuelos` **solo** si la release nueva aplicó una
   migración incompatible. Las migraciones del proyecto son aditivas; en la
   mayoría de los casos basta con volver los archivos.
4. Ejecuta `php artisan optimize:clear` en la release restaurada y comprueba
   `/`, `/catalogo` y `/admin`.
5. Conserva la release fallida hasta confirmar que la anterior funciona.

## 10. Restricciones permanentes

- Solo se opera sobre la base `espejuelos`.
- Ningún cambio de cPanel se ejecuta desde el proyecto: cron, dominios, SSL,
  correo y versión de PHP se crean a mano.
- Los assets se compilan en local; el servidor no ejecuta Node.
- Ante cualquier error, no archives el cambio ni publiques: registra el fallo en
  `docs/verificacion.md` y vuelve a la release anterior.
