---
marp: true
theme: gaia
_class: lead
paginate: true
backgroundColor: #1e1e2e
color: #cdd6f4
style: |
  section {
    font-family: 'Helvetica Neue', Arial, sans-serif;
    padding: 40px;
    font-size: 1.1em;
  }
  h1 {
    color: #a6e3a1;
  }
  h2 {
    color: #89b4fa;
  }
  footer {
    font-size: 0.5em;
    color: #6c7086;
  }
---

# 🏋️‍♂️ SINERGYFIT
## Sistema de Seguimiento y Gestión de Actividad Física

**Autor:** Juan Francisco Cortejosa Galindo  
**Tutor:** Marta Lopez Ron  
**Ciclo Formativo:** C.F.G.S. Desarrollo de Aplicaciones Web (DAW)  
**Fecha:** Mayo de 2026  
**Centro:** I.E.S. Trafalgar - Departamento de Informática

---

## 📌 1. Introducción y Motivación
### Origen y objetivos de SinergyFit

*   **Problema Real:** El sedentarismo y la falta de constancia frente a métodos de registro ineficientes (papel, notas de móvil, excels complejos).
*   **Propósito:** Ofrecer una herramienta digital, intuitiva y accesible para motivar el cambio de hábitos a largo plazo.
*   **Tres Pilares de la Aplicación:**
    1.  **Gestión de Entrenamientos:** Registro exhaustivo de series, repeticiones y cargas.
    2.  **Visualización:** Gráficos estadísticos dinámicos (Dashboard) para interpretar el progreso.
    3.  **Gamificación:** Sistema de rachas en tiempo real y vitrina de logros.
*   **Motivación Personal:** Integración de competencias DAW (Laravel + MySQL + Docker).

---

## 🔍 2. Estudio de Viabilidad y Alternativas
### Decisiones técnicas y justificación arquitectónica

*   **Viabilidad Triple:**
    *   **Técnica:** Stack robusto de código abierto alineado con los RA del ciclo.
    *   **Económica:** Software libre y nulos costes de licencias.
    *   **Operativa:** Diseño totalmente responsivo adaptable al entorno del gimnasio.
*   **Selección Tecnológica:**
    *   **Backend:** **Laravel 10** sobre PHP nativo por su estructura MVC, seguridad y Eloquent.
    *   **Frontend:** **Blade + Bootstrap 5 + Vanilla JS (Chart.js)** sobre SPAs tradicionales (React/Vue) para agilizar el desarrollo sin perder interactividad.
    *   **Despliegue:** **Docker** sobre XAMPP/Laragon para garantizar entornos estandarizados.

---

## 📊 3. Análisis y Diseño de la Solución
### Definición y estructuración del sistema

*   **Requisitos Funcionales (Backlog):** Registro de usuarios, logging de series de fuerza/cardio, dashboard con métricas dinámicas, metas personales y calendario interactivo.
*   **Requisitos No Funcionales:** Adaptabilidad responsiva, seguridad (Breeze + Middlewares) y velocidad de respuesta asíncrona.
*   **Diseño Relacional (Esquema en Español):**
    *   **Usuarios:** Datos de acceso, biometría y racha actual/mejor racha.
    *   **Entrenamientos + Detalles:** Cabeceras y ejercicios (series, repeticiones, cargas).
    *   **Objetivos, Métricas y Logros:** Progreso de metas y vitrina de medallas mediante tabla intermedia `logro_usuario`.

---

## 💻 4. Implementación - Fases 1 y 2
### Maquetación frontend e inicio con backend nativo

*   **Fase 1: Maquetación y Frontend Interactivo (Sprints 1 y 2)**
    *   Estructura HTML5 semántica y diseño deportivo responsive con Bootstrap 5.
    *   Validación de cliente con JavaScript nativo y almacenamiento en `LocalStorage`.
    *   Gráficos dinámicos integrados con **Chart.js** alimentados por simulación de datos.
*   **Fase 2: Backend Nativo y Persistencia (Sprint 3)**
    *   Diseño físico de base de datos MySQL con phpMyAdmin.
    *   Desarrollo de un CRUD funcional en PHP Puro y conexión PDO segura con sentencias preparadas contra inyecciones SQL.
    *   *Lección aprendida:* Identificación de código espagueti, justificando la migración a un framework.

---

## 🚀 5. Implementación - Fases 3 y 4
### El salto a Laravel y la lógica avanzada

