# Manual de usuario — SuperÓptica 20 20

Guía para el personal del negocio. Explica cómo se usa el sitio público y cómo se administra el contenido desde el panel.

> **Importante:** el sitio es una vitrina. No hay cuentas de cliente, carrito, pagos ni pedidos. La compra se concreta en la tienda física o por WhatsApp, y los precios son referenciales en moneda nacional (CUP).

## 1. Lo que conviene saber

- **El sitio muestra, no vende:** el visitante consulta modelos y contacta por WhatsApp, teléfono o Telegram.
- **Los precios se escriben en CUP**, como números enteros y sin decimales. El sitio los muestra con coma de miles: `1500` se ve como `1,500 CUP`.
- **Un producto solo aparece en el catálogo si está activo y tiene fecha de publicación.** Si falta cualquiera de las dos condiciones, queda oculto.
- **Los cambios del panel se ven en la siguiente carga de la página pública.** No hace falta avisar a nadie ni volver a publicar el sitio.
- **El panel es solo para administradores.** No hay registro de usuarios ni recuperación de contraseña.

## 2. El sitio público

### 2.1 Portada

La portada (`/`) reúne lo principal del negocio:

1. **Encabezado** con el sello “20 20”, el nombre del negocio y el menú: Inicio, Catálogo, Servicios y Tienda.
2. **Portada principal** con el título, el subtítulo y dos botones: “Ver el catálogo” y “Consultar por WhatsApp”.
3. **Modelos destacados:** hasta tres productos marcados como destacados.
4. **Servicios:** los servicios activos, con su descripción.
5. **Tienda:** dirección, horario y enlace al mapa.
6. **Pie de página:** dirección, horarios, canales de contacto, aviso de precios y aviso de privacidad si está configurado.

El botón flotante de WhatsApp aparece en todas las páginas públicas, también en el teléfono.

### 2.2 Catálogo

En `/catalogo` el visitante ve los productos publicados y activos, en una cuadrícula de 12 por página.

**Buscar:** el campo “Marca o modelo” acepta texto libre. El ejemplo de la interfaz es `82278`. La búsqueda encuentra coincidencias en el modelo y en el nombre de la marca. Funciona con y sin JavaScript.

**Filtrar:** se puede combinar Marca, Tipo, Género, Material y Forma. Los filtros se aplican con el botón “Aplicar filtros” y se pueden quitar uno por uno desde las etiquetas de “Filtros activos” o todos con “Limpiar filtros”.

**Compartir:** los filtros y la página quedan en la dirección del navegador. Si se copia la dirección, la otra persona ve el mismo resultado. Ejemplo:

```
/catalogo?marca=ray-ban&tipo=monturas&genero=unisex
```

**Sin resultados:** si la combinación no tiene productos, aparece un mensaje con la opción de limpiar los filtros y contactar por WhatsApp.

**Orden:** primero los destacados, después los publicados más recientemente.

### 2.3 Ficha de producto

Cada producto tiene su dirección propia: `/catalogo/{url}`. La ficha muestra:

- Galería de imágenes. Si hay varias, se cambia con las miniaturas.
- Marca y tipo de producto.
- Nombre comercial: marca + modelo.
- Precio referencial con el formato “Desde 1,500 CUP” y el aviso de moneda nacional.
- Descripción corta.
- **Colores disponibles**, si el producto tiene variantes. Al elegir un color cambian la galería y el mensaje de WhatsApp.
- Ficha técnica: marca, modelo, material, forma, género y medidas en formato lente-puente-varilla. Los datos que no estén cargados no se muestran.
- Descripción completa, si existe.
- Botón “Consultar por WhatsApp”. El mensaje incluye la marca, el modelo y el color elegido.
- Aviso: “La compra se concreta en la tienda física o por WhatsApp.”

Si el producto no tiene variantes, no aparece la sección de colores. Si no tiene medidas, no aparece el bloque de medidas.

### 2.4 Servicios

En `/servicios` se muestran los servicios activos, en el orden definido en el panel. Cada uno tiene nombre, descripción, icono y un botón “Consultar por WhatsApp” cuyo mensaje menciona el servicio.

Los servicios **no muestran precios**. La descripción debe explicar qué resuelve el servicio y a quién está dirigido, sin prometer resultados clínicos.

