## Proyecto 6 – FitnessTracker

**Descripción:** Aplicación web para el seguimiento básico de actividad física, donde usuarios pueden registrar entrenamientos, establecer objetivos y ver su progreso de forma visual.

**Objetivo:** Ayudar a las personas a mantener un registro de su actividad física y motivarse mediante el seguimiento de objetivos personales.

**Características funcionales principales:**
- Registro de usuarios con datos físicos básicos
- Logging de entrenamientos (tipo, duración, intensidad)
- Establecimiento de objetivos semanales/mensuales
- Dashboard con gráficos de progreso
- Calendario de entrenamientos
- Sistema de logros básicos
- Estadísticas personales
- Recordatorios de entrenamientos

**Tecnologías:**
- **Backend:** Laravel 10 con Carbon para fechas
- **Frontend:** HTML5, CSS3, JavaScript, Bootstrap 5, Chart.js
- **Base de datos:** MySQL 8.0
- **Despliegue:** Docker + Docker Compose
- **CI/CD:** GitHub Actions
- **Adicionales:** Chart.js para gráficos, Laravel Scheduler

### 🗓️ Sprint Planning - FitnessTracker (7 meses)

| Semana | Sprint | Objetivo principal | Tecnologías | Tareas del Backlog | Revisión / Entregable |
|--------|---------|--------------------|--------------------|--------------------|-----------------------|
| 1–3 | Sprint 1 | Análisis, diseño UI y modelo E/R | Figma/Balsamiq, Diagrama E/R | T1, T2, T3 | Wireframes, modelo E/R fitness completo |
| 4–6 | Sprint 2 | Maquetación frontend fitness | HTML5, CSS3, Bootstrap, Chart.js | T4, T5, T6 | Dashboard estático completo con gráficos JS |
| 7–9 | Sprint 3 | Backend PHP y base de datos | PHP, MySQL, PDO | T7, T8, T9 | CRUD entrenamientos funcional con métricas PHP |
| 10–12 | Sprint 4 | Migración a Laravel | Laravel, Blade, Eloquent | T10, T11, T12 | Proyecto Laravel con vistas Blade y modelos |
| 13–15 | Sprint 5 | Autenticación y objetivos | Laravel Auth, Validaciones | T13, T14, T15 | Sistema login con metas personales funcional |
| 16–18 | Sprint 6 | Calendario y visualización | FullCalendar.js, Laravel Collections | T16, T17, T18 | Calendario entrenamientos y progreso visual |
| 19–21 | Sprint 7 | Gamificación y recordatorios | Notifications, Tasks Scheduling | T19, T20, T21 | Logros, recordatorios automáticos, estadísticas |
| 22–24 | Sprint 8 | Optimización y testing | PHPUnit, Laravel Debugbar | T22, T23 | Tests métricas y rendimiento optimizado |
| 25–27 | Sprint 9 | Dockerización y despliegue | Docker, Docker Compose | T24 | App fitness contenerizada y desplegada |

### 📊 Relación Sprints con RA y Criterios de Evaluación - FitnessTracker

