# Tasks

## 1. Inventario y trazabilidad

- [x] 1.1 Crear una matriz de trazabilidad en `docs/manual-usuario.md` y `docs/manual-operacion.md` que asigne cada requirement de `product-catalog`, `services`, `store-contact`, `site-settings`, `brand-experience` y `platform-operations` a una sección; verificar que las seis capabilities aparecen y que ningún requirement queda sin sección.
- [x] 1.2 Redactar primero, como lista de comprobación, los procedimientos que debe cubrir cada manual (consulta pública, gestión de contenido, respaldo, publicación, incidencia y reversión) y confirmar que cada uno tiene una ruta o comando verificable antes de escribir el texto definitivo.

## 2. Manual de usuario

- [x] 2.1 Escribir la parte pública de `docs/manual-usuario.md` (portada, catálogo, filtros, búsqueda, ficha, variantes, servicios, tienda y canales de contacto); verificar que cada procedimiento usa rutas existentes en `routes/web.php` y nombres visibles en las vistas.
- [x] 2.2 Escribir la parte del panel en `docs/manual-usuario.md` (acceso, marcas, productos, variantes, imágenes, servicios y ajustes del sitio); verificar campo por campo contra `ProductForm`, `BrandForm`, `ServiceForm` y `ManageSiteSettings`.
- [x] 2.3 Añadir a `docs/manual-usuario.md` las reglas de publicación (activo, destacado, fecha de publicación), el formato de precio `1,500 CUP`, el comportamiento de WhatsApp y una sección de errores frecuentes; verificar que cada regla coincide con la spec correspondiente y que no se promete ninguna función fuera de alcance (cuentas, carrito, pagos).
- [x] 2.4 Revisar el manual completo con el recorrido de una tarea real (crear una marca, crear un producto con dos colores y una imagen, publicarlo y consultarlo en `/catalogo`); verificar que los pasos descritos bastan para completarla sin ayuda técnica y corregir cualquier hueco.

## 3. Manual de operación

- [x] 3.1 Escribir en `docs/manual-operacion.md` las rutinas periódicas (respaldo de base y archivos, revisión de logs, verificación del cron) y el procedimiento de restauración; verificar que cada paso indica ejecución manual, que la única base nombrada es `espejuelos` y que no se propone ninguna automatización.
- [x] 3.2 Escribir el ciclo de publicación de una actualización, la verificación posterior y la reversión, enlazando `docs/despliegue.md` para los comandos; verificar que cada comando y ruta citados coinciden literalmente con esa guía.
- [x] 3.3 Escribir la respuesta ante incidencias (sitio caído, imágenes rotas, login del panel, error 500, migración fallida) y una lista de comprobación posterior a cada cambio; verificar que cada síntoma remite a un log o a una sección concreta y que las restricciones permanentes de cPanel están repetidas de forma explícita.
- [x] 3.4 Verificar que `docs/manual-operacion.md` no contiene contraseñas, claves, correos reales ni datos personales; sustituir cualquier valor sensible por marcadores como `ADMIN_EMAIL` o `ADMIN_PASSWORD`.

## 4. Índice y enlaces

- [x] 4.1 Crear `docs/README.md` con el propósito, el público y el orden de lectura de cada documento (`manual-usuario`, `manual-operacion`, `despliegue`, `verificacion`); verificar que todos los enlaces relativos resuelven a archivos existentes.
- [x] 4.2 Añadir al `README.md` raíz una sección “Documentación” con enlaces a los manuales y a las guías; verificar que los enlaces abren el archivo correcto desde la raíz del repositorio.
- [x] 4.3 Enlazar los manuales entre sí y desde `docs/despliegue.md` cuando un tema cruce audiencias (por ejemplo, reportar una incidencia); verificar que no queda ningún documento huérfano ni enlace roto.

## 5. Revisión final

- [x] 5.1 Revisar coherencia de nombres, tono y formato entre los dos manuales: términos de la interfaz, precios en CUP con coma de miles, nombres de rutas y numeración de pasos; verificar que no hay contradicciones entre manuales ni con las specs.
- [x] 5.2 Ejecutar `openspec validate add-user-and-operations-manual --strict` y comprobar que los cuatro documentos (`docs/README.md`, `docs/manual-usuario.md`, `docs/manual-operacion.md` y el `README.md` actualizado) existen; registrar el resultado en `docs/verificacion.md` si el proyecto lo requiere al archivar.

## Workflow follow-up

- Archivar el cambio después de la revisión y aprobación del contenido por el negocio.
