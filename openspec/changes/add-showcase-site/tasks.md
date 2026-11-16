# Tasks

## 1. Base del proyecto

- [x] 1.1 Crear el proyecto Laravel 12 e instalar Livewire 3, Tailwind 4 y Filament; verificar que `php artisan about` y `npm run build` terminan sin errores.
- [x] 1.2 Configurar la conexión a la base de datos `espejuelos` y verificar con `php artisan db:show` que solo esa base está configurada, sin conexiones auxiliares ni acceso a otras bases del servidor.
- [x] 1.3 Crear el usuario administrador inicial y verificar el acceso a `/admin` con autenticación.
- [x] 1.4 Crear `AGENTS.md` con propósito, stack, comandos reales y convenciones; verificar que cada comando documentado existe y se ejecuta.
- [x] 1.5 Configurar Pint y el estilo base; verificar `./vendor/bin/pint --test` sin diferencias.

## 2. Modelo de datos y panel

- [x] 2.1 Escribir primero las pruebas de relaciones y valores por defecto (deben fallar), luego crear las migraciones de marcas, productos, variantes, servicios y ajustes con sus índices; verificar `php artisan migrate:fresh --seed` y las pruebas en verde, confirmando que solo se opera sobre `espejuelos`.
- [x] 2.2 Escribir primero las pruebas de visibilidad de borradores e inactivos (deben fallar), luego implementar modelos, relaciones y enums de tipo y género; verificar que las pruebas pasan.
- [x] 2.3 Escribir primero las pruebas de creación de un producto con variante e imagen, luego construir los recursos de Filament para marcas, productos y servicios; verificar las pruebas y la creación real en el panel.
- [x] 2.4 Escribir primero la prueba de que las taxonomías sembradas alimentan los filtros, luego crear los seeders de materiales, formas y datos iniciales; verificar que la prueba pasa y el catálogo muestra las opciones.

## 3. Ajustes del sitio

- [x] 3.1 Escribir primero las pruebas de valores por defecto y guardado de ajustes (deben fallar), luego implementar la tabla y el modelo de fila única tipada; verificar las pruebas en verde.
- [x] 3.2 Escribir primero las pruebas de validación de teléfonos, enlaces y campos obligatorios (deben fallar), luego construir la página de ajustes en Filament; verificar que un número inválido no se guarda y las pruebas pasan.
- [x] 3.3 Escribir primero la prueba de caché e invalidación de ajustes (debe fallar), luego implementar el caché; verificar que el cambio se refleja en la siguiente lectura y la prueba pasa.
- [x] 3.4 Escribir primero las pruebas unitarias del formato de precio y del enlace de WhatsApp (deben fallar), luego implementar los helpers; verificar `1500 → 1,500 CUP` y la codificación del mensaje con las pruebas en verde.
- [x] 3.5 Escribir primero las pruebas de aviso de precios, aviso de privacidad y enlaces opcionales (deben fallar), luego implementarlos; verificar que una red social sin configurar no genera enlace y las pruebas pasan.

## 4. Catálogo público

- [x] 4.1 Escribir primero las pruebas de filtros combinados y de la URL resultante (deben fallar), luego implementar la ruta del catálogo con filtros por marca, tipo, género, material y forma, más paginación en el servidor; verificar las pruebas en verde.
- [x] 4.2 Escribir primero las pruebas de estados vacíos y de catálogo sin productos publicados (deben fallar), luego crear las vistas de cuadrícula y tarjeta; verificar las pruebas y la revisión visual.
- [x] 4.3 Escribir primero la prueba de búsqueda por URL sin JavaScript (debe fallar), luego implementar la búsqueda por marca y modelo con degradación sin JavaScript; verificar que devuelve los mismos resultados con y sin Livewire.
- [x] 4.4 Escribir primero las pruebas de metadatos, `canonical`, `sitemap.xml` y datos estructurados (deben fallar), luego implementarlos; verificar las pruebas y un rastreo manual.
- [x] 4.5 Documentar las rutas y parámetros del catálogo en el README; verificar que los ejemplos documentados responden.

