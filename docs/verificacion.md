# Verificación de la implementación

- **Fecha:** 16 de noviembre de 2026
- **Cambio:** `add-showcase-site`
- **Entorno local:** Windows, PHP 8.3.36, Laravel 12, Livewire 3, Filament 4, SQLite en `database/espejuelos.sqlite`
- **Producción:** pendiente de despliegue manual en cPanel; esta verificación no ejecuta nada en el servidor.

## Resultado general

| Comprobación | Comando | Resultado |
|---|---|---|
| Suite completa | `php artisan test` | **125 pruebas, 530 aserciones — 100 % en verde** |
| Formato | `php vendor/bin/pint --test` | Sin diferencias |
| Assets | `npm run build` | Compilado sin errores |
| OpenSpec | `openspec validate add-showcase-site --strict` | Cambio válido |
| Accesibilidad | Lighthouse en `/`, `/catalogo`, `/servicios`, `/tienda` | **100 / 100** en las cuatro páginas |
| Buenas prácticas | Lighthouse | **100 / 100** |
| SEO | Lighthouse | **100 / 100** |
| Peso de la home | Informe de red | **≈ 225 KB** (límite: 500 KB) |
| Móvil 360 px | Revisión en navegador | Sin desbordamiento horizontal |

## Suite de pruebas

```
php artisan test
```

Cubre, entre otros:

- Visibilidad de productos publicados, borradores e inactivos.
- Filtros por URL (`marca`, `tipo`, `genero`, `material`, `forma`, `q`) y paginación.
- Búsqueda sin JavaScript y búsqueda en vivo de Livewire.
- Estados vacíos del catálogo y contacto por WhatsApp.
- Precio referencial en CUP con coma de miles y sin decimales.
- Ficha de producto: campos opcionales, galería, selector de color y enlace de WhatsApp con modelo y color.
- Conversiones WebP (`thumb`, `card`, `detail`) y carga diferida.
- Servicios activos, orden, ausencia de precios y CTA por servicio.
- Dirección completa, horarios con días cerrados, mapa sin terceros, `tel:` y `t.me`.
- Botón flotante de WhatsApp con etiqueta accesible.
- Ajustes del sitio, caché, validación, SEO, `sitemap.xml` y panel de Filament.
- Fuentes auto-hospedadas, favicon «20 20», textos alternativos y enlaces de contacto.

## Lighthouse

Ejecutado con el navegador integrado sobre el servidor local:

| Página | Accesibilidad | Buenas prácticas | SEO |
|---|---|---|---|
| `/` | 100 | 100 | 100 |
| `/catalogo` | 100 | 100 | 100 |
| `/servicios` | 100 | 100 | 100 |
| `/tienda` | 100 | 100 | 100 |

Sin fallos de contraste, etiquetas accesibles ni nombres inconsistentes.

## Peso de la home

Medido con `performance.getEntriesByType('resource')` y el documento:

| Recurso | Bytes |
|---|---|
| Documento HTML | 20 381 |
| CSS compilado | 73 920 |
| JavaScript | 59 519 |
| Inter (WOFF2 variable) | 48 556 |
| Space Grotesk (WOFF2 variable) | 22 588 |
| **Total** | **224 964 (≈ 225 KB)** |

Las imágenes del catálogo usan `loading="lazy"` y quedan fuera de este cálculo,
tal como exige la tarea 8.3.

## Comprobaciones manuales en navegador

- El catálogo filtra por los cinco criterios y conserva los filtros al paginar.
- La búsqueda sin JavaScript devuelve los mismos resultados que la búsqueda en vivo.
- Al cambiar de color en la ficha, cambian la imagen y el mensaje de WhatsApp.
- El botón flotante de WhatsApp recibe foco con `Tab` y muestra contorno visible.
- El mapa de la tienda es una referencia estática: no hay `iframe` ni solicitudes a
  Google Maps, OpenStreetMap, Mapbox o Bing al cargar.
- A 360 px no hay desplazamiento horizontal en `/`, `/catalogo`, `/servicios` ni `/tienda`.

## Pendiente de ejecución manual (cPanel)

Estas acciones **no** se ejecutaron y quedan a cargo del usuario, según
`docs/despliegue.md`:

1. Respaldo de `espejuelos` y de `storage/app/public`.
2. Creación del `.env` de producción y `php artisan key:generate`.
3. `composer install --no-dev`, `php artisan migrate --force`, `storage:link` y `optimize`.
4. Publicación del dominio y creación manual del cron de `schedule:run`.
5. Verificación de las rutas públicas y del panel en el servidor.

## Conclusión

La suite completa pasa al 100 %, el formato está limpio, el cambio valida con
`openspec validate --strict` y las páginas públicas cumplen accesibilidad, SEO y
peso. El cambio puede pasar a revisión del negocio y archivarse después de la
publicación manual.
