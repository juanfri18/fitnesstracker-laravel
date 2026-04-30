# Memoria Documental del Proyecto "SinergyFit" (FitnessTracker Laravel)

Este documento detalla la evolución arquitectónica y funcional del proyecto, clasificada por **Sprints** o fases de desarrollo. Sirve como base para exponer y argumentar los cambios realizados, las tecnologías utilizadas y los documentos clave modificados en cada etapa.

---

## 🚀 SPRINT 1: Migración a Laravel y Arquitectura Base
**Objetivo:** Trasladar la aplicación original escrita en PHP plano (espagueti) a la arquitectura profesional MVC (Modelo-Vista-Controlador) del framework Laravel, configurando el enrutamiento y la tabla principal de usuarios y entrenamientos.

**Archivos Creados o Modificados Clave:**
- `routes/web.php`: Se configuraron las rutas básicas utilizando middlewares (`auth` y `guest`) para proteger la aplicación.
- `database/migrations/2014_10_12_000000_create_users_table.php`: Definición de la estructura de la base de usuarios.
- `database/migrations/2026_02_01_000000_create_entrenamientos_table.php`: Estructura para registrar los ejercicios principales (carrera, caminata, fuerza).
- `app/Http/Controllers/AuthController.php`: Gestión de login, registro de cuentas y cierre de sesión.
- `app/Http/Controllers/EntrenamientoController.php` (Método `index` y `store`): Lógica de "Muro" (Dashboard) para registrar y renderizar actividades.
- `resources/views/layouts/app.blade.php`: Se creó la "plantilla maestra" (Blade layout) que hereda la barra de navegación (sidebar) a todas las demás páginas.

---

## 🎯 SPRINT 2: Gestión de Metas (Objetivos)
**Objetivo:** Permitir al usuario establecer "Metas" (antes llamados Objetivos) de condición física, definiendo fechas límite e inicios para crear compromisos reales.

**Archivos Creados o Modificados Clave:**
- `app/Models/Objetivo.php`: Enlace Eloquent entre el usuario y sus metas.
- `app/Http/Controllers/ObjetivoController.php`: Rutas RESTful (`store`, `update`, `destroy`) centralizando toda la lógica del CRUD de metas.
- `resources/views/objetivos/index.blade.php`: Vista interactiva para gestionar y hacer "check" en las metas completadas.
- `routes/web.php`: Implementación de ruta de recursos `Route::resource('objetivos', ObjetivoController::class)`.

---

## 👤 SPRINT 3: Perfil Avanzado y Parametrización
**Objetivo:** Crear una pantalla donde el usuario actualice sus datos biométricos (Peso y Grasa) que sirvan de motor para las variables relacionales.

**Archivos Creados o Modificados Clave:**
- `database/migrations/..._add_profile_fields_to_users_table.php`: Alteración de tabla en caliente para inyectar `peso`, `grasa` y `foto`.
- `app/Http/Controllers/PerfilController.php`: Lógica para procesar la subida de imágenes (Storage) y actualizar los datos médicos.
- `resources/views/perfil.blade.php`: Formulario estético de actualización dividido por pestañas u organizado verticalmente.
- `resources/views/inicio.blade.php`: Inyección de los datos del usuario en la tarjeta de perfil superior izquierda.

---

## 📈 SPRINT 4: Estadísticas Básicas y Gráficas
**Objetivo:** Mostrar resúmenes analíticos del usuario utilizando gráficas visuales y tarjetas de impacto para procesar los entrenamientos matemáticamente.

**Archivos Creados o Modificados Clave:**
- `app/Http/Controllers/MetricaController.php`: Motor matemático. Mapea la base de datos para contar entrenamientos, sumar minutos, y desglosar por "Semana" y por "Tipo" (Fuerza vs Cardio).
- `resources/views/estadisticas.blade.php`: Arquitectura de *grids* (columnas) para albergar Canvas.
- **Gráficas Asíncronas (JS):** Incorporamos llamadas `fetch('/api/metricas/dashboard')` que descargan el JSON desde el Controller sin recargar la página e inyectan los datos en `Chart.js`.

---

