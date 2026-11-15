# Spec Delta

## Purpose

Reúne la información de la tienda física y los canales de contacto para que el visitante pueda visitar el local o iniciar una conversación con el negocio sin fricción.

## ADDED Requirements

### Requirement: Información de la tienda física
El sitio SHALL mostrar la dirección completa —calle, entre calles, ciudad y país— junto con una referencia visual de la ubicación.

#### Scenario: Visitante busca la tienda
- **WHEN** el visitante llega al bloque de tienda
- **THEN** ve la dirección completa con la referencia entre calles

### Requirement: Horarios de atención
El sitio SHALL mostrar el horario de cada día de la semana y SHALL indicar los días sin servicio como cerrados.

#### Scenario: Consulta de horarios
- **WHEN** el visitante revisa los horarios
- **THEN** ve el horario de cada día y los días cerrados

### Requirement: Mapa sin carga pesada
El sitio SHALL mostrar una referencia de mapa y un enlace para abrir la ubicación en un servicio externo, y SHALL NOT cargar mapas de terceros durante la carga inicial de la página.

#### Scenario: Apertura del mapa
- **WHEN** el visitante pulsa «Abrir en el mapa»
- **THEN** se abre la ubicación en el servicio externo configurado

#### Scenario: Carga de la página
- **WHEN** la página de contacto se carga
- **THEN** no se realizan solicitudes a servicios de mapas de terceros

### Requirement: Canales de contacto
El sitio SHALL ofrecer WhatsApp, teléfono fijo y Telegram como canales, cada uno con un enlace accionable que abra la aplicación o el marcador correspondiente.

#### Scenario: Llamada telefónica
- **WHEN** el visitante pulsa el teléfono fijo
- **THEN** el dispositivo inicia una llamada a ese número

#### Scenario: Contacto por Telegram
- **WHEN** el visitante pulsa Telegram
- **THEN** se abre la conversación con el usuario configurado

### Requirement: Botón flotante de WhatsApp
El sitio SHALL mostrar un botón flotante de WhatsApp en todas las páginas públicas, con etiqueta accesible, y SHALL NOT cubrir contenido interactivo en pantallas pequeñas.

#### Scenario: Botón visible en móvil
- **WHEN** el visitante navega desde un teléfono
- **THEN** el botón flotante permanece accesible sin tapar acciones principales

#### Scenario: Navegación por teclado
- **WHEN** el visitante navega con teclado
- **THEN** puede enfocar y activar el botón flotante

### Requirement: Mensajes de WhatsApp con contexto
Las acciones de WhatsApp SHALL incluir un mensaje precargado que identifique el producto o servicio consultado.

#### Scenario: Consulta con contexto
- **WHEN** el visitante inicia una conversación desde una ficha o servicio
- **THEN** el mensaje inicial indica qué producto o servicio le interesa

### Requirement: Datos de contacto vigentes
El sitio SHALL mostrar los datos de contacto y la ubicación configurados en el panel, sin requerir despliegue para actualizarlos.

#### Scenario: Cambio de número
- **WHEN** el negocio actualiza el número de WhatsApp en el panel
- **THEN** las acciones de WhatsApp del sitio usan el número nuevo