### 2.5 Tienda y contacto

En `/tienda` el visitante encuentra:

- Dirección completa: calle y número, entre calles, ciudad, provincia y país.
- Referencia visual de la ubicación y botón “Abrir en el mapa”, si hay enlace configurado.
- Horario de cada día. Los días sin horario se muestran como “Cerrado”.
- Canales de contacto: WhatsApp, teléfono fijo y Telegram; además, Instagram y Facebook si están configurados.
- Aviso de precios y aviso de privacidad en el pie de página.

Los enlaces de WhatsApp abren la conversación con un mensaje ya escrito. Los de teléfono abren el marcador y los de Telegram la conversación con el usuario configurado.

## 3. El panel de administración

### 3.1 Entrar

1. Abre `/admin` en el navegador.
2. Escribe el **correo** del administrador en el campo de correo. No escribas el nombre: el nombre no sirve para entrar.
3. Escribe la contraseña y pulsa el botón de acceso.

El correo y la contraseña los define quien instala el sitio (`ADMIN_EMAIL` y `ADMIN_PASSWORD`). Si no los conoces, pídelos al responsable técnico. El panel no tiene “olvidé mi contraseña”.

### 3.2 Organización del panel

El menú lateral agrupa las tareas:

| Grupo | Opciones | Para qué |
|---|---|---|
| Catálogo | Marcas, Productos | Crear y mantener las monturas y sus datos |
| Contenido | Servicios, Ajustes del sitio | Servicios ópticos y datos generales del negocio |

Al entrar aparece el panel de inicio con el usuario conectado. Las listas permiten buscar, editar y eliminar registros.

### 3.3 Marcas

**Ruta:** Catálogo → Marcas.

| Campo | Qué poner |
|---|---|
| Nombre | Nombre comercial de la marca, por ejemplo `Ray-Ban`. |
| URL | Se genera a partir del nombre. Debe ser única; se usa en los filtros del catálogo. |
| Logotipo | Imagen opcional. |
| Activa | Si se desactiva, la marca deja de aparecer en los filtros del catálogo. |

**Importante:** desactivar una marca **no oculta sus productos**. Para ocultar un producto, desactívalo desde Productos. Evita eliminar una marca que todavía tenga productos; si ya no se usa, desactívala.

### 3.4 Productos

**Ruta:** Catálogo → Productos. Pulsa la acción de crear para abrir el formulario.

#### Datos del producto

| Campo | Qué poner |
|---|---|
| Marca | Obligatoria. Debe existir antes en Marcas. |
| Modelo | Obligatorio. Es el nombre que se ve en la tarjeta y en la ficha. |
| URL | Se genera a partir del modelo. Debe ser única. Si la cambias, los enlaces antiguos dejan de funcionar. |
| Tipo | Monturas, Espejuelos, Lentes de contacto o Líquidos. |
| Género | Unisex, Femenino, Masculino o Infantil. |
| Precio desde (CUP) | Obligatorio. Número entero, sin símbolos ni comas. Ejemplo: `1500`. |

#### Características

- **Material:** se elige de la lista creada previamente (Acetato, Metal, Titanio, TR90, Acero inoxidable).
- **Forma:** Rectangular, Redonda, Cuadrada, Aviador o Gato.
- **Medidas:** Lente, Puente y Varilla, en milímetros. Son opcionales; si falta una, el bloque de medidas no se muestra.

#### Descripción

- **Descripción corta:** aparece en la tarjeta del catálogo. Máximo 300 caracteres.
- **Descripción completa:** aparece al final de la ficha. Puede llevar varios párrafos.

#### Imágenes

- Se suben hasta **8 imágenes** en la galería del producto.
- **La primera imagen es la portada** de la tarjeta.
- Se recomienda una foto frontal sobre fondo claro y, si se puede, una de perfil y un detalle.

#### Variantes de color

Cada producto puede tener varios colores. En cada variante:

| Campo | Qué poner |
|---|---|
| Color | Obligatorio. Ejemplo: `Negro`, `Tortuga`, `Azul`. |
| Muestra | Color visual opcional para el círculo que acompaña al nombre. |
| Precio de esta variante (CUP) | Opcional. Si se deja vacío, se usa el precio del producto. |
| Orden | Número para ordenar los colores. Menor primero. |
| Fotos de este color | Hasta 4 imágenes. Si el color tiene fotos, la ficha las muestra al seleccionarlo. |

El precio que se muestra en el catálogo y en la ficha es **el más bajo de las variantes con precio**; si ninguna variante tiene precio, se usa el “Precio desde” del producto. Siempre se muestra con el prefijo “Desde”.

#### Publicación

| Campo | Efecto |
|---|---|
| Activo | Si se desactiva, el producto no aparece en el sitio, aunque tenga fecha de publicación. |
| Destacado en la home | Si se activa, el producto puede aparecer en “Modelos destacados”. La portada muestra como máximo tres. |
| Publicado el | Fecha y hora desde la que el producto es visible. **Si está vacío, el producto no aparece.** |

Regla completa de visibilidad: el producto debe estar **activo**, tener **fecha de publicación** y que esa fecha **ya haya pasado**. Si programas una fecha futura, aparecerá automáticamente en ese momento.

### 3.5 Servicios

**Ruta:** Contenido → Servicios.

| Campo | Qué poner |
|---|---|
| Nombre | Nombre del servicio, por ejemplo `Examen visual`. |
| URL | Se genera a partir del nombre. Debe ser única. |
| Descripción | Obligatoria. Explica qué resuelve y a quién está dirigido. |
| Icono | Opcional. Valores disponibles: `eye` (vista), `wrench` (ajuste) y `glasses` (graduación). Si se deja vacío se usa `glasses`. |
| Orden | Número para ordenar los servicios. Menor primero. |
| Activo | Si se desactiva, el servicio no aparece en el sitio. |

Los servicios **no llevan precio**. No escribas importes en la descripción.

### 3.6 Ajustes del sitio

**Ruta:** Contenido → Ajustes del sitio. Al guardar, el cambio se refleja en la siguiente carga del sitio público.

| Sección | Campos y notas |
|---|---|
| Identidad | Nombre del negocio (obligatorio), eslogan y favicon. |
| Ubicación de la tienda | Calle y número (obligatorio), entre calles, ciudad, provincia y país. |
| Horarios | Un campo por día. **Deja vacío el día que permanezca cerrado**; el sitio mostrará “Cerrado”. |
| Contacto | WhatsApp (obligatorio), teléfono fijo y usuario de Telegram. |
| Redes sociales y mapa | Instagram, Facebook, enlace del mapa e imagen de referencia. Los campos vacíos no generan enlaces. |
| Moneda y avisos | Etiqueta de moneda (`CUP`), aviso de precios (obligatorio) y aviso de privacidad. |
| Portada y SEO | Título y subtítulo de portada; título y descripción para buscadores. Si se dejan vacíos, el sitio usa los textos por defecto. |

**Formato de los teléfonos:** solo dígitos, con código de país y sin espacios ni guiones. Ejemplo: `5355123456`. El WhatsApp es obligatorio; el teléfono fijo es opcional. El usuario de Telegram se escribe **sin `@`**.

**Validaciones:** el panel no guarda un WhatsApp con formato inválido, una dirección vacía ni un enlace mal formado. Corrige el campo que marque el error y vuelve a guardar.

## 4. Tareas frecuentes

### 4.1 Publicar un producto nuevo

1. Comprueba que la marca existe en Catálogo → Marcas. Si no, créala.
2. Entra en Catálogo → Productos y crea un producto.
3. Completa Marca, Modelo, Tipo, Género y Precio desde.
4. Añade material, forma y medidas si los conoces.
5. Escribe la descripción corta y la completa.
6. Sube las imágenes. La primera será la portada.
7. Añade los colores con sus fotos y precios, si aplica.
8. Activa **Activo**, escribe la fecha y hora actual en **Publicado el** y guarda.
9. Abre `/catalogo`, busca el modelo y comprueba la ficha y el botón de WhatsApp.

### 4.2 Destacar un producto en la portada

1. Edita el producto.
2. Activa **Destacado en la home**.
3. Confirma que está activo y publicado.
4. Guarda y abre `/`. La portada muestra como máximo tres destacados.

