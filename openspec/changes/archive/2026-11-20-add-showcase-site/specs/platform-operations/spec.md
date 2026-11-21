# Spec Delta

## Purpose

Define las restricciones de operación y despliegue del sistema en cPanel, incluido el uso exclusivo de la base de datos `espejuelos` y la ejecución manual de cualquier cambio fuera de ella.

## ADDED Requirements

### Requirement: Base de datos exclusiva
El sistema SHALL operar exclusivamente sobre la base de datos `espejuelos`. Las migraciones, consultas y tareas de mantenimiento MUST NOT alterar ni consultar de forma destructiva otras bases de datos del servidor.

#### Scenario: Ejecución de migraciones
- **WHEN** se ejecuta una migración del proyecto
- **THEN** solo se modifican tablas de la base de datos `espejuelos`

#### Scenario: Configuración de conexión
- **WHEN** se configura el acceso a datos
- **THEN** la aplicación define una sola base, `espejuelos`, sin conexiones adicionales a otras bases

### Requirement: Operaciones de cPanel manuales
Cualquier cambio en cPanel distinto de las operaciones sobre `espejuelos` —archivos, dominios, cron, correo, SSL o versión de PHP— SHALL entregarse como instrucción escrita para que el usuario la ejecute manualmente.

#### Scenario: Configuración de una tarea programada
- **WHEN** la implementación requiere una entrada de cron
- **THEN** se entrega el comando, la ruta y la frecuencia por escrito, y el usuario la crea en cPanel

#### Scenario: Despliegue de una nueva versión
- **WHEN** hay que publicar cambios en el servidor
- **THEN** se documentan los pasos manuales y el agente no los ejecuta

### Requirement: Sin automatización de despliegue
El proyecto MUST NOT incluir scripts, tareas ni automatizaciones que ejecuten despliegues o cambios de configuración en cPanel.

#### Scenario: Revisión de automatizaciones
- **WHEN** se revisan los scripts del proyecto
- **THEN** ninguno ejecuta acciones sobre cPanel ni sobre otras bases de datos

#### Scenario: Compilación de assets
- **WHEN** se preparan los assets para producción
- **THEN** se compilan en local y se suben manualmente, sin automatización en el servidor

### Requirement: Pruebas antes de la implementación
Cada tarea de desarrollo SHALL comenzar por una prueba unitaria o funcional que describa el comportamiento esperado y SHALL fallar antes de implementar la solución.

#### Scenario: Inicio de una tarea
- **WHEN** comienza una tarea de desarrollo
- **THEN** existe una prueba que describe el comportamiento y falla con el código actual

#### Scenario: Fin de una tarea
- **WHEN** la implementación de la tarea está completa
- **THEN** la prueba correspondiente pasa y no rompe las pruebas existentes

### Requirement: Cierre con verificación documentada
La implementación SHALL considerarse terminada solo cuando la suite completa pase al 100% y los resultados queden documentados en el repositorio.

#### Scenario: Cierre del cambio
- **WHEN** se propone cerrar la implementación
- **THEN** la suite completa pasa y el resultado está registrado en la documentación del proyecto

#### Scenario: Suite con fallos
- **WHEN** alguna prueba falla
- **THEN** el cambio no se considera terminado ni se archiva
