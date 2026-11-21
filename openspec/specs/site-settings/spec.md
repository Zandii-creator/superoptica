# site-settings Specification

## Purpose
Permite al negocio mantener actualizada la identidad, ubicación, horarios, contactos, moneda y textos del sitio desde el panel, sin intervención técnica ni despliegues.

## Requirements

### Requirement: Configuración editable del sitio
El panel SHALL permitir editar la identidad (nombre, eslogan, favicon), la ubicación, los horarios, los canales de contacto, las redes sociales, el enlace del mapa, la moneda y los textos de la página de inicio.

#### Scenario: Edición de un ajuste
- **WHEN** el administrador modifica el eslogan
- **THEN** el cambio se guarda y queda disponible para el sitio público

### Requirement: Reflejo inmediato en el sitio público
Los cambios guardados en el panel SHALL reflejarse en el sitio público en la siguiente carga de página, sin necesidad de despliegue.

#### Scenario: Cambio de horario
- **WHEN** el administrador guarda un horario nuevo
- **THEN** el sitio público muestra el horario actualizado en la siguiente visita

### Requirement: Validación de datos
El panel SHALL rechazar ajustes obligatorios vacíos o con formato inválido, como números de teléfono no válidos o enlaces mal formados.

#### Scenario: Número inválido
- **WHEN** el administrador guarda un número de WhatsApp con formato inválido
- **THEN** el panel muestra un error y no guarda el cambio

#### Scenario: Campo obligatorio vacío
- **WHEN** el administrador intenta guardar sin dirección de tienda
- **THEN** el panel impide guardar y señala el campo faltante

### Requirement: Fuente única de la moneda y el formato de precio
El sistema SHALL tomar de la configuración la etiqueta de moneda y el formato de precio, de modo que catálogo y ficha muestren siempre «CUP», coma de miles y sin decimales.

#### Scenario: Consistencia de precios
- **WHEN** el negocio revisa precios en catálogo y en ficha
- **THEN** ambos usan el mismo formato configurado

### Requirement: Textos legales y avisos
El sitio SHALL mostrar el aviso de precios referenciales y el aviso de privacidad, y ambos SHALL ser editables desde el panel.

#### Scenario: Aviso de precios
- **WHEN** el visitante consulta un precio
- **THEN** encuentra el aviso de que el precio es referencial y está en moneda nacional

### Requirement: Acceso restringido al panel
Solo usuarios administradores autenticados SHALL poder modificar la configuración del sitio.

#### Scenario: Acceso no autorizado
- **WHEN** un visitante sin sesión intenta abrir el módulo de ajustes
- **THEN** el sistema le niega el acceso y lo redirige al inicio de sesión

### Requirement: Valores por defecto
El sitio SHALL funcionar con valores por defecto cuando un ajuste opcional no esté configurado, sin mostrar campos vacíos ni enlaces rotos.

#### Scenario: Red social sin configurar
- **WHEN** no se ha configurado Instagram
- **THEN** el enlace a Instagram no se muestra en el sitio