| Sprint | RA Principales | Criterios de Evaluación | Entregables Clave |
|--------|----------------|-------------------------|-------------------|
| **Sprint 1** | RA1 | e) Se han identificado las características específicas del proyecto, d) Se han valorado las oportunidades de negocio previsibles en el sector, i) Se ha elaborado el guión de trabajo que se va a seguir para la elaboración del proyecto | Análisis métricas fitness, wireframes UI, modelo E/R fitness |
| **Sprint 2** | RA1, RA2 | f) Se han determinado los aspectos que se deben incluir en el proyecto, h) Se ha definido y elaborado la documentación necesaria para su diseño | Maquetación HTML completa, estilos CSS responsivos, gráficos Chart.js |
| **Sprint 3** | RA2 | b) Se ha realizado el estudio de viabilidad técnica del mismo, c) Se han identificado las fases o partes que componen el proyecto y su contenido | Base datos MySQL, CRUD PHP entrenamientos, cálculos métricas PHP |
| **Sprint 4** | RA2, RA3 | a) Se ha recopilado información relativa a los aspectos que van a ser tratados en el proyecto, a) Se han secuenciado las actividades ordenándolas en función de las necesidades de ejecución | Proyecto Laravel configurado, vistas Blade, modelos Eloquent |
| **Sprint 5** | RA2, RA3 | d) Se han establecido los objetivos que se pretenden conseguir identificando su alcance, i) Se han identificado los aspectos que se deben controlar para garantizar la calidad del proyecto | Sistema autenticación, objetivos personales, validaciones |
| **Sprint 6** | RA2, RA3 | e) Se han previsto los recursos materiales y personales necesarios para realizarlo, b) Se han determinado los recursos y la logística necesarios para cada actividad | Calendario entrenamientos, visualización progreso, gráficos |
| **Sprint 7** | RA2, RA3 | f) Se ha realizado el presupuesto económico correspondiente, d) Se han determinado los procedimientos de actuación o ejecución de las actividades | Sistema logros, recordatorios automáticos, estadísticas avanzadas |
| **Sprint 8** | RA3, RA4 | h) Se ha definido y elaborado la documentación necesaria para la implementación o ejecución, a) Se ha definido el procedimiento de evaluación de las actividades o intervenciones | Tests PHPUnit, optimización cálculos, debugging |
| **Sprint 9** | RA4 | c) Se han aplicado indicadores de calidad a las actividades diseñadas en el proyecto, e) Se ha definido y elaborado la documentación necesaria para la evaluación de las actividades y del proyecto | Dockerfile, docker-compose.yml, app fitness desplegada productiva |

### 📋 Product Backlog Detallado - FitnessTracker

| ID | Tarea | Descripción Específica | Sprint | Estimación | Prioridad |
|----|-------|------------------------|--------|-------------|-----------|
| T1 | Análisis métricas fitness | Estudio métricas: tipos ejercicio, duración, calorías, objetivos | Sprint 1 | 4h | Alta |✅
| T2 | Wireframes app fitness | Diseño mockups: dashboard, registro entrenamiento, estadísticas | Sprint 1 | 6h | Alta |✅
| T3 | Modelo E/R fitness | Diagrama E/R: usuarios, entrenamientos, ejercicios, objetivos, métricas | Sprint 1 | 8h | Alta |✅
| T4 | Maquetación HTML dashboard | HTML semántico: dashboard principal, formulario entrenamiento, gráficos | Sprint 2 | 6h | ✅ Alta |
| T5 | Estilos CSS fitness | CSS Bootstrap: dashboard deportivo, cards estadísticas, responsive | Sprint 2 | 6h | Alta |✅
| T6 | JavaScript métricas | Cálculos JS: calorías, progreso, gráficos Chart.js, simulación datos | Sprint 2 | 6h | Alta |✅
| T7 | Base datos MySQL fitness | Creación tablas: usuarios, entrenamientos, ejercicios, objetivos, datos prueba | Sprint 3 | 6h | Alta |
| T8 | CRUD PHP entrenamientos | Scripts PHP para gestión entrenamientos: registrar, listar, editar | Sprint 3 | 6h | Alta |
| T9 | Cálculos métricas PHP | Lógica PHP: calorías quemadas, progreso objetivos, estadísticas | Sprint 3 | 6h | Alta |
| T10 | Instalación Laravel fitness | Setup Laravel, configuración, estructura proyecto fitness | Sprint 4 | 4h | Alta |
| T11 | Migración a Blade fitness | Convertir HTML a Blade, layouts dashboard, componentes métricas | Sprint 4 | 8h | Alta |
| T12 | Rutas y controladores fitness | Rutas RESTful, EntrenamientoController, ObjetivoController, MetricaController | Sprint 4 | 6h | Alta |
| T13 | Modelos Eloquent fitness | Modelos Entrenamiento, Ejercicio, Objetivo, Metrica con relaciones | Sprint 5 | 6h | Alta |
| T14 | Sistema objetivos Laravel | Lógica Eloquent: crear objetivos, calcular progreso, validaciones | Sprint 5 | 8h | Alta |
| T15 | Dashboard dinámico | Dashboard Ajax: actualización tiempo real, métricas actuales | Sprint 5 | 4h | Media |
| T16 | Autenticación fitness | Laravel Breeze, perfiles fitness: datos físicos, nivel actividad | Sprint 6 | 8h | Alta |
| T17 | Gráficos avanzados | Visualizaciones Chart.js: líneas progreso, barras comparativas | Sprint 6 | 6h | Media |
| T18 | Calendario entrenamientos | Vista calendario entrenamientos, planificación semanal | Sprint 6 | 4h | Media |
| T19 | Sistema logros gamificación | Logros automáticos: rachas, hitos, medallas, puntos motivación | Sprint 7 | 8h | Media |
| T20 | Estadísticas avanzadas | Análisis tendencias, comparativas periodos, reportes progreso | Sprint 7 | 8h | Media |
| T21 | Optimización UX fitness | Interface responsive, widgets personalizables, navegación rápida | Sprint 8 | 8h | Alta |
| T22 | Testing fitness | Tests: cálculos calorías, progreso objetivos, métricas | Sprint 8 | 8h | Alta |
| T23 | Docker app fitness | Containerización, docker-compose, configuración producción | Sprint 9 | 8h | Alta |
| T24 | Documentación fitness | Manual usuario, guía entrenamientos, plan uso app | Sprint 9 | 8h | Alta |