## 📅 SPRINT 5: Motor de Calendario (FullCalendar.js)
**Objetivo:** Proveer una visión general a nivel mensual/semanal de cuándo se han realizado los esfuerzos, integrando librerías de terceros (JavaScript).

**Archivos Creados o Modificados Clave:**
- `resources/views/calendario.blade.php`: Contenedor `<div id="calendar"></div>` ejecutado por el script oficial de FullCalendar.
- `app/Http/Controllers/EntrenamientoController.php` (Método `eventosAPI`): Transformador de la base de datos `Entrenamientos` a formato JSON estandarizado para calendarios (título, startDate, color, hipervínculos).
- `routes/web.php`: Rutas dedicadas de retorno de datos asíncronos (`/api/calendario/eventos`).

---

## 🏋️‍♂️ SPRINT 6: Entrenamientos Dinámicos (Fuerza e Hipertrofia)
**Objetivo:** Aumentar la granularidad de los registros. Ya no basta con indicar "Fuerza 30 min", el usuario debe poder agregar infinitas filas de Series, Reps y Kg mediante DOM scripting.

**Archivos Creados o Modificados Clave:**
- `database/migrations/...create_entrenamiento_detalles_table.php`: Tabla "Hija" con clave foránea vinculada al entrenamiento maestro.
- `app/Models/EntrenamientoDetalle.php`: Nueva clase y modelo para mapear la conexión `pertenece a un Entrenamiento`.
- `resources/views/registro.blade.php` y `editar.blade.php`: Implementación de **JavaScript Vanilla** para clonar filas y generar selectores desplegables iterativos. Colores dinámicos adaptados a la especialidad (Rojo=Fuerza, Azul=Caminata, Verde=Corre).
- `app/Http/Controllers/EntrenamientoController.php` (Modificación gigante en `store`): Motor iterativo que extrae los arreglos (`$request->ejercicio[]`) provenientes del formulario de filas infinitas y ejecuta transacciones de guardado masivo (`insert($detalles)`).

---

## 🎮 SPRINT 7: Gamificación, Logros, Tendencias y Tareas Programadas (ÚLTIMO SPRINT)
**Objetivo:** Refinamiento estelar de la aplicación. Automatizar notificaciones para retener al usuario, dotarle de medallas como recompensa e integrar históricos de entrenamiento y lógicas de rendimiento tendencial.

**Archivos Creados o Modificados Clave:**
1. **Gamificación:** 
   - `Logro.php` y `LogroSeeder.php`: Para crear el catálogo de premios.
   - Migraciones cruzadas `logros` y `logro_user` con corrección de bloqueos de Foreign Keys en la misma marca de tiempo (timestamp).
   - En `EntrenamientoController@store` introducimos condicionales para "otorgar trofeos pasivamente" si cumplen requisitos (ej. +1000 minutos, o primer entrenamiento).
   - `perfil.blade.php` ahora actúa como vitrina de exposición para las medallas en colores u opacadas.

2. **Cron Jobs y Retención (Recordatorios):**
   - Sistema `app/Console/Commands/VerificarInactividad.php`: Identifica qué usuarios no han insertado filas de Entrenamiento en 72h.
   - Clase de Envíos Mail `RecordatorioEntrenamiento.php`: Configurada con notificaciones nativas con botones directos (`Action(...)`).
   - Programación registrada localmente y atada a `app/Console/Kernel.php` con la orden `->daily()`.

3. **Experiencia Completa y Estadísticas Tendenciales:**
   - Adición del botón y ruta completa para visualizar el historial profundo de las actividades para no sobrecargar el "Dashboard" con el método `historial()` paginado en servidor. (`entrenamientos.blade.php`).
   - `MetricaController` incluye el cálculo matemático `$tendencia_porcentaje` (% de crecimiento local comparado con los últimos 7 días), impactando con una pequeña insignia roja o verde la tarjeta Resumen dentro de `Estadísticas`.

---
*Este proyecto refleja una curva de aprendizaje real, escalando de sistemas rústicos en PHP a un ecosistema sofisticado apoyado inteligentemente por un ORM robusto moderno (Eloquent), componentes Blade estandarizados, Controladores delgados, y JS inyectado de forma nativa manteniendo el MVC estricto.*
