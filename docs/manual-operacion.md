# Manual de operación — SuperÓptica 20 20

Guía para quien mantiene la instalación publicada. Dice **qué hacer y cuándo**; los comandos exactos, las rutas y los detalles del hosting están en [despliegue.md](despliegue.md).

## 1. Reglas permanentes

- **Una sola base de datos:** `espejuelos` (en cPanel aparece como `dtaameri_espejuelos`). No se consultan, migran ni modifican otras bases del servidor.
- **cPanel se opera a mano:** archivos, dominios, cron, correo, SSL y versión de PHP los cambia el usuario desde cPanel. El proyecto no automatiza esas acciones.
- **Sin SSH ni Terminal:** `artisan` se ejecuta con Trabajos de cron o con el script temporal documentado en `despliegue.md`.
- **Assets en local:** `npm run build` se ejecuta en el equipo de desarrollo. El servidor no ejecuta Node ni Composer.
- **La aplicación vive fuera de la web:** el código está en `superoptica-app`; solo se publica el contenido de `public/` en `public_html/superoptica`.
- **Nunca `migrate:fresh` en producción:** borra los datos de `espejuelos`.
- **Respaldo antes de cambiar:** ninguna actualización empieza sin una copia de la base y de los archivos.

## 2. Mapa de la instalación

| Concepto | Valor |
|---|---|
| URL del sitio | `https://dtaamerica.com/superoptica` |
| Usuario de cPanel | `dtaameri` |
| Aplicación | `/home/dtaameri/superoptica-app` |
| Carpeta publicada | `/home/dtaameri/public_html/superoptica` |
| Base de datos | `dtaameri_espejuelos` (la base `espejuelos`) |
| PHP | 8.3 (`ea-php83`) |
| Binario PHP para cron | `/usr/local/bin/ea-php83` |
| Enlace de imágenes | `public_html/superoptica/storage` → `superoptica-app/storage/app/public` |
| Logs | `superoptica-app/storage/logs/laravel.log`, `deploy.log`, `scheduler.log` |

El cron permanente del planificador corre cada minuto:

```bash
/usr/local/bin/ea-php83 /home/dtaameri/superoptica-app/artisan schedule:run >> /home/dtaameri/superoptica-app/storage/logs/scheduler.log 2>&1
```

## 3. Rutinas

### 3.1 Cada semana

1. Abre `/`, `/catalogo`, `/servicios` y `/tienda`. Comprueba que cargan con estilos e imágenes.
2. Abre el catálogo, filtra por una marca y abre una ficha con foto.
3. Pulsa un botón de WhatsApp y confirma que abre la conversación correcta.
4. Entra en `/admin` con el correo y la contraseña del administrador.
5. Revisa el final de `storage/logs/laravel.log` desde el Administrador de archivos. Si aparecen errores repetidos, anótalos con fecha y hora.

### 3.2 Cada mes

1. Haz un respaldo completo (sección 4).
2. Confirma que el cron del planificador sigue activo.
3. Revisa el espacio disponible de la cuenta y el tamaño de `storage/app/public`.
4. Confirma en cPanel que la versión de PHP sigue siendo 8.3 y que el certificado SSL no está vencido.
5. Revisa que el usuario administrador siga siendo el correcto y que no haya usuarios extraños.

## 4. Respaldos y restauración

### 4.1 Crear un respaldo

1. **Base de datos:** en phpMyAdmin, selecciona `dtaameri_espejuelos`, exporta en formato SQL y guarda el archivo con la fecha, por ejemplo `espejuelos-2026-11-20.sql`.
2. **Archivos:** en el Administrador de archivos, comprime y descarga `superoptica-app` y `public_html/superoptica`.
3. **Fuera del servidor:** guarda las copias en el equipo local o en un almacenamiento externo.
4. **Antes de actualizar:** conserva además el zip de la versión que vas a subir.

### 4.2 Restaurar

1. Restaura **solo** la base `dtaameri_espejuelos` desde phpMyAdmin.
2. Extrae los archivos de la copia sobre las carpetas correspondientes.
3. Borra los `.php` de `superoptica-app/bootstrap/cache`.
4. Verifica `/`, `/catalogo` y `/admin`.

No importes una copia en otra base del servidor. Si la restauración falla, detente y conserva los logs; no improvises sobre otras bases.

## 5. Publicar una actualización

El flujo completo, con los comandos y las particularidades de este hosting, está en [despliegue.md](despliegue.md). En resumen:

1. **En local:** ejecuta `.\scripts\build-deploy.ps1`. Corre pruebas, formato, compila assets y genera los dos zip y el resumen con la `APP_KEY`.
2. **Respaldo:** base y archivos, como en la sección 4.
3. **Archivos:** sube y extrae el zip de la aplicación sobre `superoptica-app`, y el zip público sobre `public_html/superoptica`. Se conservan `.env`, `storage` y el enlace de imágenes.
4. **Cachés:** borra los `.php` de `superoptica-app/bootstrap/cache`.
5. **Migraciones:** crea un cron puntual con minuto `*/5` para `migrate --force`, espera, revisa `deploy.log` y borra la tarea.
6. **Cachés de producción:** repite el cron con `optimize`, revisa el log y borra la tarea.
7. **Verificación:** completa la lista de la sección 6.

Si el `.env` cambia, edítalo **antes** de ejecutar `optimize`. Mientras exista `bootstrap/cache/config.php`, los cambios del `.env` no se aplican.