*   **Fase 3: Migración a Laravel y Arquitectura MVC (Sprints 4-6)**
    *   Definición de rutas semánticas y arquitectura limpia en controladores REST.
    *   Modelos relacionales con **Eloquent ORM** y control de base de datos mediante **Migrations**.
    *   Vistas organizadas mediante herencia de plantillas de **Blade**.
    *   Autenticación out-of-the-box segura con **Laravel Breeze** y protección por *Middlewares*.
*   **Fase 4: Lógica Avanzada, Gamificación y UX (Sprints 7 y 8)**
    *   Calendario mensual interactivo con **FullCalendar.js** consumiendo JSON dinámico.
    *   Lógica matemática en servidor para la progresión de metas e hitos de gamificación.
    *   Interactividad suave sin recargas mediante peticiones asíncronas con **Fetch API (AJAX)**.

---

## 🐳 6. Implementación - Fase 5
### Contenerización y arquitectura en producción (Sprint 9)

*   **Estandarización absoluta:** Contenerización completa mediante **Docker**.
*   **Dockerfile Personalizado:** Definición de una imagen optimizada de PHP con extensiones de base de datos y Composer.
*   **Orquestación con Docker Compose:**
    1.  **Contenedor Web/App:** Ejecución del motor PHP-FPM y código Laravel.
    2.  **Contenedor Proxy/Web:** Servidor web (Nginx o Apache) para peticiones y estáticos.
    3.  **Contenedor Base de Datos:** MySQL 8.0 con almacenamiento de volúmenes persistentes.
*   *Ventaja:* Despliegue productivo e idéntico en cualquier máquina host con un único comando.

---

## 🧪 7. Pruebas y Control de Calidad
### Garantizando la estabilidad de SinergyFit

*   **Pruebas Unitarias (Backend):**
    *   Suite de tests con **PHPUnit** para asegurar cálculos matemáticos exactos en calorías y volumen de levantamiento.
*   **Optimización y Rendimiento:**
    *   Monitoreo con **Laravel Debugbar** para eliminar consultas redundantes y mitigar problemas de "N+1 queries" mediante Eager Loading.
*   **Pruebas de Frontend y Usabilidad:**
    *   Pruebas de Caja Negra de flujos de usuario (registro erróneo, accesos indebidos).
    *   Validaciones estrictas de formularios de servidor mediante *Form Requests*.
    *   Testeo responsive emulado en múltiples resoluciones.

---

## 💰 8. Presupuesto y Costes
### Estimación de costes y viabilidad financiera

*   **Costes de Recursos Humanos (Desarrollo Activo):**
    *   **160 horas** de trabajo estimadas a lo largo de los 9 sprints.
    *   Tarifa estándar de desarrollador Junior: 20 € / hora.
    *   *Presupuesto mano de obra:* **3.200 €**.
*   **Costes de Software:**
    *   **0 €** gracias al uso exclusivo de tecnologías *Open Source* y de licencias libres (PHP, Laravel, Docker, Nginx, MySQL, VS Code).
*   **Costes de Infraestructura de Producción:**
    *   Servidor VPS Linux (2GB RAM, 40GB SSD) y Dominio Web (.es/.com): **72 € / año**.
*   **Coste total de puesta en marcha (Año 1): 3.272 €**.

---

## 🔮 9. Conclusiones y Futuro
### Valoración personal y evolución del producto

*   **Cumplimiento del 100% de los objetivos:** Plataforma robusta, interactiva, gamificada y desplegada en producción.
*   **Retos Superados:** La asimilación de la lógica de Eloquent ORM y la gestión de redes internas en Docker Compose.
*   **Valoración Académica:** Consolidación práctica de todas las áreas del desarrollo web (*Full-Stack*), desde el boceto inicial hasta la virtualización.
*   **Ampliaciones Futuras:**
    *   Apertura del backend mediante una **API RESTful**.
    *   Creación de una aplicación nativa móvil en React Native.
    *   Incorporación de IA para la generación automática de rutinas de fuerza.

---

## 💬 10. Turno de Preguntas
### Defensa técnica y Anexos

*   **Anexo I (Manual de Usuario):** Registro, Dashboard interactivo, registro dinámico de series, vista mensual en calendario, metas personales y vitrina de medallas.
*   **Anexo II (Manual de Instalación):** Clonación con Git, instalación de Composer y NPM, configuración del entorno `.env`, llaves, migraciones y seeders (`migrate --seed`) y lanzamiento de servidor local.
*   **Anexo III (Diagramas Técnicos):** Diagrama ERD en español y fragmento de `docker-compose.yml`.