---


# 📅 Planificación de Sprint Reviews por Trimestre

## 🎯 Organización Temporal del Proyecto Intermodular

Este documento presenta la planificación general de los **Sprint Reviews** a lo largo de los **7 meses** (27 semanas) del Proyecto Intermodular de 2º DAW. Los sprints tienen una duración de **3 semanas** cada uno, organizados en trimestres académicos.

---

## 📊 Tabla General de Sprint Reviews

| Sprint | Semanas | Trimestre | Título del Sprint Review | Objetivos Principales | Entregables Esperados | Tecnologías Dominantes |
|--------|---------|-----------|--------------------------|----------------------|----------------------|------------------------|
| **Sprint 1** | 1-3 | 1º Trimestre | **Análisis y Maquetación Estática** | • Análisis de requisitos del proyecto<br>• Diseño de wireframes y mockups<br>• Maquetación HTML/CSS estática<br>• Configuración del entorno de trabajo | • Documento de requisitos<br>• Wireframes de pantallas principales<br>• Sitio web estático responsive<br>• Repositorio Git configurado | HTML5, CSS3, Bootstrap, Figma/Balsamiq |
| **Sprint 2** | 4-6 | 1º Trimestre | **Interactividad Frontend con JavaScript** | • Implementación de JavaScript vanilla<br>• Validaciones de formularios<br>• Manipulación del DOM<br>• Persistencia local con LocalStorage | • Frontend interactivo funcional<br>• Validaciones cliente implementadas<br>• Simulación de datos con JS<br>• Documentación técnica inicial | JavaScript ES6+, DOM API, LocalStorage |
| **Sprint 3** | 7-9 | 1º Trimestre | **Backend PHP y Base de Datos** | • Diseño y creación de base de datos<br>• Implementación CRUD con PHP vanilla<br>• Conexión frontend-backend<br>• Gestión de sesiones básica | • Base de datos MySQL funcional<br>• Scripts PHP CRUD operativos<br>• Integración HTML-PHP<br>• Datos persistentes en BD | PHP 8.x, MySQL 8.0, PDO, phpMyAdmin |
| **Sprint 4** | 10-12 | 2º Trimestre | **Migración a Framework Laravel** | • Instalación y configuración de Laravel<br>• Definición de rutas y controladores<br>• Migración de vistas a Blade<br>• Estructura MVC implementada | • Proyecto Laravel funcional<br>• Sistema de rutas RESTful<br>• Plantillas Blade organizadas<br>• Documentación de arquitectura | Laravel 10, Blade Templates, Artisan CLI |
| **Sprint 5** | 13-15 | 2º Trimestre | **Modelos Eloquent y Relaciones** | • Creación de modelos Eloquent<br>• Definición de relaciones BD<br>• Migrations y seeders<br>• Validaciones con Form Requests | • Modelos con relaciones activas<br>• Migrations ejecutadas<br>• ORM Eloquent operativo<br>• Validaciones robustas servidor | Laravel Eloquent, Migrations, Validation |
| **Sprint 6** | 16-18 | 2º Trimestre | **Autenticación y Funcionalidades Avanzadas** | • Sistema de autenticación completo<br>• Gestión de roles y permisos<br>• Funcionalidades específicas del proyecto<br>• Middleware de seguridad | • Login/registro funcional<br>• Sistema de roles implementado<br>• Features principales completas<br>• Seguridad básica aplicada | Laravel Auth/Breeze, Middleware, Gates |
| **Sprint 7** | 19-21 | 3º Trimestre | **Optimización UX y AJAX** | • Implementación de peticiones AJAX<br>• Mejoras de experiencia de usuario<br>• Optimización responsive final<br>• Interactividad avanzada | • Aplicación SPA-like parcial<br>• UX optimizada y fluida<br>• Responsive completo verificado<br>• Performance mejorado | AJAX, Fetch API, JavaScript avanzado |
| **Sprint 8** | 22-24 | 3º Trimestre | **Testing y Control de Calidad** | • Implementación de tests automatizados<br>• Debugging y corrección de bugs<br>• Optimización de queries<br>• Métricas de calidad | • Suite de tests funcional<br>• Bugs críticos resueltos<br>• Código optimizado<br>• Informe de calidad | PHPUnit, Laravel Testing, Debugbar |
| **Sprint 9** | 25-27 | 3º Trimestre | **Despliegue y Documentación Final** | • Containerización con Docker<br>• Despliegue en producción<br>• Documentación completa<br>• Presentación del proyecto | • Aplicación desplegada<br>• Docker funcional<br>• Documentación técnica y usuario<br>• Defensa del proyecto | Docker, Docker Compose, CI/CD básico |

