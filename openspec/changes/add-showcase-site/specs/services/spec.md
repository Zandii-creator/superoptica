# Spec Delta

## Purpose

Presenta los servicios ópticos que ofrece el negocio —examen visual, ajuste y graduación— como información de valor para el visitante, sin precios y con una vía directa de contacto.

## ADDED Requirements

### Requirement: Listado de servicios
El sitio SHALL mostrar los servicios activos con nombre, descripción e icono, en el orden definido por el negocio, y SHALL mantenerlos accesibles desde la navegación principal.

#### Scenario: Visitante consulta los servicios
- **WHEN** el visitante abre la sección de servicios
- **THEN** ve los servicios activos con su descripción

#### Scenario: Servicio inactivo
- **WHEN** un servicio está marcado como inactivo
- **THEN** no aparece en la sección pública

### Requirement: Servicios sin precio
La sección de servicios SHALL NOT mostrar precios, importes ni referencias monetarias.

#### Scenario: Servicio sin importe
- **WHEN** el visitante revisa un servicio
- **THEN** no ve ningún precio asociado

### Requirement: Contacto por servicio
Cada servicio SHALL ofrecer una acción de WhatsApp con un mensaje precargado que mencione el nombre del servicio.

#### Scenario: Consulta de un servicio
- **WHEN** el visitante pulsa la acción de contacto de «Examen visual»
- **THEN** se abre WhatsApp con un mensaje que menciona ese servicio

### Requirement: Orientación al servicio
Cada servicio SHALL indicar de forma breve qué resuelve y a quién está dirigido, sin prometer resultados clínicos ni sustituir la valoración profesional.

#### Scenario: Descripción del servicio
- **WHEN** el visitante lee un servicio
- **THEN** entiende qué resuelve y cómo solicitar atención