## 5. Ficha de producto

- [x] 5.1 Escribir primero la prueba de campos opcionales omitidos (debe fallar), luego construir la ficha con marca, modelo, medidas, material, forma, género y descripción; verificar que la maquetación no se rompe y la prueba pasa.
- [x] 5.2 Escribir primero la prueba de cambio de variante y actualización de galería (debe fallar), luego implementar galería y selector de color con Livewire; verificar las pruebas y la interacción real.
- [x] 5.3 Escribir primero la prueba del enlace de WhatsApp con modelo y color (debe fallar), luego implementar el CTA; verificar el enlace generado y la prueba en verde.
- [x] 5.4 Escribir primero la prueba de que las imágenes se sirven con conversiones y carga diferida (debe fallar), luego configurar las conversiones WebP y miniaturas; verificar los archivos generados y los atributos de las imágenes.

## 6. Servicios

- [x] 6.1 Escribir primero las pruebas de servicios activos y de su orden (deben fallar), luego crear la página de servicios; verificar que los inactivos no aparecen y las pruebas pasan.
- [x] 6.2 Escribir primero las pruebas del CTA por servicio y de ausencia de precios (deben fallar), luego implementarlas; verificar que el mensaje menciona el servicio, no aparece ningún importe y las pruebas pasan.

## 7. Tienda y contacto

- [x] 7.1 Escribir primero las pruebas de dirección completa, referencia entre calles y horarios con días cerrados (deben fallar), luego construir el bloque de tienda; verificar las pruebas en verde.
- [x] 7.2 Escribir primero la prueba de que la carga inicial no solicita mapas de terceros (debe fallar), luego añadir la referencia de mapa estática y el enlace externo; verificar el HTML y la prueba.
- [x] 7.3 Escribir primero las pruebas de etiqueta accesible y foco por teclado del botón flotante (deben fallar), luego implementarlo; verificar las pruebas y que no cubre acciones en 360 px.
- [x] 7.4 Escribir primero las pruebas de los enlaces `tel:` y `t.me` (deben fallar), luego añadirlos; verificar que abren el destino correcto y las pruebas pasan.

## 8. Identidad y rendimiento

- [ ] 8.1 Definir los tokens de color y tipografía en Tailwind, auto-hospedar las fuentes e integrar el sello «20 20»; verificar el build y una revisión visual en móvil y escritorio.
- [ ] 8.2 Aplicar accesibilidad (foco visible, etiquetas, textos alternativos y contraste) y verificar con Lighthouse una puntuación de accesibilidad de 90 o más.
- [ ] 8.3 Optimizar el peso de la home por debajo de 500 KB sin imágenes diferidas; verificar con Lighthouse en red limitada y con el informe de peso.

## 9. Despliegue y verificación

- [ ] 9.1 Preparar localmente la configuración de producción (variables de ejemplo, cachés de Laravel y respaldo de almacenamiento si `storage:link` falla) y verificar en un staging local; no ejecutar ninguna acción en cPanel.
- [ ] 9.2 Redactar la instrucción manual para la entrada de cron de `php artisan schedule:run`, incluyendo comando, ruta y frecuencia; verificar el texto y entregarlo al usuario para que lo cree en cPanel.
- [ ] 9.3 Redactar la guía de despliegue y reversión manual a la release anterior; verificar los pasos con el usuario en staging, sin que el agente ejecute nada en cPanel.
- [ ] 9.4 Ejecutar la suite completa, registrar los resultados al 100% en `docs/verificacion.md` y verificar `openspec validate add-showcase-site --strict` sin errores; no cerrar el cambio si alguna prueba falla.

## Workflow follow-up

- Archivar el cambio cuando la revisión del negocio esté aprobada y la suite completa esté documentada en verde.
- Verificar el resultado archivado y las capacidades consolidadas en `openspec/specs/`.