## 6. Verificación posterior

| Comprobación | Resultado esperado |
|---|---|
| `https://dtaamerica.com/superoptica/` | Portada con estilos, imágenes y botones. |
| `/catalogo` | Productos, filtros por URL y paginación. |
| Una ficha con foto | Imagen visible y WhatsApp con modelo y color. |
| `/servicios` | Servicios activos, sin precios, con WhatsApp. |
| `/tienda` | Dirección, horarios, mapa y contactos. |
| `/sitemap.xml` | URLs con el prefijo `/superoptica`. |
| `/robots.txt` y `/up` | Responden correctamente; `/up` devuelve 200. |
| `/admin` | El formulario de acceso responde y permite entrar. |
| HTTPS | Sin contenido mixto ni avisos de certificado. |

Si algo falla, revisa `storage/logs/laravel.log` antes de tocar archivos.

## 7. Reversión

1. Extrae el zip anterior de la aplicación sobre `superoptica-app`.
2. Extrae el zip público anterior sobre `public_html/superoptica`.
3. Borra los `.php` de `superoptica-app/bootstrap/cache`.
4. Restaura el SQL de `dtaameri_espejuelos` **solo** si la versión nueva aplicó una migración incompatible. Las migraciones son aditivas; casi siempre basta con volver los archivos.
5. Verifica `/`, `/catalogo` y `/admin`.

## 8. Incidencias comunes

| Síntoma | Qué revisar |
|---|---|
| Error 500 tras actualizar | Cachés y permisos. Borra `bootstrap/cache/*.php` y revisa `laravel.log`. |
| Imágenes rotas | Enlace `storage` ausente o roto. Repite el `ln -s`; si el hosting no lo permite, copia los archivos. |
| La imagen da 404 aunque el archivo existe | Revisa `media.disk` en phpMyAdmin. Si dice `local`, la subida se guardó en el disco privado: actualiza `ProductForm.php` con `->disk('public')`, borra la imagen desde el panel y vuelve a subirla. |
| No se puede entrar en `/admin` | Usa el correo (`ADMIN_EMAIL`), no el nombre. Revisa `APP_URL` y que existan los archivos de Livewire en `public_html/superoptica/vendor/livewire/` y `superoptica-app/public/vendor/livewire/manifest.json`. |
| El formulario de acceso no responde | Livewire carga su JavaScript desde la raíz del dominio. Comprueba las dos rutas de Livewire y extrae los dos zip. |
| `/catalogo` da 404 pero `/` responde | Falta `.htaccess` o reescritura. Extrae de nuevo el zip público; si persiste, añade `RewriteBase /superoptica/`. |
| Un cron no se ejecuta y no hay `deploy.log` | Revisa la ruta de `artisan`, el binario `/usr/local/bin/ea-php83` y programa con `*/5` y el resto en `*`. La hora del servidor no es la de Cuba. |
| `deploy.log` está vacío | El binario PHP no arrancó. Usa `/usr/local/bin/ea-php83`. |
| Los cambios del `.env` no se aplican | `optimize` dejó `bootstrap/cache/config.php`. Borra los `.php` de `bootstrap/cache` y repite `optimize`. |
| El WhatsApp no abre | Revisa el número en Ajustes del sitio: solo dígitos, con código de país. |
| Error de conexión a la base | Verifica `pdo_mysql`, las credenciales del `.env` y que la base sea `dtaameri_espejuelos`. No configures otra base. |
| Un producto no aparece en el sitio | No es una incidencia del servidor: revisa que esté activo y publicado en el panel. Ver [manual de usuario](manual-usuario.md). |

La lista ampliada de problemas ya encontrados está en la sección 7 de [despliegue.md](despliegue.md).

## 9. Dónde mirar

| Archivo | Qué contiene |
|---|---|
| `superoptica-app/storage/logs/laravel.log` | Errores de la aplicación. |
| `superoptica-app/storage/logs/deploy.log` | Resultado de las tareas puntuales (`migrate`, `seed`, `optimize`). |
| `superoptica-app/storage/logs/scheduler.log` | Ejecución del planificador. |
| `superoptica-app/bootstrap/cache` | Configuración, rutas y vistas cacheadas. |
| `public_html/superoptica/storage` | Enlace a las imágenes públicas. |

Para ejecutar comandos sin SSH, usa las tareas de cron puntuales de `despliegue.md` y borra cada tarea al terminar.

## 10. Cuándo escalar

Detente y pide apoyo técnico si:

- el error persiste después de revisar cachés, permisos y logs;
- hay que tocar una base de datos distinta de `espejuelos`;
- una migración falla o deja la base en un estado dudoso;
- se pierde el acceso al panel y no hay forma de restablecer la contraseña desde el servidor;
- el hosting cambia rutas, PHP, SSL o permisos.

Antes de escalar, conserva la fecha y hora del problema, el mensaje del log, qué se cambió por última vez y una captura de la pantalla.

## 11. De dónde sale este manual

| Sección | Capability |
|---|---|
| 1 Reglas, 2 Instalación, 5 Actualización, 7 Reversión, 8 Incidencias | `platform-operations` |
| 3 Rutinas y 6 Verificación | `platform-operations`, `product-catalog`, `store-contact` |
| 4 Respaldos | `platform-operations` |
| Contenido y ajustes que se verifican | `site-settings` |