---

## 📈 Distribución Trimestral

### 🗓️ **Primer Trimestre (Sprints 1-3)**
**Duración:** 9 semanas  
**Enfoque:** Fundamentos y Backend Básico

| Aspecto | Descripción |
|---------|-------------|
| **Temática** | Análisis, Frontend Estático, JavaScript e Introducción Backend |
| **Tecnologías** | HTML5, CSS3, JavaScript vanilla, PHP, MySQL |
| **Hitos Principales** | • Sitio web estático completo<br>• Frontend interactivo<br>• CRUD básico funcional |
| **Evaluación** | 3 Sprint Reviews (semanas 3, 6 y 9) |
| **RA Predominantes** | RA1 (Análisis necesidades), RA2 (Diseño proyecto) |

---

### 🗓️ **Segundo Trimestre (Sprints 4-6)**
**Duración:** 9 semanas  
**Enfoque:** Framework Laravel y Desarrollo Avanzado

| Aspecto | Descripción |
|---------|-------------|
| **Temática** | Laravel, Eloquent ORM, Autenticación y Features Principales |
| **Tecnologías** | Laravel 10, Eloquent, Blade, Laravel Auth |
| **Hitos Principales** | • Proyecto migrado a Laravel<br>• Modelos y relaciones completas<br>• Autenticación funcional |
| **Evaluación** | 3 Sprint Reviews (semanas 12, 15 y 18) |
| **RA Predominantes** | RA2 (Diseño proyecto), RA3 (Planificación ejecución) |

---

### 🗓️ **Tercer Trimestre (Sprints 7-9)**
**Duración:** 9 semanas  
**Enfoque:** Optimización, Testing y Despliegue

| Aspecto | Descripción |
|---------|-------------|
| **Temática** | AJAX, Testing, Calidad, Despliegue y Documentación |
| **Tecnologías** | AJAX/Fetch, PHPUnit, Docker, Docker Compose |
| **Hitos Principales** | • UX optimizada<br>• Tests implementados<br>• Aplicación desplegada |
| **Evaluación** | 3 Sprint Reviews (semanas 21, 24 y 27) |
| **RA Predominantes** | RA3 (Planificación), RA4 (Seguimiento y control) |

---
<!-- 
## 🎭 Formato de los Sprint Reviews

