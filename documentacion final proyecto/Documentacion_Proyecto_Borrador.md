# DOCUMENTO DEL PROYECTO: SINERGYFIT (Borrador Completo)

*A continuación se presenta el desarrollo completo de todos los capítulos exigidos por las normas. Este contenido está listo para que lo copies y pegues en tu plantilla de Word respetando los márgenes solicitados.*

---

## HOJA RESUMEN

**Título del proyecto:** SinergyFit - Fitness Tracker
**Autor:** [Tu Nombre]
**Fecha:** [Fecha actual]
**Tutor:** [Nombre de tu Tutor]
**Ciclo Formativo:** CFGS Desarrollo de Aplicaciones Web (DAW)
**Palabras clave:** Laravel, PHP, MySQL, Fitness, Gamificación, FullCalendar, Chart.js, MVC.

**Resumen del proyecto:**
SinergyFit es una aplicación web transaccional desarrollada bajo el patrón de arquitectura MVC utilizando el framework Laravel. Su objetivo principal es ofrecer a los usuarios una plataforma integral para el registro, seguimiento y gamificación de sus entrenamientos físicos (fuerza, caminata, carrera). El sistema cuenta con autenticación segura, cuadros de mando analíticos interactivos con Chart.js, calendarios dinámicos mediante FullCalendar.js y un sistema de logros automatizado para incentivar el progreso físico del usuario.

---

## ÍNDICE

1. Introducción
2. Estudio de la viabilidad
3. Alternativas y selección de la solución
4. Análisis y diseño de la solución adoptada
5. Implementación
6. Pruebas
7. Costes/Presupuesto
8. Conclusiones
9. Bibliografía
10. Glosario
11. Anexos

---

## 1. INTRODUCCIÓN

SinergyFit nace con la misión de solucionar el problema de dispersión y falta de análisis que sufren los usuarios al intentar registrar sus avances físicos. Muchas veces, las personas que entrenan dependen de cuadernos físicos o aplicaciones genéricas de notas que carecen de interactividad y capacidad de medición a largo plazo. 

El presente proyecto busca consolidar un entorno digital donde el usuario no solo registra datos básicos, sino que puede detallar cada sesión (Series, Repeticiones, Kilogramos) y obtener un feedback visual y tendencial inmediato. La motivación personal detrás de este desarrollo radica en la oportunidad de aplicar tecnologías modernas del ecosistema PHP (como Laravel y Eloquent ORM) y Javascript (Fetch API, DOM Scripting) en un producto de uso real. 

A lo largo del proyecto, se ha experimentado la evolución de escalar un sistema informático básico con código poco estructurado, hacia una arquitectura profesional, mantenible y escalable.

## 2. ESTUDIO DE LA VIABILIDAD

El desarrollo del proyecto es completamente viable a nivel técnico, económico y temporal.

- **Viabilidad Técnica:** Se dispone de los conocimientos necesarios en el framework Laravel, diseño de bases de datos relacionales y tecnologías front-end (Blade, HTML5, CSS3, JS). Los servidores web actuales (Apache/Nginx) soportan nativamente los requerimientos de la aplicación sin necesidad de configuraciones experimentales.
- **Viabilidad Económica:** Al utilizar herramientas de código abierto (Open Source) como PHP, MySQL, y librerías gratuitas bajo licencias MIT (Chart.js, FullCalendar), el coste de desarrollo asociado a la compra de licencias de software es estrictamente nulo.
- **Situación y viabilidad operativa:** El seguimiento manual tradicional conlleva pérdida de datos y desmotivación en el deporte. El diagnóstico de la situación actual demuestra que la digitalización y gamificación de este proceso resolverá esta problemática, aumentando la retención y adherencia al ejercicio físico de los posibles clientes del sistema.

## 3. ALTERNATIVAS Y SELECCIÓN DE LA SOLUCIÓN

Antes de iniciar el desarrollo, se plantearon distintas alternativas arquitectónicas:

1. **Desarrollo en PHP Nativo (Sin framework):** Fue la idea inicial. Sin embargo, quedó descartada rápidamente por su propensión al "código espagueti", la alta dificultad de mantenimiento a largo plazo y las vulnerabilidades de seguridad comunes que el desarrollador debe parchear manualmente (inyecciones SQL, ataques XSS, validaciones de formularios).
2. **Desarrollo SPA puro (Vue.js o React + API Laravel separada):** Esta alternativa suponía separar el frontend y backend en dos proyectos completamente distintos. Fue descartada por requerir un coste de tiempo mayor al disponible para la ejecución del proyecto, aunque se reconoce como la solución más robusta a futuro.
3. **Laravel con Blade y JavaScript Vanilla (Solución Adoptada):** Fue seleccionada como la mejor alternativa. Proporciona la seguridad de un framework back-end consolidado a nivel mundial, gestionando sesiones, middleware, validaciones y migraciones de forma automática. Al mismo tiempo, permite inyectar interactividad moderna (Single Page Application parcial) utilizando Fetch API y JavaScript Vanilla en las vistas que realmente lo requieren, como el Dashboard de analíticas o el motor de calendario.

## 4. ANÁLISIS Y DISEÑO DE LA SOLUCIÓN ADOPTADA

### Requisitos del sistema
- Sistema de usuarios privado mediante autenticación de alta seguridad.
- Registro pormenorizado de perfiles de usuario (Peso, Índice de grasa, Fotografías).
- Creación de entrenamientos dinámicos y granulares (Cardio vs Fuerza, permitiendo añadir infinitas series y repeticiones de forma dinámica).
- Representación visual asíncrona de los datos almacenados.
- Sistema automatizado de notificaciones y logros (gamificación).

### Diseño de Arquitectura
El sistema se diseña e implementa bajo el estricto patrón **Modelo-Vista-Controlador (MVC)**, el cual es el núcleo de Laravel:

- **Modelos (La capa de datos):** Entidades como `User`, `Entrenamiento`, `EntrenamientoDetalle`, `Objetivo` y `Logro`. Mediante el ORM de Laravel (Eloquent), se establecen relaciones complejas en la base de datos (relaciones Uno a Muchos, Muchos a Muchos) sin necesidad de escribir consultas SQL puras.
- **Controladores (La lógica de negocio):** Son los directores de la aplicación. Por ejemplo, `EntrenamientoController` gestiona la lógica de inserción masiva de registros en transacciones, `MetricaController` actúa como motor matemático para extraer porcentajes de crecimiento, y `AuthController` protege la integridad del acceso.
- **Vistas (La interfaz de usuario):** Desarrolladas con Blade, el motor de plantillas nativo, permitiendo heredar diseños maestros (Layouts) y modularizar componentes visuales (como barras de navegación o tarjetas de estadística).

## 5. IMPLEMENTACIÓN

La ejecución del proyecto se estructuró a través de una metodología basada en hitos o "Sprints":

- **Fase de Cimentación:** Migración de lógicas a Laravel, estableciendo el sistema de rutas seguras, middlewares de protección y archivos de migraciones para generar automáticamente las tablas estructurales (Usuarios, Entrenamientos).
- **Fase de Entidades y Modelos:** Creación del sistema de "Metas/Objetivos" mediante rutas RESTful completas. Además, se implementó el almacenamiento local (Storage) de imágenes para los avatares de usuario.
- **Fase Analítica (Dashboard):** Se implementaron peticiones asíncronas con JavaScript (Fetch). El controlador devuelve objetos JSON puros que son consumidos y renderizados en la pantalla de "Estadísticas" utilizando la librería visual Chart.js, evitando cargas de página pesadas.
- **Fase de Lógica Avanzada (Calendario y Detalles):** Integración del script FullCalendar.js para mapear los entrenamientos. Posteriormente se implementó la lógica más compleja: el DOM Scripting mediante Javascript Vanilla para permitir a los usuarios crear filas infinitas en sus entrenamientos (Series y repeticiones), los cuales son capturados e insertados masivamente por el servidor.
- **Fase Final (Gamificación):** Diseño de un sistema de logros y medallas automatizados. Creación de *Cron Jobs* (tareas programadas en servidor) para alertar mediante correos electrónicos a los usuarios inactivos y la creación de un motor matemático para mostrar las tendencias de rendimiento.

## 6. PRUEBAS

Se ejecutaron pruebas unitarias e integrales para certificar la calidad del software:

1. **Pruebas de Seguridad (Middlewares):** Se intentó forzar el acceso a URIs protegidas como `/estadisticas` sin tener una sesión activa. *Resultado:* Redirección automática inmediata al formulario de login.
2. **Pruebas de Integridad y Validación:** Envío malintencionado de formularios de perfil con valores numéricos negativos. *Resultado:* El controlador intercepta los datos gracias a las reglas de validación predefinidas y retorna un error amigable al usuario, sin colapsar el servidor.
3. **Pruebas Transaccionales (Base de Datos):** Inserción masiva de un entrenamiento con errores forzados a mitad del proceso. *Resultado:* El motor transaccional de Laravel ejecuta un *Rollback* automático, garantizando que no existan registros huérfanos en la base de datos.
4. **Pruebas de Tolerancia a Fallos (Asíncronas):** Caída forzada del endpoint de estadísticas de la API. *Resultado:* La página se carga correctamente y, en el cuadro donde debería ir la gráfica, Javascript intercepta el error `500 Server Error` para renderizar un aviso visual que no rompe la experiencia del usuario.

