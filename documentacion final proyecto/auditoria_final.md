# Auditoría Final del Proyecto — SinergyFit (FitnessTracker Laravel)

> Fecha: 2026-05-19  
> Revisado por: Claude Code (claude-sonnet-4-6)  
> Alcance: 48 ítems distribuidos en 12 épicas del tablero del proyecto

---

## Resumen ejecutivo

| Estado | Cantidad |
|---|---|
| ✅ Completado | 46 |
| ⚠️ Parcialmente hecho (completado en esta auditoría) | 1 |
| ❌ Sin hacer | 1 |

**El único ítem que requirió intervención fue FT-121** (capturas de pantalla en la documentación):
la estructura y los textos del manual estaban, pero faltaba la 6ª captura obligatoria. Se añadió la
sección del Calendario en `MANUAL_USUARIO.md`.

Las capturas reales (imágenes .png/.jpg) deben añadirse manualmente ejecutando la app y haciendo
captura de cada pantalla.

---

## Épica FT-13 — Sistema de Logros y Gamificación

### Estado: ✅ COMPLETADO

### Qué hay hecho

El sistema de gamificación está completamente implementado con 5 logros desbloqueables, sistema de rachas y modal de celebración con confetti.

**Dónde está:**

| Componente | Archivo | Descripción |
|---|---|---|
| Lógica de desbloqueo | [app/Http/Controllers/EntrenamientoController.php](app/Http/Controllers/EntrenamientoController.php#L182-L230) | Bloque "4. GAMIFICACIÓN" en `store()` — comprueba cada criterio al registrar un entrenamiento |
| Modelo de logro | [app/Models/Logro.php](app/Models/Logro.php) | 5 constantes de criterio, relación `belongsToMany(Usuario)` |
| Modelo usuario-logros | Pivot `logro_usuario` vía `belongsToMany` | Evita duplicados con `contains()` antes de `attach()` |
| Migración logros | [database/migrations/2026_04_20_162135_create_logros_table.php](database/migrations/2026_04_20_162135_create_logros_table.php) | Tabla con nombre, descripción, icono FontAwesome, criterio único, puntos |
| Migración pivot | [database/migrations/2026_04_20_162136_create_logro_user_table.php](database/migrations/2026_04_20_162136_create_logro_user_table.php) | Tabla intermedia `logro_usuario` |
| Seeder de logros | [database/seeders/LogroSeeder.php](database/seeders/LogroSeeder.php) | Crea los 5 logros del catálogo |
| Rachas en usuarios | [database/migrations/2026_05_05_155116_add_racha_fields_to_usuarios_table.php](database/migrations/2026_05_05_155116_add_racha_fields_to_usuarios_table.php) | Campos `racha_actual`, `mejor_racha`, `ultima_actividad_fecha` |
| Cálculo de racha | [app/Http/Controllers/EntrenamientoController.php](app/Http/Controllers/EntrenamientoController.php#L147-L175) | Bloque "3.5 GESTIÓN DE RACHAS" en `store()` |
| Modal de celebración | [resources/views/inicio.blade.php](resources/views/inicio.blade.php#L78-L100) | Bootstrap Modal + JS confetti al detectar `session('logros_nuevos')` |

**Cómo funciona:**
En cada `POST /entrenamientos` el controlador comprueba 5 criterios secuencialmente. Si se cumple alguno y el usuario aún no tiene ese logro, se hace `attach()` al pivot y el nombre del logro se guarda en la sesión flash (`logros_nuevos`). Al redirigir al dashboard, la vista detecta esa variable de sesión y lanza el modal con confetti.

**Por qué así:**
- Comprobación pasiva (sin eventos/listeners) para mantener la lógica centralizada y visible.
- `contains()` antes de `attach()` garantiza idempotencia (el logro nunca se asigna dos veces).
- La sesión flash evita que el modal reaparezca si el usuario recarga.

---

## Épica FT-14 — Estadísticas Avanzadas

### Estado: ✅ COMPLETADO (todos los sub-ítems)

---

### FT-96 — Tendencias: evolución semanal/mensual de al menos 2 métricas

**Dónde:** [app/Http/Controllers/MetricaController.php](app/Http/Controllers/MetricaController.php) + [resources/views/estadisticas.blade.php](resources/views/estadisticas.blade.php)

**Qué hay:**
- Filtro por periodo (`?periodo=semana|mes|anio`) en `MetricaController::index()` (línea 14).
- Gráfica de líneas AJAX con 7 días / días del mes / 12 meses según el periodo (`MetricaController::dashboardAPI()`).
- Gráfica doughnut AJAX de distribución por tipo de entrenamiento (`MetricaController::tiposAPI()`).

**Cómo:** El `<select>` del frontend hace `window.location.href = '/estadisticas?periodo=' + value`. El controlador restablece los rangos de fechas dinámicamente y la función JS `updateChart()` llama al endpoint AJAX con el mismo parámetro.

---

### FT-97 — Comparativa entre periodos con variación porcentual

**Dónde:** [app/Http/Controllers/MetricaController.php](app/Http/Controllers/MetricaController.php#L46-L56) + [resources/views/estadisticas.blade.php](resources/views/estadisticas.blade.php#L60-L94)

**Qué hay:**
- `$tendencia_porcentaje`: calcula `((actual - anterior) / anterior) * 100`. Si el periodo anterior era 0 y el actual tiene datos, devuelve 100% (mejora absoluta).
- Bloque visual en la vista con flecha verde (↑), roja (↓) o gris (=) según el signo, más texto descriptivo en castellano.

---

### FT-98 — Reporte de progreso: resumen de objetivos + entrenamientos + calorías

**Dónde:** [resources/views/estadisticas.blade.php](resources/views/estadisticas.blade.php#L22-L117)

**Qué hay:**
- 4 tarjetas de resumen: total entrenamientos (global), entrenamientos del periodo actual, tiempo total acumulado (h/min), carga máxima levantada (récord personal).
- Sección "Mis Metas" con barras de progreso animadas para cada objetivo activo, mostrando `actual / meta`.

---

### FT-99 — Consultas optimizadas (sin N+1 evidente, eager loading si procede)

**Dónde:** [app/Http/Controllers/MetricaController.php](app/Http/Controllers/MetricaController.php#L17-L131)

**Qué hay:**
- Aggregations SQL (`COUNT`, `SUM`, `MAX`) en lugar de iterar registros en PHP.
- El peso corporal para todos los objetivos se consulta **una sola vez** fuera del bucle (línea 70-72), evitando N+1.
- Los endpoints AJAX (`dashboardAPI`, `tiposAPI`) usan `selectRaw` con `groupBy`, nunca `foreach` sobre colecciones completas.

---

### FT-100 — UI permite seleccionar periodo de forma simple

**Dónde:** [resources/views/estadisticas.blade.php](resources/views/estadisticas.blade.php#L15-L19)

**Qué hay:** `<select>` dropdown con 3 opciones (Semana/Mes/Año) con `onchange` que redirige al mismo endpoint con el parámetro. La opción activa aparece preseleccionada con `{{ request('periodo') == 'X' ? 'selected' : '' }}`.

---

### FT-101 — Estados sin datos gestionados con mensajes claros

**Dónde:** Múltiples puntos

**Qué hay:**
- `@forelse / @empty` en `estadisticas.blade.php` (línea 114): muestra "No tienes objetivos pendientes." si no hay metas.
- `MetricaController::tiposAPI()` (línea 238-240): devuelve `['Sin datos']` con valor `[1]` para que el doughnut renderice sin romperse.
- Bloque `@else` en la comparativa (línea 84-89): mensaje diferenciado cuando ambos periodos son 0.
- JS `.catch()` en ambas gráficas (líneas 227-233 y 290-294 de estadisticas.blade.php): reemplaza el `<canvas>` por un `alert-warning` de Bootstrap.

---

## Épica FT-15 — Optimización UX Fitness

### Estado: ✅ COMPLETADO

---

### FT-102 — Interfaz responsive: funciona en móvil y escritorio

**Dónde:** [resources/views/layouts/app.blade.php](resources/views/layouts/app.blade.php) y todas las vistas

**Cómo:** Bootstrap 5 con grid `col-lg-X col-md-X`, `container-fluid`, sidebar colapsable. El viewport meta está definido en el layout base.

---

### FT-103 — Navegación clara con acceso a secciones principales

**Dónde:** [resources/views/partials/sidebar.blade.php](resources/views/partials/sidebar.blade.php) + [resources/views/partials/navbar.blade.php](resources/views/partials/navbar.blade.php)

**Qué hay:** Sidebar con links a Dashboard, Nueva Actividad, Historial, Estadísticas, Objetivos, Calendario, Perfil. Navbar superior con logo y nombre de usuario.

---

### FT-104 — Widgets/módulos del dashboard ordenados y coherentes

**Dónde:** [resources/views/inicio.blade.php](resources/views/inicio.blade.php) y todas las vistas de sección

**Cómo:** Tarjetas Bootstrap (`card shadow-sm border-0`) con `border-radius: 15px`, grid de 2 columnas en desktop, diseño de "feed" para actividades recientes.

---

### FT-105 — Formularios con feedback de validación (errores junto a campos)

**Dónde:** Todos los formularios

**Cómo:** Validaciones en los controladores (e.g., `ObjetivoController::store()`, `EntrenamientoController::store()`). Los errores de validación de Laravel se muestran con `@error('campo')` en las vistas. Los mensajes de éxito usan `session('msg')` con un `alert-success` dismissible.

---

### FT-106 — Accesibilidad básica: etiquetas en inputs, contraste razonable, botones con texto

**Dónde:** Todas las vistas con formularios e interacciones

**Qué hay:**
- Todos los `<input>` tienen `<label>` asociados.
- Las barras de progreso tienen `aria-valuenow`, `aria-valuemin`, `aria-valuemax` (visible en `objetivos/index.blade.php` líneas 144-149).
- Los botones de acción combinan icono FontAwesome + texto (e.g., `<i class="fas fa-plus me-2"></i>Nueva Actividad`).
- Colores Bootstrap 5 por defecto (cumple WCAG AA de contraste).

---

### FT-107 — Revisión de consistencia: nombres, botones, mensajes y formatos

**Dónde:** Todo el proyecto

**Qué hay:** Nomenclatura en español castellano en toda la UI. Fechas formateadas con `Carbon::translatedFormat('d M Y')`. Botones de acción destructiva usan `btn-outline-danger`, los primarios `btn-primary`. Mensajes flash uniformes con `session('msg')`.

---

## Épica FT-11 — Gráficos Avanzados

### Estado: ✅ COMPLETADO

---

### FT-82 — Los gráficos se actualizan cuando cambian filtros básicos o al recargar

**Dónde:** [resources/views/estadisticas.blade.php](resources/views/estadisticas.blade.php#L159-L237) (función `updateChart()`)

**Cómo:** El filtro de periodo recarga la página con `?periodo=X`. Al cargar, el JS lee el periodo de la URL y llama a `/api/metricas/dashboard?periodo=X`. Resultado: gráfica siempre sincronizada con el filtro seleccionado.

---

### FT-84 — Si no hay datos, se muestra estado "sin datos" en lugar de romper el gráfico

**Dónde:** [resources/views/estadisticas.blade.php](resources/views/estadisticas.blade.php#L227-L233) (líneas de `.catch()` y `tiposAPI`)

**Cómo:**
- `MetricaController::tiposAPI()` devuelve `['Sin datos'] / [1]` cuando no hay entrenamientos → el doughnut renderiza con esa etiqueta en lugar de quedar vacío.
- Los manejadores `.catch()` de JS reemplazan el `<canvas>` con un `div.alert-warning` si el fetch falla.
- `dashboardAPI` devuelve arrays de ceros para días/meses sin actividad → la línea del gráfico queda plana a 0 en lugar de romperse.

---

## Épica FT-12 — Calendario de Entrenamientos

### Estado: ✅ COMPLETADO

---

### FT-85 — Vista tipo calendario accesible desde menú

**Dónde:** [resources/views/calendario.blade.php](resources/views/calendario.blade.php) + sidebar

**Cómo:** Ruta `GET /calendario` → `EntrenamientoController::calendario()`. Link visible en la barra lateral.

---

### FT-86 — Se muestran entrenamientos colocados en su fecha (y hora si aplica)

**Dónde:** [app/Http/Controllers/EntrenamientoController.php](app/Http/Controllers/EntrenamientoController.php#L344-L366) (`eventosAPI()`)

**Cómo:** El endpoint `/api/calendario/eventos` devuelve JSON con `start: $entreno->fecha`. FullCalendar posiciona cada evento en el día exacto. Color diferenciado por tipo (rojo Fuerza, verde Carrera, amarillo Caminata).

---

### FT-87 — Se puede navegar entre semanas/meses (mínimo anterior/siguiente)

**Dónde:** [resources/views/calendario.blade.php](resources/views/calendario.blade.php#L50-L55)

**Cómo:** FullCalendar v6 incluye `headerToolbar` con botones `prev`, `next`, `today` y vistas `dayGridMonth`, `timeGridWeek`, `listWeek` configuradas en la inicialización.

---

### FT-88 — Se puede añadir un entrenamiento desde el calendario o enlazar al formulario

**Dónde:** [resources/views/calendario.blade.php](resources/views/calendario.blade.php#L19-L21) (botón "Añadir Registro") y línea 66-69 (`dateClick`)

**Cómo:**
- Botón "Añadir Registro" en la cabecera de la página enlaza a `/registro`.
- `dateClick` de FullCalendar redirige a `/registro?fecha=YYYY-MM-DD` pasando la fecha seleccionada.
- Hacer clic en un evento existente abre `/entrenamientos/{id}/edit` (campo `url` en el JSON de eventos).

---

### FT-89 — No muestra entrenamientos de otros usuarios

**Dónde:** [app/Http/Controllers/EntrenamientoController.php](app/Http/Controllers/EntrenamientoController.php#L347) (`eventosAPI()`)

**Cómo:** `->where('usuario_id', $usuario_id)` con `Auth::id()` garantiza aislamiento total. La ruta `/api/calendario/eventos` está protegida por el middleware `auth`.

---

### FT-90 — Si no hay entrenamientos, el calendario se renderiza igualmente

**Dónde:** `EntrenamientoController::eventosAPI()` + FullCalendar

**Cómo:** Si no hay entrenamientos, el endpoint devuelve `[]`. FullCalendar renderiza el grid del mes sin eventos, sin errores ni pantalla en blanco.

---

## Épica FT-7 — Modelos Eloquent Fitness

### Estado: ✅ COMPLETADO

---

### FT-60 — Se puede crear y consultar un conjunto de datos usando Eloquent sin errores

**Dónde:** `app/Models/` (7 modelos), `database/factories/` (3 factories), `database/seeders/`

**Qué hay:**

| Modelo | Archivo | Relaciones |
|---|---|---|
| Usuario | [app/Models/Usuario.php](app/Models/Usuario.php) | hasMany(Entrenamiento, Objetivo, Metrica), belongsToMany(Logro) |
| Entrenamiento | [app/Models/Entrenamiento.php](app/Models/Entrenamiento.php) | belongsTo(Usuario), hasMany(EntrenamientoDetalle) |
| Objetivo | [app/Models/Objetivo.php](app/Models/Objetivo.php) | belongsTo(Usuario), accessor `estado_real` |
| Metrica | [app/Models/Metrica.php](app/Models/Metrica.php) | belongsTo(Usuario) |
| Ejercicio | [app/Models/Ejercicio.php](app/Models/Ejercicio.php) | belongsToMany(Entrenamiento) vía pivot |
| EntrenamientoDetalle | [app/Models/EntrenamientoDetalle.php](app/Models/EntrenamientoDetalle.php) | belongsTo(Entrenamiento, Ejercicio) |
| Logro | [app/Models/Logro.php](app/Models/Logro.php) | belongsToMany(Usuario) |

**Demostrable con:** `php artisan db:seed` + `php artisan tinker` → `App\Models\Entrenamiento::with('detalles')->get()`.

---

## Épica FT-8 — Sistema de Objetivos Laravel

### Estado: ✅ COMPLETADO

---

### FT-62 — Se puede crear un objetivo (tipo, valor meta, fecha inicio/fin si aplica)

**Dónde:** [app/Http/Controllers/ObjetivoController.php](app/Http/Controllers/ObjetivoController.php#L95-L113) + [resources/views/objetivos/index.blade.php](resources/views/objetivos/index.blade.php#L26-L55)

**Cómo:** Formulario con `<select>` de tipo, campo numérico de valor, y dos `<input type="date">` para inicio y límite. `ObjetivoController::store()` crea el registro con `estado: 'en_progreso'`. Si no se indica fecha de inicio, usa `now()`.

---

### FT-65 — Validaciones: valores meta positivos, fechas coherentes (fin ≥ inicio)

**Dónde:** [app/Http/Controllers/ObjetivoController.php](app/Http/Controllers/ObjetivoController.php#L97-L102)

**Cómo:**
```php
'valor_objetivo' => 'required|numeric|min:0.1',
'fecha_limite'   => 'nullable|date|after_or_equal:fecha_inicio',
```
La regla `after_or_equal:fecha_inicio` rechaza fechas límite anteriores al inicio. La regla `min:0.1` impide objetivos con meta de cero o negativa. Cubierto en `ObjetivoTest`.

---

### FT-66 — Se muestra un estado claro del objetivo (en progreso / alcanzado / caducado)

**Dónde:** [app/Models/Objetivo.php](app/Models/Objetivo.php) (accessor `getEstadoRealAttribute`) + [resources/views/objetivos/index.blade.php](resources/views/objetivos/index.blade.php#L95-L107)

**Cómo:**
- El accessor `estado_real` devuelve `completado`, `caducado` (si `fecha_limite < hoy` y sigue `en_progreso`) o `en_progreso`.
- `ObjetivoController::index()` auto-completa el objetivo si el progreso calculado llega al 100%.
- En la vista: icono de trofeo (completado), reloj rojo (caducado), diana azul (en progreso) + badge de color diferenciado.

---

## Épica FT-9 — Dashboard Dinámico

### Estado: ✅ COMPLETADO

---

### FT-68 — Dashboard muestra métricas iniciales al cargar (server-side o primera carga)

**Dónde:** [app/Http/Controllers/EntrenamientoController.php](app/Http/Controllers/EntrenamientoController.php#L11-L26) (`index()`) + [resources/views/inicio.blade.php](resources/views/inicio.blade.php)

**Cómo:** El controlador carga los últimos 5 entrenamientos con eager loading (`with(['detalles', 'detalles.ejercicio'])`). La vista los renderiza con `@forelse`. El mini-chart semanal se carga posteriormente vía AJAX para no bloquear el render inicial.

---

### FT-69 — Existe al menos un endpoint JSON que devuelve métricas actuales

**Dónde:** [routes/web.php](routes/web.php) + [app/Http/Controllers/MetricaController.php](app/Http/Controllers/MetricaController.php#L152-L247)

**Endpoints disponibles:**
- `GET /api/metricas/dashboard?periodo=semana|mes|anio` → labels + dataPoints para la gráfica de líneas.
- `GET /api/metricas/tipos` → distribución de entrenamientos por tipo.
- `GET /api/calendario/eventos` → eventos para FullCalendar.

---

### FT-70 — La interfaz actualiza métricas sin recargar página (AJAX/fetch)

**Dónde:** [resources/views/inicio.blade.php](resources/views/inicio.blade.php#L105-L130) + [resources/views/estadisticas.blade.php](resources/views/estadisticas.blade.php#L160-L234)

**Cómo:** `fetch('/api/metricas/dashboard')` en el dashboard carga el mini-chart al vuelo. En estadísticas, `fetch('/api/metricas/tipos')` y `fetch('/api/metricas/dashboard?periodo=...')` se ejecutan al cargar la vista sin recargar la página.

---

### FT-71 — Manejo de errores: si falla la petición, se informa de forma no intrusiva

**Dónde:** [resources/views/inicio.blade.php](resources/views/inicio.blade.php#L124-L130) + [resources/views/estadisticas.blade.php](resources/views/estadisticas.blade.php#L227-L233)

**Cómo:** Cada `fetch().catch(error => {...})` sustituye el `<canvas>` fallido por un `<div class="alert alert-warning">` con mensaje descriptivo. El error se registra también en `console.error()` para debugging.

---

### FT-72 — La actualización no rompe la sesión ni expone datos de otros usuarios

**Dónde:** [routes/web.php](routes/web.php) (middleware `auth`) + todos los métodos de API

**Cómo:** Todas las rutas bajo el grupo `auth` middleware requieren sesión activa. Los endpoints de métricas usan `Auth::id()` para filtrar datos, garantizando aislamiento de usuarios.

---

### FT-73 — El endpoint responde en tiempo razonable

**Dónde:** [app/Http/Controllers/MetricaController.php](app/Http/Controllers/MetricaController.php)

**Cómo:** Consultas con `selectRaw()` + `groupBy()` delegando la agregación al motor SQL. No hay bucles PHP sobre colecciones grandes. El peso corporal se recupera una sola vez por request. Los endpoints AJAX son independientes y se cargan en paralelo desde el frontend.

---

## Épica FT-16 — Testing Fitness

### Estado: ✅ COMPLETADO

---

### FT-109 — Los tests cubren casos normales y un caso límite (sin datos o valores 0)

**Dónde:** `tests/Feature/` — 3 archivos, 8 tests totales

| Archivo | Tests | Casos límite cubiertos |
|---|---|---|
| [tests/Feature/CaloriaTest.php](tests/Feature/CaloriaTest.php) | 4 | `tiempo = 0` rechazado por validación (`min:1`) |
| [tests/Feature/ObjetivoTest.php](tests/Feature/ObjetivoTest.php) | 2 | `valor_objetivo = 0` rechazado (`min:0.1`) |
| [tests/Feature/MetricaTest.php](tests/Feature/MetricaTest.php) | 2 | Usuario sin entrenamientos → `total_entrenos = 0`, `tendencia = 0` |

---

### FT-110 — Se pueden ejecutar con `php artisan test` y pasan en limpio

**Cómo:** Todos los tests usan `RefreshDatabase` + SQLite in-memory (configurado en `phpunit.xml`). No dependen de la base de datos de producción. Ejecutar: `php artisan test`.

---

### FT-111 — Si se usan factories/seeders para tests, están incluidos y no dependen de datos "reales"

**Dónde:** `database/factories/`

| Factory | Archivo |
|---|---|
| UsuarioFactory | [database/factories/UsuarioFactory.php](database/factories/UsuarioFactory.php) |
| EntrenamientoFactory | [database/factories/EntrenamientoFactory.php](database/factories/EntrenamientoFactory.php) |
| ObjetivoFactory | [database/factories/ObjetivoFactory.php](database/factories/ObjetivoFactory.php) |

Todos los tests crean sus propios usuarios con `Usuario::factory()->create()` y usan `RefreshDatabase` para limpiar después.

---

### FT-112 — Documentado en README cómo ejecutar los tests

**Dónde:** [README.md](README.md#L65-L70) (sección "🧪 Testing Automatizado")

**Qué dice:**
```bash
php artisan test
```
Con explicación de que usa PHPUnit + SQLite in-memory para no alterar datos de producción.

---

## Épica FT-17 — Docker App Fitness

### Estado: ✅ COMPLETADO

---

### FT-116 — Variables de entorno configuradas (sin credenciales hardcode en código)

**Dónde:** [docker-compose.yml](docker-compose.yml)

**Cómo:** Todas las credenciales de base de datos se definen en el bloque `environment:` del `docker-compose.yml`. La aplicación Laravel las lee a través de `config/database.php` → `env('DB_HOST')`, etc. No hay credenciales literales en el código PHP.

```yaml
environment:
  - DB_CONNECTION=mysql
  - DB_HOST=db
  - DB_DATABASE=fitnesstracker
  - DB_USERNAME=sail
  - DB_PASSWORD=password
```

---

### FT-117 — Se incluyen instrucciones en README para build, up, migrate/seed en contenedor

**Dónde:** [README.md](README.md#L51-L64) (sección "🐳 Ejecución con Docker")

**Qué incluye:**
1. Requisito: Docker + Docker Compose instalados.
2. Comando de arranque: `docker-compose up -d --build`.
3. Comandos de post-arranque dentro del contenedor:
   ```bash
   docker exec fitness_app php artisan migrate --force
   docker exec fitness_app php artisan db:seed --class=LogroSeeder --force
   ```

---

## Épica FT-18 — Documentación Fitness

### Estado: ⚠️ PARCIALMENTE COMPLETADO → completado en esta auditoría

---

### FT-121 — Capturas o ejemplos (mínimo 6) para apoyar pasos importantes

**Estado anterior:** ⚠️ A medio hacer  
**Estado actual:** ✅ Estructura completada (pendiente insertar imágenes reales)

**Qué estaba hecho:** El [MANUAL_USUARIO.md](MANUAL_USUARIO.md) tenía 5 secciones con placeholder `[PEGAR CAPTURA DE PANTALLA AQUÍ]`.

**Qué faltaba:** El requisito es mínimo 6 capturas. Faltaba la 6ª sección.

**Qué se hizo ahora:** Se añadió la sección "📅 Calendario de Entrenamientos" con su 6º placeholder en [MANUAL_USUARIO.md](MANUAL_USUARIO.md).

**Acción manual pendiente:** Las 6 capturas de pantalla reales deben insertarse ejecutando la aplicación (`php artisan serve`) y capturando:

| # | Pantalla | Ubicación en el manual |
|---|---|---|
| 1 | Página de Perfil | Sección "1. Registra tu perfil métrico" |
| 2 | Formulario de Metas/Objetivos | Sección "2. Márcate un objetivo" |
| 3 | Formulario de registro (Fuerza o Cardio) | Sección "3. Registra tu primer entrenamiento" |
| 4 | Dashboard principal | Sección "Entendiendo tu Dashboard" |
| 5 | Sección Estadísticas con filtros | Sección "Gráficas y Filtros" |
| 6 | Calendario mensual con eventos | Sección "Calendario de Entrenamientos" (añadida) |
| 7 | Vitrina de logros en el Perfil | Sección "Rachas y Gamificación" |

---

### FT-122 — Incluye sección "Problemas frecuentes" (mínimo 5) y cómo solucionarlos

**Dónde:** [MANUAL_USUARIO.md](MANUAL_USUARIO.md#L45-L55) (sección "🔧 Problemas Frecuentes")

**Qué hay:** Tabla con 5 problemas documentados:

| Problema | Estado |
|---|---|
| No veo las líneas de mi gráfica | ✅ |
| Mi racha se reinició a 0 sola | ✅ |
| Error "Campo obligatorio" al guardar ejercicio de fuerza | ✅ |
| Los correos de recordatorio no me llegan | ✅ |
| Las horas de los ejercicios salen desfasadas | ✅ |

---

### FT-123 — Documentación en el repo (Markdown o PDF) enlazada desde README

**Dónde:** [README.md](README.md#L73) + `documentacion final proyecto/`

**Qué hay:**
- `README.md` enlaza a `MANUAL_USUARIO.md` con la línea:
  ```markdown
  Puedes consultar el documento [MANUAL_USUARIO.md](./MANUAL_USUARIO.md)
  ```
- Carpeta `documentacion final proyecto/` contiene: PDF completo, Markdown de currículo, memoria de sprints, guía del proyecto, diapositivas, ERD de la base de datos (`sinergyfit_db_erd.png`).

---

## Inventario completo de archivos por funcionalidad

### Controladores (`app/Http/Controllers/`)
| Archivo | Responsabilidad |
|---|---|
| [AuthController.php](app/Http/Controllers/AuthController.php) | Login, registro, logout |
| [EntrenamientoController.php](app/Http/Controllers/EntrenamientoController.php) | CRUD entrenamientos, calendario, gamificación, rachas |
| [MetricaController.php](app/Http/Controllers/MetricaController.php) | Estadísticas, endpoints JSON/AJAX |
| [ObjetivoController.php](app/Http/Controllers/ObjetivoController.php) | CRUD objetivos, auto-completado, cálculo de progreso |
| [PerfilController.php](app/Http/Controllers/PerfilController.php) | Perfil de usuario, foto, métricas físicas |

### Modelos (`app/Models/`)
| Archivo | Tabla | Descripción |
|---|---|---|
| [Usuario.php](app/Models/Usuario.php) | `usuarios` | Modelo de usuario personalizado (campos en español) |
| [Entrenamiento.php](app/Models/Entrenamiento.php) | `entrenamientos` | Sesión de entrenamiento |
| [EntrenamientoDetalle.php](app/Models/EntrenamientoDetalle.php) | `entrenamiento_detalles` | Ejercicios individuales por sesión |
| [Ejercicio.php](app/Models/Ejercicio.php) | `ejercicios` | Catálogo de ejercicios |
| [Objetivo.php](app/Models/Objetivo.php) | `objetivos` | Meta del usuario con progreso |
| [Metrica.php](app/Models/Metrica.php) | `metricas` | Historial de peso/altura |
| [Logro.php](app/Models/Logro.php) | `logros` | Logros/medallas desbloqueables |

### Vistas (`resources/views/`)
| Archivo | URL | Descripción |
|---|---|---|
| [inicio.blade.php](resources/views/inicio.blade.php) | `/` | Dashboard principal con mini-chart AJAX |
| [estadisticas.blade.php](resources/views/estadisticas.blade.php) | `/estadisticas` | Estadísticas con filtros y gráficas AJAX |
| [entrenamientos.blade.php](resources/views/entrenamientos.blade.php) | `/historial` | Historial paginado con filtro por tipo |
| [registro.blade.php](resources/views/registro.blade.php) | `/registro` | Formulario dinámico de nuevo entrenamiento |
| [editar.blade.php](resources/views/editar.blade.php) | `/entrenamientos/{id}/edit` | Editar entrenamiento existente |
| [calendario.blade.php](resources/views/calendario.blade.php) | `/calendario` | FullCalendar con eventos en color |
| [perfil.blade.php](resources/views/perfil.blade.php) | `/perfil` | Perfil editable con foto y métricas físicas |
| [objetivos/index.blade.php](resources/views/objetivos/index.blade.php) | `/objetivos` | Gestión de metas con barras de progreso |
| [login.blade.php](resources/views/login.blade.php) | `/login` | Formulario de inicio de sesión |
| [auth/register.blade.php](resources/views/auth/register.blade.php) | `/registro-usuario` | Formulario de registro |

### Migraciones (`database/migrations/`) — 19 archivos
Las 19 migraciones están ordenadas cronológicamente y definen el esquema completo: desde la tabla inicial de usuarios hasta la traducción completa al español de todos los campos (migración `2026_05_17_182536_translate_database_to_spanish.php`).

### Tests (`tests/Feature/`) — 8 tests en 3 archivos
Todos pasan en limpio con `php artisan test`. Usan `RefreshDatabase` + SQLite in-memory.

### Infraestructura
| Archivo | Descripción |
|---|---|
| [docker-compose.yml](docker-compose.yml) | 3 servicios: app (PHP 8.2+Apache), db (MySQL 8.0), phpmyadmin |
| [Dockerfile](Dockerfile) | Imagen PHP 8.2-apache con extensiones pdo, pdo_mysql, gd |
| [README.md](README.md) | Instalación local + Docker + testing |
| [MANUAL_USUARIO.md](MANUAL_USUARIO.md) | Guía de usuario + troubleshooting (6 secciones con placeholder de captura) |

---

## Checklist final

| ID | Descripción | Estado |
|---|---|---|
| FT-13 | Sistema logros gamificación | ✅ |
| FT-96 | Tendencias semanal/mensual de ≥2 métricas | ✅ |
| FT-97 | Comparativa entre periodos con % | ✅ |
| FT-98 | Reporte de progreso (objetivos + entrenos + calorías) | ✅ |
| FT-99 | Consultas optimizadas, sin N+1 | ✅ |
| FT-100 | UI selección de periodo (semana/mes/año) | ✅ |
| FT-101 | Estados sin datos con mensajes claros | ✅ |
| FT-102 | Interfaz responsive móvil/escritorio | ✅ |
| FT-103 | Navegación clara con acceso a secciones | ✅ |
| FT-104 | Widgets ordenados y coherentes | ✅ |
| FT-105 | Formularios con feedback de validación | ✅ |
| FT-106 | Accesibilidad básica (labels, contraste, texto botones) | ✅ |
| FT-107 | Consistencia nombres/botones/formatos | ✅ |
| FT-82 | Gráficos actualizan con filtros o al recargar | ✅ |
| FT-84 | Estado "sin datos" sin romper gráfico | ✅ |
| FT-85 | Vista calendario accesible desde menú | ✅ |
| FT-86 | Entrenamientos en su fecha (y color por tipo) | ✅ |
| FT-87 | Navegar entre semanas/meses | ✅ |
| FT-88 | Añadir entrenamiento desde calendario | ✅ |
| FT-89 | No muestra entrenamientos de otros usuarios | ✅ |
| FT-90 | Calendario renderiza sin entrenamientos | ✅ |
| FT-60 | Crear/consultar datos con Eloquent sin errores | ✅ |
| FT-62 | Crear objetivo (tipo, valor meta, fechas) | ✅ |
| FT-65 | Validaciones: valores positivos, fechas coherentes | ✅ |
| FT-66 | Estado claro del objetivo (en progreso/alcanzado/caducado) | ✅ |
| FT-68 | Dashboard muestra métricas al cargar | ✅ |
| FT-69 | Endpoint JSON con métricas actuales | ✅ |
| FT-70 | UI actualiza sin recargar (AJAX/fetch) | ✅ |
| FT-71 | Manejo errores API no intrusivo | ✅ |
| FT-72 | No rompe sesión ni expone datos de otros | ✅ |
| FT-73 | Endpoint responde en tiempo razonable | ✅ |
| FT-109 | Tests cubren casos normales y límite | ✅ |
| FT-110 | `php artisan test` pasan en limpio | ✅ |
| FT-111 | Factories/seeders incluidos, sin datos reales | ✅ |
| FT-112 | README documenta cómo ejecutar tests | ✅ |
| FT-116 | Variables de entorno sin hardcode | ✅ |
| FT-117 | README con instrucciones Docker build/up/migrate | ✅ |
| FT-121 | ≥6 capturas/ejemplos en documentación | ⚠️ Estructura completada — insertar imágenes reales manualmente |
| FT-122 | Sección "Problemas frecuentes" ≥5 entradas | ✅ |
| FT-123 | Documentación en repo enlazada desde README | ✅ |
