# Proposal

## Why

El sitio ya está publicado y su comportamiento está descrito en las specs de `add-showcase-site`, pero ese conocimiento vive disperso entre documentos técnicos y el código. El personal del negocio no tiene una guía para usar el sitio ni el panel, y quien opera el hosting depende de `docs/despliegue.md` para tareas que van más allá de publicar una versión.

Sin manuales, cada cambio de contenido o incidencia requiere apoyo técnico, y las reglas críticas de operación —una sola base de datos `espejuelos` y cero automatización sobre cPanel— quedan solo en manos de quien las conoce.

## What Changes

- Se crea **`docs/manual-usuario.md`**, dirigido al personal del negocio, con dos partes:
  - **Sitio público**: qué muestra la portada, cómo navegar el catálogo, usar filtros y búsqueda, leer una ficha, consultar servicios y contactar por WhatsApp, teléfono o Telegram.
  - **Panel de administración**: acceso, gestión de marcas, productos, variantes, imágenes, servicios y ajustes del sitio; reglas de publicación, precios en CUP y qué hacer ante errores de validación.
- Se crea **`docs/manual-operacion.md`**, dirigido a quien opera la instalación: rutinas de respaldo y verificación, publicación de actualizaciones, migraciones, cachés, logs, respuesta ante incidencias, restricciones permanentes y lista de comprobación posterior a cada cambio.
- Se crea **`docs/README.md`** como índice de la documentación, con el propósito y el público de cada documento y el orden de lectura recomendado.
- Se actualiza el **`README.md`** raíz con una sección de documentación que enlace los manuales y las guías existentes.
- Ambos manuales se derivan de las specs existentes (`product-catalog`, `services`, `store-contact`, `site-settings`, `brand-experience`, `platform-operations`) e incluyen una tabla de trazabilidad entre cada sección y la capability que la respalda.
- El manual de operación **no duplica** `docs/despliegue.md`: lo referencia como anexo técnico para los comandos exactos de cPanel y conserva el principio de ejecución manual.
- **Sin cambios de código ni de comportamiento.** Por eso el cambio no introduce ni modifica capabilities (`skip_specs: true`).

## Capabilities

### New Capabilities

<!-- Ninguna: es un cambio exclusivamente de documentación. -->

### Modified Capabilities

<!-- Ninguna: no cambia el comportamiento descrito en las specs. -->

## Impact

- **Documentación**: nuevos archivos en `docs/` y una sección nueva en `README.md`.
- **Código**: ninguno; no se tocan rutas, vistas, modelos, pruebas ni assets.
- **Datos**: ninguno; no se ejecutan migraciones ni consultas.
- **cPanel**: ninguno; no se ejecutan acciones ni se agregan automatizaciones.
- **Proceso**: los manuales pasan a ser la referencia para el uso diario y la operación. Describen la instalación actual y no añaden automatizaciones ni procesos de actualización.