## 7. COSTES / PRESUPUESTO

A continuación, se detalla una simulación del presupuesto necesario para desplegar SinergyFit en un entorno de producción real durante su primer año de vida:

| Concepto de Gasto | Descripción Técnica | Coste Estimado |
| :--- | :--- | :--- |
| **Infraestructura Cloud** | Servidor VPS (Ej: DigitalOcean) para hosting de la app y base de datos (15€/mes x 12). | 180,00 € |
| **Dominio y Seguridad** | Compra de dominio `.com` y emisión de certificado SSL para protocolo HTTPS seguro. | 15,00 € |
| **Licencias Software** | Linux Ubuntu, MySQL, PHP 8, Laravel, Chart.js (Todo bajo licencias Open Source). | 0,00 € |
| **Desarrollo (Mano de Obra)** | 180 horas estimadas de Análisis, Programación, Testing e Implementación a razón de 30€/h. | 5.400,00 € |
| | **PRESUPUESTO TOTAL ESTIMADO** | **5.595,00 €** |

## 8. CONCLUSIONES

SinergyFit ha culminado en la creación de una plataforma robusta, escalable y visualmente atractiva. La transición y decisión de enmarcar el desarrollo dentro del ecosistema de Laravel ha demostrado ser crítica para el éxito del proyecto, otorgando al sistema una limpieza de código absoluta, facilidad de mantenimiento a largo plazo y medidas de seguridad intrínsecas (como la protección nativa contra CSRF e Inyección SQL).

Como líneas de futuro y ampliación, el proyecto está arquitectónicamente preparado para separar sus Controladores Web en una API REST dedicada. Esto posibilitaría el desarrollo paralelo de una aplicación móvil nativa (utilizando tecnologías como React Native o Flutter), así como la potencial integración de algoritmos de Inteligencia Artificial para recomendar rutinas personalizadas de fuerza basadas en el historial del usuario.

A nivel puramente personal, la conceptualización, desarrollo y finalización de este proyecto ha representado un desafío estimulante. Ha permitido aplicar y consolidar la inmensa mayoría de conocimientos adquiridos durante el Ciclo Formativo, validando la capacidad para analizar problemas complejos, diseñar bases de datos eficientes y programar soluciones productivas viables en el mundo laboral real.

## 9. BIBLIOGRAFÍA

*   Documentación Oficial del Framework Laravel (Versión actual). Recuperado de: *https://laravel.com/docs*
*   Manual de Referencia Oficial de MySQL 8.0. Recuperado de: *https://dev.mysql.com/doc/*
*   Documentación y Guías de Chart.js para visualización de datos. Recuperado de: *https://www.chartjs.org/docs/*
*   Documentación de FullCalendar para integración de eventos. Recuperado de: *https://fullcalendar.io/docs*
*   Guías de desarrollo web Mozilla Developer Network (MDN Web Docs) para Javascript Vanilla y Fetch API.

## 10. GLOSARIO

*   **MVC:** Modelo-Vista-Controlador. Patrón de arquitectura de software que separa los datos de una aplicación, la interfaz de usuario, y la lógica de control.
*   **ORM (Eloquent):** Object-Relational Mapping. Técnica que permite interactuar con la base de datos usando programación orientada a objetos en lugar de consultas SQL crudas.
*   **API REST / Endpoint:** Punto de acceso de un servicio web que recibe peticiones y devuelve datos, generalmente estructurados en formato JSON.
*   **Cron Job:** Tarea o programa definido en el sistema operativo del servidor web diseñado para ejecutarse de forma automática y silenciosa en intervalos temporales predefinidos.
*   **Fetch API:** Interfaz moderna de Javascript que permite realizar peticiones HTTP asíncronas para actualizar partes concretas de una web sin tener que recargar el navegador al completo.

## 11. ANEXOS

*(Nota para el alumno: En este apartado final deberás crear o adjuntar las páginas del "Manual de Instalación" y el "Manual de Usuario" como se te exigía en las normas).*