> **Muchas gracias por su atención. Quedo a disposición del tribunal para responder a cualquier pregunta.**

---
---

# 🗣️ GUIÓN DEL ORADOR (SPEAKER NOTES)
## Utiliza este guión durante la defensa para explicar detalladamente tu proyecto

### 🎤 Introducción (Diapositiva 1 - Portada)
*"Buenos días, distinguidos miembros del tribunal. Mi nombre es **Juan Francisco Cortejosa Galindo**, y a continuación voy a realizar la defensa de mi proyecto integrado: **SinergyFit**, bajo la tutela de **Marta Lopez Ron**. SinergyFit es una aplicación web interactiva diseñada para la gestión, seguimiento y gamificación de la actividad física, estructurada a través de un desarrollo ágil y con un stack técnico profesional e innovador."*

---

### 🎤 Contexto y Propósito (Diapositiva 2 - Introducción)
*"El proyecto SinergyFit responde a un problema de salud pública generalizado: el sedentarismo y la dificultad para mantener la constancia en el entrenamiento. Las herramientas tradicionales, como libretas físicas o notas en el móvil, resultan ineficaces porque dispersan la información y no permiten un análisis real de nuestro progreso. SinergyFit ataca este problema estructurando la aplicación en tres objetivos principales: un registro detallado de las variables de entrenamiento (series, repeticiones y cargas), la visualización de estas métricas en tiempo real a través de gráficos interactivos de rendimiento, y una capa de gamificación mediante rachas diarias y medallas de logros para motivar la continuidad del usuario. A nivel personal, el proyecto consolida los conocimientos de desarrollo web integrando tecnologías clave del mercado."*

---

### 🎤 Decisión de Stack (Diapositiva 3 - Viabilidad y Alternativas)
*"Antes de programar, realizamos un estudio minucioso de viabilidad. Determinamos que técnicamente el stack cubría todos los objetivos formativos, económicamente era viable gracias al uso exclusivo de licencias libres de código abierto, y operativamente su diseño responsivo cubría la necesidad de uso directo en el gimnasio.
Al analizar las alternativas de arquitectura, decidimos usar el framework Laravel 10 para el backend en lugar de PHP puro, ya que nos provee de una arquitectura MVC profesional y robusta. Para el frontend, optamos por plantillas Blade combinadas con Bootstrap 5 y JavaScript nativo en lugar de una arquitectura separada con React o Vue; esta decisión nos permitió integrarnos ágilmente con el servidor sin añadir complejidad de APIs innecesarias en el inicio. Finalmente, descartamos entornos de desarrollo tradicionales como XAMPP a favor de Docker para evitar incompatibilidades locales y agilizar el despliegue."*

---

### 🎤 Estructura del Sistema (Diapositiva 4 - Análisis y Diseño)
*"La planificación inicial nos llevó a redactar un Backlog estricto. Definimos requisitos como el registro seguro de usuarios, el logging de series de fuerza y cardio, la actualización asíncrona del Dashboard y un calendario mensual interactivo.
Para el diseño de la persistencia, normalizamos la base de datos MySQL traduciendo todo el esquema a español en la fase final para mayor legibilidad del tribunal. Como pueden apreciar en el documento, el núcleo del diseño son la tabla 'usuarios' —que almacena credenciales y biometría— y la relación uno a muchos con 'entrenamientos' y 'objetivos'. Destaca la relación muchos a muchos entre usuarios y logros a través de la tabla intermedia 'logro_usuario', que permite que los hitos del deportista se almacenen de forma eficiente."*

---

### 🎤 Primeros Pasos del Desarrollo (Diapositiva 5 - Fases 1 y 2)
*"La implementación se dividió en fases. En la Fase 1 diseñamos la maquetación responsiva con Bootstrap 5, validamos formularios en el navegador y simulamos persistencia local usando el LocalStorage de JavaScript, integrando por primera vez Chart.js en el frontend.
En la Fase 2 pasamos a dotar de persistencia real al proyecto con PHP puro y MySQL, usando PDO y sentencias preparadas para garantizar la seguridad contra inyecciones de código. Aunque este CRUD nativo funcionó perfectamente para asentar las bases del funcionamiento, vimos que la mezcla de archivos PHP con HTML generaba problemas de mantenibilidad a medida que aumentaba el tamaño de la aplicación, lo que justificó plenamente el salto a Laravel."*

---