### 📋 Estructura Recomendada (1-2 horas)

Cada Sprint Review debe seguir esta estructura:

1. **Bienvenida y Objetivos** (5 min)
   - Recordatorio de objetivos del sprint
   - Agenda de la sesión

2. **Demostración de Funcionalidades** (30-40 min)
   - Cada equipo/alumno muestra su trabajo
   - Demo en vivo de las funcionalidades desarrolladas
   - Explicación técnica breve de implementación

3. **Revisión de Entregables** (15-20 min)
   - Verificación de criterios de aceptación
   - Revisión de documentación generada
   - Código en repositorio actualizado

4. **Feedback y Mejoras** (15-20 min)
   - Comentarios del Product Owner (profesor)
   - Feedback de compañeros
   - Identificación de mejoras para siguiente sprint

5. **Retrospectiva Breve** (10 min)
   - ¿Qué funcionó bien?
   - ¿Qué se puede mejorar?
   - Acciones para el próximo sprint

6. **Planning del Siguiente Sprint** (10-15 min)
   - Presentación de objetivos del próximo sprint
   - Distribución de tareas
   - Dudas y aclaraciones -->

---

## 📊 Métricas de Seguimiento por Sprint

### Indicadores Clave de Rendimiento (KPI)

| Métrica | Descripción | Objetivo |
|---------|-------------|----------|
| **Velocity** | Puntos de historia completados | 15-21h por sprint |
| **Burndown** | Trabajo restante vs tiempo | Línea descendente constante |
| **Bugs Encontrados** | Errores detectados en review | < 5 bugs críticos |
| **Cobertura Tests** | % código con tests | > 70% (Sprint 8+) |
| **Code Quality** | Métricas de calidad (SonarQube) | Grado A o B |
| **Asistencia Reviews** | Participación en sprint reviews | 100% |

---

## 🏆 Criterios de Éxito por Sprint

### Sprint 1: ✅ Análisis y Maquetación
- [ ] Documento de requisitos completo y aprobado
- [ ] Wireframes de todas las pantallas principales
- [ ] HTML/CSS responsive funcional en 3 dispositivos
- [ ] Git configurado con commits regulares

### Sprint 2: ✅ Interactividad Frontend
- [ ] Validaciones JavaScript en todos los formularios
- [ ] Manipulación DOM para contenido dinámico
- [ ] LocalStorage implementado y funcional
- [ ] Código JavaScript modular y comentado

### Sprint 3: ✅ Backend PHP
- [ ] Base de datos normalizada (3FN)
- [ ] CRUD completo funcionando
- [ ] Conexión frontend-backend operativa
- [ ] Prepared statements para prevenir SQL injection

### Sprint 4: ✅ Laravel Básico
- [ ] Laravel instalado y configurado correctamente
- [ ] Rutas RESTful definidas
- [ ] Controladores resource implementados
- [ ] Vistas Blade con herencia

### Sprint 5: ✅ Eloquent ORM
- [ ] Modelos con relaciones correctas
- [ ] Migrations ejecutadas sin errores
- [ ] Seeders con datos de prueba realistas
- [ ] Validaciones Form Request implementadas

### Sprint 6: ✅ Autenticación
- [ ] Sistema login/logout funcional
- [ ] Registro de usuarios operativo
- [ ] Middleware auth protegiendo rutas privadas
- [ ] Funcionalidades principales del proyecto completas

### Sprint 7: ✅ Optimización UX
- [ ] Peticiones AJAX sin recargar página
- [ ] Feedback visual en operaciones asíncronas
- [ ] Responsive verificado en múltiples dispositivos
- [ ] Performance optimizado (< 2s carga)

### Sprint 8: ✅ Testing
- [ ] Tests unitarios de modelos principales
- [ ] Tests funcionales de flujos críticos
- [ ] Cobertura > 70%
- [ ] Bugs críticos resueltos

### Sprint 9: ✅ Despliegue
- [ ] Docker compose funcional
- [ ] Aplicación desplegada y accesible
- [ ] README completo con instrucciones
- [ ] Presentación del proyecto realizada

---

## 👥 Roles en los Sprint Reviews

