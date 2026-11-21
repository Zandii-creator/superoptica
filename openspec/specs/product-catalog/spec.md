# product-catalog Specification

## Purpose
Define el catálogo público de espejuelos y monturas: cómo se organizan los productos, cómo se filtran y cómo se presenta su información y su precio referencial en moneda nacional.

## Requirements

### Requirement: Catálogo público de productos
El sitio SHALL mostrar únicamente productos publicados y activos, en una cuadrícula paginada con imagen, marca, modelo y precio referencial. Los productos en borrador o inactivos SHALL permanecer ocultos.

#### Scenario: Visitante abre el catálogo
- **WHEN** el visitante accede a la página del catálogo
- **THEN** ve solo productos activos, ordenados y paginados

#### Scenario: Producto en borrador
- **WHEN** un producto está marcado como borrador o inactivo
- **THEN** no aparece en el catálogo ni en los resultados de búsqueda

### Requirement: Filtrado y búsqueda por URL
El sitio SHALL permitir filtrar por marca, tipo, género, material y forma, y buscar por marca o modelo. Los filtros activos SHALL reflejarse en la URL para que el resultado sea compartible e indexable.

#### Scenario: Filtro por material
- **WHEN** el visitante selecciona el material Acetato
- **THEN** el catálogo muestra solo productos de ese material y la URL incluye el filtro

#### Scenario: Combinación sin resultados
- **WHEN** la combinación de filtros no devuelve productos
- **THEN** se muestra un estado vacío con la opción de limpiar los filtros

#### Scenario: Búsqueda por modelo
- **WHEN** el visitante busca "82278"
- **THEN** se muestran los productos cuyo modelo coincide

### Requirement: Precio referencial en moneda nacional
El sitio SHALL mostrar el precio con el formato «Desde 1,500 CUP», usando coma para los miles y sin decimales, y SHALL mostrar la línea «Precios en moneda nacional (CUP).» junto al precio en la ficha y al menos una vez en el catálogo.

#### Scenario: Precio de producto
- **WHEN** el precio mínimo de las variantes es 1500
- **THEN** la ficha muestra «Desde 1,500 CUP» y la línea de moneda nacional

#### Scenario: Variantes con precios distintos
- **WHEN** las variantes de un producto tienen precios diferentes
- **THEN** se muestra el precio más bajo con el prefijo «Desde»

### Requirement: Ficha de producto
La ficha SHALL mostrar marca, modelo, galería, variantes de color, medidas en formato lente-puente-varilla, material, forma, género y descripción. Los campos sin dato SHALL omitirse sin romper la maquetación.

#### Scenario: Ficha completa
- **WHEN** el visitante abre un producto con todos sus datos
- **THEN** ve la información completa y la galería de imágenes

#### Scenario: Producto sin medidas
- **WHEN** el producto no tiene medidas registradas
- **THEN** el bloque de medidas no se muestra y la ficha sigue siendo legible

### Requirement: Selección de variante de color
La ficha SHALL permitir seleccionar una variante de color y SHALL actualizar la imagen mostrada y el mensaje de WhatsApp según la selección.

#### Scenario: Cambio de color
- **WHEN** el visitante selecciona el color Negro
- **THEN** la galería muestra las imágenes de esa variante

#### Scenario: Variante por defecto
- **WHEN** el visitante abre la ficha sin elegir color
- **THEN** se preselecciona la primera variante disponible

### Requirement: Llamada a la acción por WhatsApp
La ficha SHALL ofrecer una acción de WhatsApp con un mensaje precargado que incluya el modelo y el color seleccionado.

#### Scenario: Consulta desde la ficha
- **WHEN** el visitante pulsa «Consultar por WhatsApp»
- **THEN** se abre WhatsApp con un mensaje que menciona el modelo y el color

### Requirement: Estados vacíos
El catálogo SHALL mostrar un mensaje comprensible cuando no existan productos publicados o cuando una búsqueda no tenga resultados.

#### Scenario: Catálogo sin productos
- **WHEN** no hay productos publicados
- **THEN** se muestra un mensaje informativo y una vía de contacto

### Requirement: Indexación de productos
Cada ficha SHALL tener un título y una descripción únicos, una URL legible y datos estructurados de producto para los motores de búsqueda.

#### Scenario: Rastreo de una ficha
- **WHEN** un buscador rastrea una ficha de producto
- **THEN** encuentra metadatos únicos y datos estructurados válidos