### 4.3 Ocultar un producto sin borrarlo

1. Edita el producto.
2. Desactiva **Activo**.
3. Guarda. El producto desaparece del catálogo, de la búsqueda y de la portada, pero se conserva en el panel.

### 4.4 Cambiar un precio

1. Edita el producto.
2. Cambia **Precio desde (CUP)** o el precio de la variante correspondiente.
3. Guarda y revisa la ficha. Recuerda que se muestra el precio más bajo de las variantes con precio.

Escribe solo números enteros: `1500`, no `1,500` ni `1500.00`.

### 4.5 Actualizar el WhatsApp

1. Entra en Contenido → Ajustes del sitio → Contacto.
2. Escribe el número con código de país y solo dígitos.
3. Guarda.
4. Abre la portada, una ficha y `/tienda`; prueba un botón de WhatsApp.

### 4.6 Cerrar un día o cambiar el horario

1. Entra en Ajustes del sitio → Horarios.
2. Escribe el horario del día, por ejemplo `9:00 a.m. - 5:00 p.m.`.
3. Para cerrar un día, deja su campo vacío.
4. Guarda y revisa `/tienda`.

### 4.7 Añadir o reordenar un servicio

1. Entra en Contenido → Servicios y crea el servicio o edita uno existente.
2. Escribe nombre y descripción; elige icono y orden.
3. Actívalo y guarda.
4. Revisa `/servicios` y la portada.

## 5. Errores y dudas frecuentes

| Situación | Qué revisar |
|---|---|
| “Este campo es obligatorio” | Completa el campo marcado. Los obligatorios son nombre, URL y precios según el formulario. |
| El WhatsApp no se guarda | Usa solo dígitos, con código de país, entre 8 y 15. Sin espacios, guiones ni `+` obligatorio. |
| La URL está repetida | Cambia la URL; debe ser única. |
| El producto no aparece en el catálogo | Debe estar **Activo** y tener **Publicado el** con fecha pasada. |
| El producto no sale en la portada | Debe estar activo, publicado y marcado como **Destacado**. La portada muestra hasta tres. |
| El producto no tiene fotos | Sube imágenes en la galería del producto o en la variante. La primera es la portada. |
| El cambio no se ve en el sitio | Recarga la página. Los ajustes se reflejan en la siguiente carga; si persiste, avisa al responsable técnico. |
| No puedo entrar al panel | Escribe el **correo**, no el nombre. Confirma mayúsculas y contraseña. No hay recuperación automática. |
| Un servicio no aparece | Debe estar **Activo**. Revisa también el orden. |
| No puedo borrar una marca | Probablemente tiene productos asociados. Desactívala en lugar de borrarla. |
| El precio se ve raro | Escribe el monto como entero, sin comas, puntos ni símbolos. |
| La imagen no sube | Usa una imagen más ligera (JPG o PNG) y vuelve a intentarlo. |

## 6. Buenas prácticas de contenido

- **Fotos:** fondo neutro, buena luz y la montura centrada. La primera impresión es la tarjeta del catálogo.
- **Nombres:** usa la marca real y el modelo tal como lo conoce el cliente.
- **Descripciones:** la corta resume; la completa explica materiales, comodidad y para quién es.
- **Precios:** mantenlos al día. Si cambia el precio de una variante, el “Desde” puede cambiar.
- **Servicios:** describe qué se hace y a quién ayuda, sin prometer resultados clínicos.
- **Textos:** usa el español de Cuba y los términos del negocio: espejuelos, monturas, lentes de contacto.
- **Enlaces:** revisa que WhatsApp, teléfono, Telegram y mapa abran el destino correcto después de cambiarlos.

## 7. De dónde sale este manual

Este manual traduce las specs del sitio a tareas concretas.

| Sección | Capability |
|---|---|
| 2.1 Portada, 2.2 Catálogo, 2.3 Ficha | `product-catalog` |
| 2.4 Servicios | `services` |
| 2.5 Tienda y contacto | `store-contact` |
| 3.2 a 3.6 Panel y ajustes | `site-settings` |
| Tono, identidad y accesibilidad | `brand-experience` |
| Operación del servidor | `platform-operations` (ver [manual de operación](manual-operacion.md)) |
