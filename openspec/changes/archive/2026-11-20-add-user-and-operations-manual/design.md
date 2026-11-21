# Design

## Context

Ver `proposal.md` para la motivación. El proyecto ya cuenta con:

- Seis specs en `openspec/changes/add-showcase-site/specs/` que describen el comportamiento del sitio y del panel.
- `README.md` con stack, rutas públicas y parámetros del catálogo.
- `docs/despliegue.md` con el procedimiento técnico completo de cPanel.
- `docs/verificacion.md` con el resultado de la suite y la publicación.

No existe todavía documentación orientada a quien usa el sitio ni a quien lo opera día a día. El panel Filament y las páginas públicas están en español, y el personal del negocio no necesita leer código ni specs para trabajar.

Restricciones que condicionan la redacción:

- El manual de operación no puede proponer automatizaciones sobre cPanel ni tocar bases distintas de `espejuelos`.
- Los manuales deben poder leerse sin conexión y sin herramientas especiales.
- No deben incluir credenciales, claves ni datos sensibles reales.

## Goals / Non-Goals

**Goals:**

- Dos manuales independientes, uno por audiencia: personal del negocio (usuario) y responsable de la instalación (operación).
- Cada instrucción del manual de usuario corresponde a una acción real del sitio o del panel.
- Cada sección se puede rastrear hasta la spec que la respalda.
- El manual de operación cubre el ciclo completo: respaldo, publicación, verificación, incidencia y reversión, remitiendo a `docs/despliegue.md` para los comandos exactos.
- Un índice único (`docs/README.md`) que indique qué leer y en qué orden.

**Non-Goals:**

- Sustituir `docs/despliegue.md` o `docs/verificacion.md`.
- Documentar el código, la arquitectura interna o el esquema de base de datos para desarrolladores.
- Incluir capturas de pantalla que queden obsoletas con cada cambio visual.
- Crear documentación en inglés o multi-idioma.

## Decisions

### 1. Separar por audiencia, no por funcionalidad

`docs/manual-usuario.md` atiende a quien publica contenido y consulta el sitio; `docs/manual-operacion.md` atiende a quien administra el hosting. Una separación por funcionalidad obligaría a ambos públicos a leer secciones que no les corresponden.

- **Alternativa descartada**: un único manual con capítulos mezclados. Aumenta la longitud y diluye las responsabilidades.
- **Consecuencia**: el manual de usuario incluye una parte pública y una parte de panel; el de operación asume que el lector ya conoce el panel y se concentra en el servidor.

### 2. Las specs son la fuente; los manuales son la traducción operativa

Cada sección de los manuales cita la capability de origen (`product-catalog`, `services`, `store-contact`, `site-settings`, `brand-experience`, `platform-operations`). Al final de cada manual se incluye una tabla de trazabilidad sección → capability, para que el manual declare de dónde sale cada parte.

- **Alternativa descartada**: redactar los manuales desde el código. El código cambia con más frecuencia y describe detalles internos que el usuario no necesita.
- **Consecuencia**: los manuales no prometen comportamientos que las specs no respalden.

### 3. Estilo de instrucción: tarea, pasos y resultado esperado

Cada procedimiento se escribe con un título en forma de tarea (“Publicar un producto nuevo”), pasos numerados, el resultado visible y una nota de error frecuente cuando aplique. Se evita el lenguaje técnico innecesario y se usan los mismos nombres que muestra la interfaz (Marca, Modelo, Precio desde, Ajustes del sitio).

- **Alternativa descartada**: descripción pasiva de campos. No ayuda a completar una tarea.
- **Consecuencia**: el manual de usuario se puede usar como lista de comprobación.

### 4. Sin capturas de pantalla; referencias estables

Se describen las pantallas por su título, sus campos y la ruta (`/admin`, `/admin/ajustes`, `/catalogo`, `/tienda`). Las capturas envejecen con el rediseño y pesan en el repositorio; además, el sitio prioriza conexiones lentas.

- **Alternativa descartada**: capturas anotadas. Útiles al inicio, pero se desactualizan sin aviso.
- **Consecuencia**: si en el futuro se añaden imágenes, se guardarán en `docs/imagenes/` y se referenciarán desde el texto.

### 5. El manual de operación no duplica el procedimiento de despliegue

`docs/manual-operacion.md` describe qué hacer y cuándo (rutinas, decisiones, verificación, reversión), y enlaza a `docs/despliegue.md` para los comandos, rutas y particularidades del hosting. Las restricciones permanentes se repiten de forma explícita porque son reglas de seguridad, no detalles técnicos.

- **Alternativa descartada**: mover todo el contenido de despliegue al manual. Duplicaría una guía ya verificada y quedaría desincronizada.
- **Consecuencia**: el manual de operación es más corto y actúa como índice de decisiones; el anexo técnico sigue siendo la referencia de comandos.

### 6. Índice y enlaces desde el README

`docs/README.md` presenta la documentación en este orden: manual de usuario → manual de operación → despliegue → verificación. El `README.md` raíz gana una sección “Documentación” con los mismos enlaces. Los manuales se enlazan entre sí cuando un tema cruza audiencias (por ejemplo, el usuario que necesita reportar una incidencia).

- **Alternativa descartada**: dejar los manuales solo enlazados desde `docs/despliegue.md`. Quien llega nuevo no los encontraría.

## Risks / Trade-offs

- **Duplicación con `docs/despliegue.md`** → El manual de operación solo enlaza los comandos y repite únicamente las reglas de seguridad.
- **Exposición de datos sensibles** → Nunca se escriben contraseñas, claves ni correos reales; se usan marcadores como `ADMIN_EMAIL` y `ADMIN_PASSWORD`.
- **Exceso de detalle para el personal del negocio** → El manual de usuario se limita a tareas frecuentes; los casos excepcionales remiten al responsable técnico.
- **Deriva entre interfaz y texto** → Se usan los nombres visibles del panel y las rutas estables; las capturas se evitan a propósito.

## Migration Plan

No aplica: el cambio solo agrega documentación y no modifica el sitio ni el servidor. La publicación de los manuales no requiere desplegar la aplicación; basta con versionarlos en el repositorio.