### 🎤 Migración y Lógica de Negocio (Diapositiva 6 - Fases 3 y 4)
*"En la Fase 3 reescribimos el sistema completo en Laravel. El código ganó en legibilidad al organizar las peticiones en rutas semánticas y controladores dedicados. Implementamos las bases de datos mediante migraciones de Laravel, evitando dependencias manuales, y simplificamos las vistas gracias a la potencia de las plantillas Blade y componentes reutilizables. La autenticación la delegamos en Laravel Breeze para garantizar un flujo de login y registro sumamente seguro protegido por Middlewares.
En la Fase 4 aumentamos la calidad de la aplicación. Integramos FullCalendar.js para pintar los entrenamientos mensuales en base a consultas JSON del servidor, programamos los algoritmos de cálculo de calorías estimadas y progreso de metas, y erradicamos las recargas molestas de página utilizando peticiones asíncronas con Fetch API para operaciones recurrentes."*

---

### 🎤 Virtualización Profesional (Diapositiva 7 - Fase 5 Docker)
*"La última fase del desarrollo fue una de las más interesantes: la contenerización mediante Docker. Redactamos un Dockerfile personalizado para nuestro código Laravel en un contenedor ligero de PHP-FPM y configuramos un archivo docker-compose.yml para levantar y orquestar tres servicios independientes en una red virtual aislada: la aplicación PHP, un proxy inverso Nginx y una base de datos MySQL 8.0 con almacenamiento montado en volúmenes locales. Esta infraestructura permite levantar SinergyFit en cualquier ordenador del mundo o servidor de internet en segundos con solo ejecutar el comando 'docker-compose up', logrando una portabilidad y profesionalidad de grado de producción."*

---

### 🎤 Testeo y Calidad (Diapositiva 8 - Pruebas)
*"Para garantizar que el software carece de fallos y es robusto, diseñamos una batería de pruebas. Escribimos pruebas unitarias con PHPUnit que evalúan la lógica del cálculo de calorías estimadas y sumas de cargas de peso. También integramos Laravel Debugbar en desarrollo para analizar el consumo de memoria y queries a la base de datos, lo que nos permitió aplicar Eager Loading a nuestros modelos Eloquent y evitar el problema de rendimiento N+1.
En el Frontend, realizamos pruebas manuales de caja negra simulando flujos reales del usuario, validamos estrictamente las entradas del servidor mediante Form Requests y testeaamos la responsividad visual del Dashboard en resoluciones móviles y tablets."*

---

### 🎤 Estudio Económico (Diapositiva 9 - Costes)
*"El apartado de costes e inversión demuestra la viabilidad económica del producto. Estimamos un total de 160 horas de desarrollo activo a lo largo de los sprints del curso. Aplicando una tarifa de 20 €/hora propia de un perfil de desarrollador Junior, el coste de recursos humanos se sitúa en 3.200 €. Al haber seleccionado estrictamente herramientas y tecnologías de código abierto (Open Source), la inversión en licencias de software es de 0 €. El coste de infraestructura anual para alojar la aplicación con Docker en producción es de 72 € al año, sumando un presupuesto de lanzamiento total de 3.272 €, lo que lo hace un proyecto altamente viable e idóneo para empresas emergentes o despliegues rápidos."*

---

### 🎤 Reflexión y Futuro (Diapositiva 10 - Conclusiones)
*"Como conclusiones finales, este proyecto ha significado una maduración técnica muy importante para mí. He comprendido el flujo completo de vida de una aplicación desde el diseño en papel hasta la virtualización con Docker, dominando el patrón MVC. SinergyFit es totalmente estable. En un futuro, el siguiente paso evolutivo será crear una API REST robusta que nos permita alimentar una aplicación móvil nativa desarrollada con React Native, así como integrar modelos sencillos de Inteligencia Artificial para recomendar entrenamientos automáticamente."*

---

### 🎤 Despedida y Anexos (Diapositiva 11 - Preguntas)
*"Por último, como pueden observar en la documentación impresa y en pantalla, incluimos tres Anexos indispensables: el Manual de Usuario que detalla el uso de todas las interfaces del sistema, el Manual de Instalación paso a paso para levantar la aplicación en local ejecutando los comandos de Composer, NPM y el sembrado de base de datos con Seeders, y finalmente los diagramas y código de orquestación técnica del proyecto.
Agradezco enormemente su atención y quedo a la total disposición del tribunal para responder a cualquier pregunta o aclaración que consideren oportuna. Muchas gracias."*