### 🎓 Rol del Profesor (Product Owner)

- Valida que se cumplan los objetivos del sprint
- Proporciona feedback constructivo
- Aprueba o solicita ajustes en entregables
- Orienta sobre próximos pasos
- Evalúa la calidad técnica

### 👨‍💻 Rol del Alumno (Developer)

- Presenta su trabajo desarrollado
- Explica decisiones técnicas tomadas
- Demuestra funcionalidades en vivo
- Responde preguntas y dudas
- Propone mejoras para siguiente sprint

### 👥 Rol de Compañeros (Stakeholders)

- Proporcionan feedback como usuarios
- Sugieren mejoras de UX/UI
- Hacen preguntas técnicas
- Aprenden de las soluciones de otros
- Colaboran en la retrospectiva

---
<!-- 
## 💡 Consejos para Sprint Reviews Exitosos

### ✅ Preparación Previa

1. **Demo Preparada**: Prueba tu demo antes del review
2. **Entorno Funcional**: Asegúrate que todo funciona
3. **Datos de Prueba**: Ten datos realistas cargados
4. **Backup Plan**: Ten un vídeo por si falla algo en vivo

### 🎯 Durante el Review

1. **Sé Conciso**: Máximo 10 minutos por presentación
2. **Muestra, No Cuentes**: Demo en vivo > slides
3. **Enfócate en Valor**: Qué problema resuelve
4. **Acepta Feedback**: Es para mejorar, no para criticar

### 📈 Después del Review

1. **Documenta Feedback**: Anota todas las sugerencias
2. **Actualiza Backlog**: Añade nuevas historias si es necesario
3. **Planifica Mejoras**: Prioriza feedback para próximo sprint
4. **Celebra Logros**: Reconoce el trabajo realizado -->

---

<!-- ## 📚 Recursos Adicionales

### 🔗 Enlaces Útiles

- [Guía Scrum Official](https://scrumguides.org/)
- [Atlassian: How to Run Sprint Reviews](https://www.atlassian.com/agile/scrum/sprint-reviews)
- [Mountain Goat Software: Sprint Review Meeting](https://www.mountaingoatsoftware.com/agile/scrum/meetings/sprint-review-meeting)

### 📖 Lecturas Recomendadas

- **"Scrum: The Art of Doing Twice the Work in Half the Time"** - Jeff Sutherland
- **"User Story Mapping"** - Jeff Patton
- **"The Phoenix Project"** - Gene Kim

---

## 📝 Plantilla de Informe de Sprint Review

```markdown
# Sprint Review #X - [Título del Sprint]

**Fecha:** DD/MM/YYYY
**Sprint:** #X (Semanas X-X)
**Participantes:** [Lista de asistentes]

## 📊 Resumen del Sprint

### Objetivo del Sprint
[Descripción del objetivo principal]

### Historias Completadas
- [ ] US-XXX: [Título de la historia]
- [ ] US-XXX: [Título de la historia]

### Historias Pendientes
- [ ] US-XXX: [Título de la historia] - [Razón]

## 🎯 Demostración

### Funcionalidades Mostradas
1. [Funcionalidad 1]: [Descripción breve]
2. [Funcionalidad 2]: [Descripción breve]

### Problemas Técnicos Durante Demo
[Si hubo algún problema]

## 💬 Feedback Recibido

### Positivo
- [Comentario positivo 1]
- [Comentario positivo 2]

### Áreas de Mejora
- [Sugerencia de mejora 1]
- [Sugerencia de mejora 2]

## 📋 Acciones para Siguiente Sprint

1. [Acción 1]
2. [Acción 2]

## 📊 Métricas

- **Velocity**: X horas completadas de Y planificadas
- **Bugs encontrados**: X
- **Cobertura de tests**: X%

## 🔄 Retrospectiva Breve

**Start (Empezar a hacer):** [Qué empezar]
**Stop (Dejar de hacer):** [Qué dejar]
**Continue (Seguir haciendo):** [Qué mantener]
``` -->

---

**Última actualización:** Noviembre 2025  
**Documento preparado por:** Equipo Docente 2º DAW
