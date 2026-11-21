# brand-experience Specification

## Purpose
Define la identidad visual, el tono de voz y la calidad de experiencia del sitio para transmitir optimismo, transparencia, limpieza y futurismo en cada página.

## Requirements

### Requirement: Paleta blanco y azul
El sitio SHALL usar el blanco como color dominante de fondo, el azul como color de acento para acciones y detalles, y un azul profundo para el texto principal, manteniendo contraste suficiente en todo el contenido.

#### Scenario: Contraste de acciones
- **WHEN** el visitante lee un botón o enlace azul sobre fondo blanco
- **THEN** el contraste cumple el mínimo de accesibilidad definido

### Requirement: Sello de marca «20 20»
El sitio SHALL incorporar el sello «20 20» como recurso gráfico de marca en la página de inicio y en el favicon, sin depender de un logotipo definitivo.

#### Scenario: Identidad en la home
- **WHEN** el visitante abre la página de inicio
- **THEN** el sello «20 20» aparece como elemento de identidad visible

### Requirement: Tono cubano
Los textos de interfaz SHALL estar en español cubano y SHALL usar «espejuelos», «monturas», «lentes de contacto» y «líquidos» como vocabulario principal.

#### Scenario: Texto de catálogo
- **WHEN** el visitante recorre el catálogo
- **THEN** los textos usan el vocabulario cubano definido

### Requirement: Moneda siempre identificada
Todo importe mostrado en el sitio SHALL identificar la moneda como CUP y SHALL NOT usar el símbolo «$» de forma ambigua.

#### Scenario: Precio en una tarjeta
- **WHEN** el visitante ve un precio
- **THEN** el importe incluye la unidad CUP

### Requirement: Experiencia móvil primero
El sitio SHALL ser usable desde pantallas de 360 píxeles de ancho, sin desplazamiento horizontal ni contenido cortado, y SHALL priorizar la navegación táctil.

#### Scenario: Navegación en pantalla pequeña
- **WHEN** el visitante abre el sitio en un teléfono de 360 píxeles
- **THEN** puede navegar y leer el contenido sin desplazamiento horizontal

### Requirement: Rendimiento en conexiones lentas
La carga inicial de la página de inicio SHALL mantenerse por debajo de 500 KB sin contar las imágenes diferidas, y las imágenes SHALL cargarse de forma diferida y en formato comprimido.

#### Scenario: Carga de la home
- **WHEN** el visitante abre la página de inicio en una conexión lenta
- **THEN** el contenido principal aparece sin esperar a todas las imágenes

### Requirement: Accesibilidad
El sitio SHALL cumplir un nivel de accesibilidad equivalente a WCAG 2.1 AA en contraste, navegación por teclado, foco visible, textos alternativos y etiquetas de formularios.

#### Scenario: Uso con teclado
- **WHEN** el visitante navega solo con teclado
- **THEN** puede recorrer enlaces, botones y formularios con foco visible

#### Scenario: Imagen de producto
- **WHEN** un lector de pantalla recorre una tarjeta de producto
- **THEN** la imagen tiene un texto alternativo descriptivo

### Requirement: Presencia en búsquedas locales
Cada página pública SHALL tener título y descripción propios, y el sitio SHALL exponer datos estructurados de negocio local y un mapa del sitio para motores de búsqueda.

#### Scenario: Búsqueda local
- **WHEN** alguien busca «espejuelos Ciego de Ávila»
- **THEN** el sitio puede aparecer con metadatos que indican la ubicación y el servicio
