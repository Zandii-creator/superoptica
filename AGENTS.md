# SuperÓptica 20 20 — Instrucciones del proyecto

## Reglas Críticas de Entorno (cPanel y Base de Datos)
- **Base de datos exclusiva:** Solo está permitido interactuar y modificar la base de datos `espejuelos`. Está estrictamente prohibido tocar, migrar o consultar de forma destructiva cualquier otra base de datos del servidor.
- **Cero automatización en cPanel:** No ejecutes acciones directas ni scripts de despliegue sobre cPanel (no hay CI/CD). Cualquier acción requerida en cPanel fuera de la base de datos `espejuelos` debe proponerse en texto para que el usuario la ejecute manualmente.
- **Flujo de desarrollo:** Para cambios estructurales o nuevas funcionalidades, sigue el flujo y las reglas definidas en `openspec/config.yaml`.

## Propósito
Sitio vitrina B2C para la óptica SuperÓptica 20 20 (Ciego de Ávila, Cuba). Muestra marca, catálogo de monturas y servicios; el cierre comercial ocurre en la tienda física o por WhatsApp. **No** hay cuentas, carrito, checkout, pagos, pedidos ni inventario.

## Stack
- Laravel 12 (PHP 8.3) + Livewire 3 + Tailwind CSS 4 gestionado por Vite.
- Panel de administración con Filament.
- Imágenes con Spatie Media Library e Intervention Image.
- Base de datos local SQLite (`database/espejuelos.sqlite`); producción MySQL con base `espejuelos`.

## Comandos del entorno local (Windows)

PHP y Composer no están en el PATH de shells nuevas, así que se invocan por ruta absoluta:

```powershell
$php = "$env:LOCALAPPDATA\php\8.3\php.exe"
$composer = "$env:LOCALAPPDATA\composer\composer.phar"
```

| Tarea | Comando |
|---|---|
| Dependencias PHP | `& $php $composer install` |
| Servidor de desarrollo | `& $php -S 127.0.0.1:8000 -t public` (`artisan serve` falla en este equipo) |
| Compilar assets | `npm run build` |
| Desarrollo de assets | `npm run dev` |
| Pruebas | `& $php artisan test` |
| Formato | `& $php vendor/bin/pint` |
| Verificar formato | `& $php vendor/bin/pint --test` |
| Migraciones | `& $php artisan migrate` |
| Recrear base local | `& $php artisan migrate:fresh --seed` |
| Panel | `& $php artisan make:filament-user` o `& $php artisan db:seed --class=AdminUserSeeder` |

Las descargas de Composer en este equipo fallan si se ejecutan en paralelo; usar siempre:

```powershell
$env:COMPOSER_MAX_PARALLEL_HTTP = '1'
```

## Convenciones
- **TDD obligatorio:** cada tarea empieza por una prueba que falla; la tarea termina cuando la prueba y la suite pasan.
- **Idioma:** código y nombres técnicos en inglés; textos de interfaz, contenido y documentación en español.
- **Precios:** enteros en CUP, formato `1,500 CUP`, sin `$` ni decimales.
- **Rutas públicas:** filtros y paginación en la URL, renderizado en el servidor. Livewire solo para búsqueda, galería y selector de color.
- **WhatsApp:** todos los enlaces se generan con un helper central que arma el mensaje con contexto.
- **Sin JavaScript innecesario:** el sitio debe funcionar en conexiones lentas y en móviles modestos.

## OpenSpec
- Contexto y reglas: `openspec/config.yaml`.
- Cambio en curso: `openspec/changes/add-showcase-site/`.
- Validar el cambio: `openspec validate add-showcase-site --strict`.
