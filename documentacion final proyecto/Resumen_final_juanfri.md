# SinergyFit — Resumen completo del proyecto
### Guía personal de Juanfri para entender qué has construido, cómo funciona y por qué

---

## Índice

1. [¿Qué es este proyecto?](#1-qué-es-este-proyecto)
2. [Tecnologías utilizadas y por qué](#2-tecnologías-utilizadas-y-por-qué)
3. [Estructura de carpetas explicada](#3-estructura-de-carpetas-explicada)
4. [La base de datos: tablas y relaciones](#4-la-base-de-datos-tablas-y-relaciones)
5. [Cómo funciona cada sección de la app](#5-cómo-funciona-cada-sección-de-la-app)
6. [El flujo completo de una petición HTTP](#6-el-flujo-completo-de-una-petición-http)
7. [Conceptos clave que debes conocer](#7-conceptos-clave-que-debes-conocer)
8. [Cómo se construyó el proyecto paso a paso](#8-cómo-se-construyó-el-proyecto-paso-a-paso)
9. [El despliegue con Docker](#9-el-despliegue-con-docker)

---

## 1. ¿Qué es este proyecto?

**SinergyFit** es una aplicación web de seguimiento de fitness construida con el framework PHP **Laravel**. Permite a los usuarios:

- Registrar sesiones de entrenamiento (Fuerza, Carrera, Caminata)
- Llevar un historial paginado y filtrable de sus entrenos
- Ver estadísticas con gráficas interactivas y comparativas por periodo
- Definir objetivos personales y seguir su progreso automáticamente
- Consultar un calendario mensual con todos sus entrenamientos
- Gestionar su perfil con foto y métricas físicas
- Desbloquear logros y mantener rachas de entrenamiento

Es una aplicación **monolítica** (todo en un mismo servidor), con renderizado de páginas en el servidor (**server-side rendering**) usando plantillas Blade, y algunas partes que se actualizan sin recargar la página mediante **AJAX**.

---

## 2. Tecnologías utilizadas y por qué

### Laravel (Framework PHP)
**Qué es:** Un framework para construir aplicaciones web en PHP. Un framework es básicamente un conjunto de herramientas y convenciones que ya están hechas para que no tengas que empezar de cero.

**Por qué se usa aquí:** Laravel gestiona el enrutamiento (qué código ejecutar según la URL), la autenticación, la comunicación con la base de datos (Eloquent ORM), la validación de formularios, las sesiones y mucho más. Sin él, habría que programar todo eso manualmente.

**Versión:** Laravel 11 con PHP 8.2.

---

### Blade (Motor de plantillas)
**Qué es:** El sistema de plantillas de Laravel. En lugar de mezclar HTML con PHP puro (`<?php echo $variable; ?>`), Blade usa una sintaxis más limpia: `{{ $variable }}`.

**Por qué se usa aquí:** Todas las páginas de la aplicación son archivos `.blade.php`. Blade permite heredar layouts, incluir componentes parciales y usar directivas como `@if`, `@foreach`, `@extends` que hacen el código HTML mucho más legible.

**Ejemplo de lo que hace Blade:**
```blade
@extends('layouts.app')          {{-- Hereda el layout base (navbar, estilos...) --}}

@section('contenido')
    @foreach($actividades as $actividad)
        <p>{{ $actividad['tipo'] }}</p>   {{-- Muestra datos sin riesgo de XSS --}}
    @endforeach
@endsection
```

---

### Eloquent ORM
**Qué es:** El sistema de Laravel para hablar con la base de datos sin escribir SQL directamente. ORM significa "Object-Relational Mapper": traduce tablas de base de datos a clases PHP (Modelos) y filas a objetos.

**Por qué se usa aquí:** En lugar de escribir `SELECT * FROM entrenamientos WHERE usuario_id = 5`, se escribe `Entrenamiento::where('usuario_id', 5)->get()`. El resultado es código más legible, más seguro (previene SQL injection automáticamente) y más fácil de mantener.

---

### MySQL 8.0
**Qué es:** El sistema de base de datos relacional donde se almacenan todos los datos de la app. Una base de datos relacional organiza los datos en tablas con filas y columnas, y permite relacionar tablas entre sí.

**Por qué se usa aquí:** Es el estándar en aplicaciones Laravel por su fiabilidad, velocidad y compatibilidad total. Los datos de usuarios, entrenamientos, objetivos y logros viven aquí.

---

### Bootstrap 5
**Qué es:** Una librería CSS y JavaScript que proporciona componentes visuales ya diseñados: botones, tarjetas, modales, formularios, grids responsivos, etc.

**Por qué se usa aquí:** Permite que la app tenga un diseño profesional y responsive (que funcione bien en móvil y escritorio) sin necesidad de diseñar cada elemento desde cero. Todo el aspecto visual de SinergyFit está construido sobre Bootstrap.

---

### Chart.js
**Qué es:** Una librería JavaScript para crear gráficas interactivas en el navegador.

**Por qué se usa aquí:** La página de Estadísticas muestra una gráfica de líneas con la evolución de minutos entrenados y una gráfica donut con la distribución por tipo de entrenamiento. Chart.js las genera con animaciones y tooltips al pasar el ratón.

---

### FullCalendar
**Qué es:** Una librería JavaScript para mostrar calendarios interactivos con eventos.

**Por qué se usa aquí:** La sección de Calendario muestra todos los entrenamientos posicionados en sus fechas, con colores por tipo. FullCalendar gestiona la navegación entre meses, la visualización de eventos y el clic en días para añadir entrenos.

---

### FontAwesome
**Qué es:** Una librería de iconos vectoriales (SVG). Con una clase CSS se puede insertar cualquier icono.

**Por qué se usa aquí:** Todos los iconos de la interfaz (el cohete del onboarding, el trofeo de logros, las flechas, los calendarios, etc.) vienen de FontAwesome.

---

### PHPUnit
**Qué es:** El framework de testing estándar para PHP. Permite escribir pruebas automáticas que verifican que el código funciona correctamente.

**Por qué se usa aquí:** La app tiene 8 tests automáticos que comprueban el cálculo de calorías, la validación de objetivos y el cálculo de estadísticas. Si en el futuro se cambia algo del código, los tests detectan si algo deja de funcionar.

---

### Docker
**Qué es:** Una plataforma que empaqueta una aplicación con todo lo que necesita (PHP, Apache, MySQL...) en contenedores. Un contenedor es como una máquina virtual muy ligera.

**Por qué se usa aquí:** Permite desplegar la app en cualquier máquina sin instalar PHP, Apache ni MySQL manualmente. Con `docker-compose up --build` toda la infraestructura se levanta automáticamente.

---

## 3. Estructura de carpetas explicada

```
fitnesstracker-laravel/
│
├── app/                          ← El corazón de la aplicación
│   ├── Console/Commands/         ← Comandos artisan personalizados
│   ├── Http/
│   │   ├── Controllers/          ← Lógica de negocio (reciben peticiones, procesan, responden)
│   │   └── Middleware/           ← Filtros que se ejecutan antes/después de cada petición
│   ├── Models/                   ← Clases que representan tablas de la BD
│   ├── Notifications/            ← Notificaciones por email/SMS
│   └── Providers/                ← Arranque y configuración del framework
│
├── bootstrap/                    ← Archivos de inicialización de Laravel (no tocar)
│   └── cache/                    ← Caches de configuración y rutas generadas por artisan
│
├── config/                       ← Archivos de configuración
│   ├── app.php                   ← Nombre app, zona horaria, idioma, providers
│   ├── auth.php                  ← Cómo funciona la autenticación
│   ├── cache.php                 ← Driver de caché (file, redis...)
│   ├── database.php              ← Conexiones a bases de datos
│   └── session.php               ← Cómo se gestionan las sesiones
│
├── database/
│   ├── factories/                ← Generadores de datos de prueba para tests
│   ├── migrations/               ← Historial de cambios en la estructura de la BD
│   └── seeders/                  ← Scripts para poblar la BD con datos iniciales
│
├── documentacion final proyecto/ ← Documentación académica del proyecto
│
├── imagenes/                     ← Assets de imágenes (fondos, logos)
│
├── public/                       ← La única carpeta accesible desde internet
│   ├── index.php                 ← Punto de entrada de toda la app
│   ├── vendor/                   ← Assets JS/CSS descargados (Bootstrap, Chart.js...)
│   └── storage -> storage/app/public  ← Symlink a los archivos subidos por usuarios
│
├── resources/
│   └── views/                    ← Todas las plantillas Blade (lo que ve el usuario)
│       ├── layouts/app.blade.php ← Layout base con navbar, estilos y scripts comunes
│       ├── partials/             ← Componentes reutilizables (navbar, sidebar, actividad)
│       ├── auth/                 ← Vistas de login y registro
│       ├── objetivos/            ← Vistas de la sección de metas
│       ├── inicio.blade.php      ← Dashboard principal
│       ├── estadisticas.blade.php← Página de estadísticas y gráficas
│       ├── entrenamientos.blade.php ← Historial de entrenamientos
│       ├── registro.blade.php    ← Formulario de nuevo entrenamiento
│       ├── editar.blade.php      ← Formulario de editar entrenamiento
│       ├── calendario.blade.php  ← Vista de calendario
│       └── perfil.blade.php      ← Página de perfil de usuario
│
├── routes/
│   ├── web.php                   ← Todas las rutas de la aplicación web
│   ├── api.php                   ← Rutas de la API (mínimas, solo Sanctum)
│   └── console.php               ← Rutas de comandos artisan
│
├── storage/
│   ├── app/public/               ← Archivos subidos por usuarios (fotos de perfil)
│   ├── framework/
│   │   ├── cache/                ← Datos cacheados
│   │   ├── sessions/             ← Sesiones de usuario
│   │   └── views/                ← Plantillas Blade compiladas a PHP puro
│   └── logs/laravel.log          ← Log de errores de la aplicación
│
├── tests/
│   └── Feature/                  ← Tests de integración (prueban rutas y controladores)
│       ├── CaloriaTest.php
│       ├── MetricaTest.php
│       └── ObjetivoTest.php
│
├── .env                          ← Variables de entorno (credenciales, configuración local)
├── Dockerfile                    ← Instrucciones para construir la imagen Docker
├── docker-compose.yml            ← Orquestación de los contenedores
├── entrypoint.sh                 ← Script de arranque del contenedor
└── artisan                       ← CLI de Laravel
```

---

## 4. La base de datos: tablas y relaciones

La base de datos tiene **8 tablas propias** (más algunas de sistema de Laravel):

### Tabla `usuarios`
Almacena los usuarios registrados. Sus campos más importantes:

| Campo | Tipo | Para qué sirve |
|---|---|---|
| `id` | INT | Identificador único |
| `nombre` | VARCHAR | Nombre del usuario |
| `correo` | VARCHAR | Email (usado para login) |
| `contrasena` | VARCHAR | Contraseña encriptada con bcrypt |
| `peso`, `altura`, `edad`, `genero` | Numéricos | Métricas físicas |
| `grasa` | DECIMAL | % grasa corporal estimado |
| `foto` | VARCHAR | Ruta de la foto de perfil |
| `nivel_actividad` | VARCHAR | Sedentario / Moderado / Activo |
| `racha_actual` | INT | Días consecutivos entrenando ahora mismo |
| `mejor_racha` | INT | Récord histórico de racha |
| `ultima_actividad_fecha` | DATE | Último día que entrenó |

### Tabla `entrenamientos`
Cada fila es una sesión de entrenamiento.

| Campo | Para qué sirve |
|---|---|
| `usuario_id` | A qué usuario pertenece (clave foránea → usuarios) |
| `fecha` | Cuándo fue el entrenamiento |
| `tipo` | 'Fuerza', 'Carrera' o 'Caminata' |
| `duracion_minutos` | Cuánto duró |
| `calorias_estimadas` | Calorías calculadas automáticamente |
| `notas` | Texto libre (sensación, distancia, kcal) |

### Tabla `entrenamiento_detalles`
Para entrenamientos de Fuerza: detalla cada ejercicio dentro de la sesión.

| Campo | Para qué sirve |
|---|---|
| `entrenamiento_id` | A qué sesión pertenece |
| `ejercicio_id` | Qué ejercicio fue |
| `grupo_muscular` | Pecho, Piernas, Espalda... |
| `series` | Número de series |
| `repeticiones` | Reps por serie |
| `carga_kg` | Peso utilizado |

### Tabla `ejercicios`
Catálogo de ejercicios disponibles. Si un ejercicio no existe, se crea automáticamente con `firstOrCreate()`.

### Tabla `objetivos`
Metas que el usuario se pone a sí mismo.

| Campo | Para qué sirve |
|---|---|
| `tipo_objetivo` | 'Días Entrenados', 'Volumen (kg levantados)', 'Peso Corporal' |
| `valor_objetivo` | La meta numérica (ej: 20 días, 5000 kg, 70 kg) |
| `progreso` | Progreso actual calculado dinámicamente |
| `fecha_inicio` / `fecha_limite` | Rango de fechas del objetivo |
| `estado` | 'en_progreso', 'completado' |

### Tabla `metricas`
Historial de peso y altura del usuario a lo largo del tiempo.

### Tabla `logros`
Catálogo fijo de los 5 logros desbloqueables:
1. ¡Primer Paso! — Primer entrenamiento registrado
2. Constancia Pura — Racha de 3 días consecutivos
3. Levantador NATO — 5 sesiones de Fuerza
4. Maratonista — Carrera de 10km o más
5. Leyenda del Sudor — 1.000 minutos totales entrenados

### Tabla `logro_usuario`
Tabla intermedia (pivot) que registra qué logros ha desbloqueado cada usuario y cuándo.

### Relaciones entre tablas

```
usuarios ──< entrenamientos ──< entrenamiento_detalles >── ejercicios
usuarios ──< objetivos
usuarios ──< metricas
usuarios >──< logros  (a través de logro_usuario)
```

`──<` significa "uno a muchos" (un usuario tiene muchos entrenamientos)
`>──<` significa "muchos a muchos" (un usuario puede tener varios logros, y un logro puede ser de varios usuarios)

---

## 5. Cómo funciona cada sección de la app

### Autenticación (Login / Registro)
**Archivos:** `AuthController.php`, `login.blade.php`, `auth/register.blade.php`

Cuando un usuario envía el formulario de login, `AuthController::login()` valida las credenciales contra la tabla `usuarios`. Si son correctas, Laravel crea una **sesión** (un archivo en el servidor que recuerda quién está conectado) y redirige al dashboard.

Todas las páginas protegidas están bajo el middleware `auth`, que comprueba en cada petición que existe una sesión válida. Si no la hay, redirige al login.

La contraseña nunca se guarda en texto plano — Laravel la encripta con **bcrypt** al registrarse y la compara con `Hash::check()` al hacer login.

---

### Dashboard (Inicio)
**Archivos:** `EntrenamientoController::index()`, `inicio.blade.php`

Al cargar el dashboard:
1. El controlador consulta los últimos 5 entrenamientos del usuario con `with(['detalles', 'detalles.ejercicio'])` — esto es **eager loading**, carga los detalles de cada ejercicio en una sola consulta adicional (evita el problema N+1).
2. La vista renderiza esas actividades con el partial `partials/actividad.blade.php`.
3. El mini-gráfico de progreso semanal se carga después mediante **AJAX**: el JavaScript hace un `fetch('/api/metricas/dashboard')` y cuando llega la respuesta, dibuja la gráfica con Chart.js.
4. Si el usuario acaba de desbloquear un logro, la sesión flash lleva `logros_nuevos` y se muestra el modal de celebración con confetti.

---

### Registro de entrenamiento
**Archivos:** `EntrenamientoController::store()`, `registro.blade.php`

El formulario tiene tres modos según el tipo elegido (Fuerza / Carrera / Caminata). Al enviar:

1. Se valida que `fecha`, `modulo` y `tiempo` estén presentes y sean válidos.
2. Se calculan las calorías estimadas:
   - Carrera: `minutos × 11 kcal/min`
   - Caminata: `minutos × 4.5 kcal/min`
   - Fuerza: `minutos × 6.5 kcal/min`
3. Se guarda el entrenamiento en `entrenamientos`.
4. Si es Fuerza, se guardan los ejercicios individuales en `entrenamiento_detalles`. Si el ejercicio no existe en el catálogo, se crea con `firstOrCreate()`.
5. Se actualiza la **racha**: si el último entreno fue ayer, la racha sube 1; si fue hace más días, se reinicia a 1.
6. Se comprueban los **logros** en orden y se asignan con `attach()` si se cumplen los criterios.
7. Se redirige al dashboard con un mensaje flash de éxito.

---

### Historial
**Archivos:** `EntrenamientoController::historial()`, `entrenamientos.blade.php`

Muestra todos los entrenamientos con **paginación** (20 por página). Acepta un parámetro `?tipo=Fuerza` para filtrar. La paginación la gestiona Eloquent automáticamente con `->paginate(20)`.

---

### Estadísticas
**Archivos:** `MetricaController.php`, `estadisticas.blade.php`

Es la página más compleja. Al cargar:
1. El controlador recibe el parámetro `?periodo=semana|mes|anio`.
2. Calcula los totales globales, los del periodo actual y los del periodo anterior para la comparativa.
3. Calcula el progreso de cada objetivo activo iterando sobre ellos (cada tipo tiene su propia lógica SQL).
4. Pasa todo a la vista, que renderiza las tarjetas de resumen, la comparativa porcentual y los contenedores de las gráficas.
5. El JavaScript carga las gráficas de forma **asíncrona** (AJAX) llamando a `/api/metricas/dashboard?periodo=X` y `/api/metricas/tipos`.

La comparativa usa esta fórmula:
```
tendencia% = ((minutos_actual - minutos_anterior) / minutos_anterior) × 100
```
Si el periodo anterior era 0 y el actual tiene datos → 100% de mejora.

---

### Objetivos
**Archivos:** `ObjetivoController.php`, `objetivos/index.blade.php`

Cuando se carga la página de objetivos, el controlador recorre cada objetivo del usuario y calcula su progreso real consultando la base de datos:
- **Días Entrenados:** cuenta los días distintos con al menos un entrenamiento dentro del rango de fechas del objetivo.
- **Volumen (kg levantados):** suma `carga_kg × series × repeticiones` de todos los detalles de entreno en el rango.
- **Peso Corporal:** obtiene el último peso registrado en `metricas` y lo compara con el objetivo.

Si el progreso llega al 100%, el estado se marca automáticamente como `completado`.

---

### Calendario
**Archivos:** `EntrenamientoController::calendario()`, `EntrenamientoController::eventosAPI()`, `calendario.blade.php`

La vista carga FullCalendar, que hace una petición AJAX a `/api/calendario/eventos`. El controlador devuelve un JSON con todos los entrenamientos del usuario en el formato que FullCalendar espera:
```json
[
  { "title": "Fuerza (45m)", "start": "2026-05-15", "url": "/entrenamientos/12/edit", "color": "#dc3545" }
]
```
FullCalendar posiciona cada evento en su día, con el color correspondiente al tipo. Al hacer clic en un día vacío, redirige a `/registro?fecha=YYYY-MM-DD` con la fecha preseleccionada.

---

### Perfil
**Archivos:** `PerfilController.php`, `perfil.blade.php`

Permite editar datos personales y métricas físicas. La foto se sube con `$request->file('foto')->store('avatars', 'public')`, que la guarda en `storage/app/public/avatars/` y es accesible en la web a través del symlink `public/storage`.

---

### Gamificación (Logros y Rachas)
**Archivos:** `EntrenamientoController::store()`, `Logro.php`, `LogroSeeder.php`

**Rachas:** En cada registro de entrenamiento se compara la fecha del entreno con `ultima_actividad_fecha`. Si fue ayer: racha +1. Si fue hace más días: racha = 1. Si fue hoy: no cambia. Si la racha supera `mejor_racha`, se actualiza.

**Logros:** Se comprueban 5 criterios después de guardar cada entrenamiento:
1. ¿Es el primer entrenamiento del usuario? → Logro "¡Primer Paso!"
2. ¿Tiene 5 o más sesiones de Fuerza? → Logro "Levantador NATO"
3. ¿Es una carrera de ≥10 km? → Logro "Maratonista"
4. ¿Lleva ≥1000 minutos totales? → Logro "Leyenda del Sudor"
5. ¿Tiene racha ≥3 días? → Logro "Constancia Pura"

Si se cumple alguno y el usuario no lo tiene ya, se hace `$usuario->logros()->attach($logro->id)`. Los logros nuevos se pasan a la vista con sesión flash y se muestra el modal de celebración.

---

## 6. El flujo completo de una petición HTTP

Para entender cómo funciona Laravel, es útil seguir el recorrido de una petición desde que el usuario hace clic hasta que ve la respuesta:

```
1. Usuario hace clic en "Estadísticas"
         ↓
2. El navegador envía: GET http://localhost:8000/estadisticas
         ↓
3. Apache recibe la petición y la pasa a public/index.php
         ↓
4. index.php arranca Laravel (carga configuración, servicios...)
         ↓
5. Laravel busca en routes/web.php una ruta que coincida con GET /estadisticas
   → Encuentra: Route::get('/estadisticas', [MetricaController::class, 'index'])
         ↓
6. Antes de llegar al controlador, pasa por los Middleware:
   → auth: ¿hay sesión activa? Sí → continúa. No → redirige a /login
         ↓
7. Laravel llama a MetricaController::index($request)
   → El controlador consulta la BD con Eloquent
   → Calcula estadísticas, tendencias, objetivos
   → Devuelve: return view('estadisticas', [...datos...])
         ↓
8. Blade compila estadisticas.blade.php con los datos
   → Hereda layouts/app.blade.php (navbar, Bootstrap, FontAwesome)
   → Genera el HTML final
         ↓
9. Laravel envía el HTML como respuesta HTTP 200
         ↓
10. El navegador renderiza la página
    → El JavaScript ejecuta fetch('/api/metricas/dashboard')
    → Llega el JSON → Chart.js dibuja las gráficas
```

---

## 7. Conceptos clave que debes conocer

### MVC (Modelo-Vista-Controlador)
Laravel sigue el patrón MVC. Es una forma de organizar el código en tres capas:
- **Modelo** (`app/Models/`): sabe cómo hablar con la base de datos
- **Vista** (`resources/views/`): sabe cómo mostrar información al usuario
- **Controlador** (`app/Http/Controllers/`): recibe las peticiones, usa los modelos para obtener datos y se los pasa a las vistas

La idea es que cada parte tenga una sola responsabilidad y no se mezclen.

---

### Migraciones
Son archivos PHP que describen cambios en la estructura de la base de datos. En lugar de ejecutar SQL directamente en phpMyAdmin, se crean migraciones con `php artisan make:migration`.

**¿Por qué?** Porque las migraciones son código versionado con Git. Si alguien clona el proyecto, ejecuta `php artisan migrate` y obtiene exactamente la misma BD que tienes tú, sin importar en qué máquina esté.

Tu proyecto tiene 19 migraciones que cuentan la historia de cómo evolucionó la BD: desde la tabla inicial de usuarios hasta añadir campos de racha, calorías, traducir columnas al español, etc.

---

### Eloquent y las relaciones
Cuando en un modelo defines:
```php
public function entrenamientos() {
    return $this->hasMany(Entrenamiento::class);
}
```
Estás diciéndole a Laravel: "un Usuario tiene muchos Entrenamientos, y se relacionan por el campo `usuario_id`". Luego puedes hacer `$usuario->entrenamientos` y Eloquent construye y ejecuta el SQL por ti.

Las relaciones en tu proyecto:
- `hasMany`: uno-a-muchos (Usuario → Entrenamientos)
- `belongsTo`: muchos-a-uno (Entrenamiento → Usuario)
- `belongsToMany`: muchos-a-muchos (Usuario ↔ Logros, a través de `logro_usuario`)

---

### Eager Loading y el problema N+1
Imagina que tienes 10 entrenamientos y quieres mostrar los ejercicios de cada uno. Sin eager loading:
- 1 consulta para obtener los 10 entrenamientos
- 10 consultas para obtener los ejercicios de cada uno
- **Total: 11 consultas** (N+1 problema)

Con eager loading (`->with(['detalles', 'detalles.ejercicio'])`):
- 1 consulta para los entrenamientos
- 1 consulta para todos los detalles
- 1 consulta para todos los ejercicios
- **Total: 3 consultas** siempre, sin importar cuántos entrenos haya

---

### Middleware
Son filtros que se ejecutan antes o después de que una petición llegue al controlador. Los más importantes de tu app:
- `auth`: comprueba que el usuario está logueado
- `guest`: solo deja pasar a usuarios NO logueados (para login/registro)
- `throttle:5,1`: limita a 5 intentos de login por minuto (protección contra ataques de fuerza bruta)

---

### Sesiones y Autenticación
Cuando un usuario hace login, Laravel crea un archivo de sesión en `storage/framework/sessions/`. Ese archivo guarda el ID del usuario. En cada petición, Laravel lee la cookie del navegador, busca el archivo de sesión correspondiente y sabe quién es el usuario.

`Auth::id()` devuelve el ID del usuario en sesión. `Auth::user()` devuelve el objeto usuario completo.

---

### AJAX y APIs JSON
AJAX permite que una página actualice partes de sí misma sin recargar toda la página. En tu app se usa para:
- Cargar las gráficas de Chart.js sin bloquear el renderizado inicial
- Cargar los eventos del calendario
- Actualizar las estadísticas al cambiar el filtro de periodo

El navegador ejecuta `fetch('/api/metricas/dashboard')`, el servidor devuelve un JSON con los datos, y JavaScript lo procesa para dibujar la gráfica.

---

### Factories y Seeders
**Factories:** Generan datos falsos pero realistas para tests. `UsuarioFactory` crea un usuario con nombre, email y contraseña aleatorios.

**Seeders:** Ejecutan código para poblar la BD. `DatabaseSeeder` crea usuarios de prueba, ejercicios del catálogo y entrenamientos de ejemplo. Se ejecutan con `php artisan db:seed`.

---

### Variables de entorno (.env)
El archivo `.env` guarda configuración que cambia entre entornos (desarrollo, producción). Las credenciales de la base de datos, la clave de la app, el modo debug... Nunca se sube a Git (está en `.gitignore`) para no exponer datos sensibles.

En el código, se accede con `env('DB_DATABASE')` o a través de los archivos de `config/`.

---

## 8. Cómo se construyó el proyecto paso a paso

Esta es la secuencia lógica de construcción, de más básico a más complejo:

### Paso 1 — Instalación y configuración inicial
```bash
composer create-project laravel/laravel fitnesstracker-laravel
```
Se instaló Laravel, se configuró el `.env` con las credenciales de MySQL de XAMPP y se generó la clave de la app con `php artisan key:generate`.

### Paso 2 — Modelo de datos y migraciones
Se diseñó el esquema de la base de datos y se crearon las migraciones:
```bash
php artisan make:migration create_entrenamientos_table
php artisan make:migration create_objetivos_table
# ... etc para cada tabla
php artisan migrate
```
Las migraciones evolucionaron durante el desarrollo: se añadieron campos (calorías, racha, foto...) con migraciones adicionales en lugar de modificar las originales.

### Paso 3 — Modelos Eloquent
Se creó un modelo por cada tabla con sus relaciones:
```bash
php artisan make:model Entrenamiento
php artisan make:model Objetivo
# ... etc
```
El modelo `Usuario` fue especial: en lugar de extender el `User` de Laravel, se construyó desde cero con campos en español (`correo`, `contrasena`) porque la BD usa nombres en castellano.

### Paso 4 — Autenticación
Se construyó `AuthController` con los métodos de login, registro y logout. Se configuró en `config/auth.php` que el modelo de usuario es `Usuario` y el campo de contraseña es `contrasena`.

### Paso 5 — Rutas y controladores básicos
Se definieron las rutas en `routes/web.php` y se crearon los controladores para cada sección. El orden fue: registro de entrenamiento → historial → estadísticas → objetivos → perfil → calendario.

### Paso 6 — Vistas Blade
Se creó el layout base (`layouts/app.blade.php`) con Bootstrap, navbar y sidebar. Luego cada página heredó ese layout con `@extends('layouts.app')`.

### Paso 7 — Lógica de negocio
Se fueron añadiendo las funcionalidades más complejas:
- Cálculo de calorías por tipo de entrenamiento
- Guardado de múltiples ejercicios por sesión (detalles)
- Sistema de rachas con comparación de fechas con Carbon
- Cálculo dinámico de progreso de objetivos
- Estadísticas con comparativa de periodos

### Paso 8 — Gráficas e interactividad
Se integraron Chart.js y FullCalendar, se crearon los endpoints AJAX (`/api/metricas/dashboard`, `/api/metricas/tipos`, `/api/calendario/eventos`) y se añadió el JavaScript en las vistas para consumirlos.

### Paso 9 — Gamificación
Se crearon la tabla `logros`, el seeder `LogroSeeder`, la relación muchos-a-muchos con `logro_usuario` y la lógica de comprobación de criterios en `EntrenamientoController::store()`. Se añadió el modal de celebración con confetti.

### Paso 10 — Tests
Se escribieron los tests de PHPUnit para las funcionalidades críticas: cálculo de calorías, validación de objetivos, estadísticas con y sin datos.

### Paso 11 — Docker
Se creó el `Dockerfile` con PHP 8.2 + Apache, el `docker-compose.yml` con los servicios de app, BD y phpMyAdmin, y el `entrypoint.sh` para los caches de Laravel.

### Paso 12 — Optimización de rendimiento
Se detectó que Docker en Windows era lento por el volume mount WSL2. Se eliminó el mount (dejando el código en el filesystem del contenedor), se añadió OPcache, se descargaron los assets externos localmente y se configuró modo producción.

---

## 9. El despliegue con Docker

### Qué hace cada contenedor

**`fitness_app`** (el servidor web):
- Imagen base: `php:8.2-apache`
- Contiene: todo el código de la app, PHP con OPcache, Apache configurado para Laravel, todos los assets JS/CSS (Bootstrap, FontAwesome, Chart.js, etc.)
- Puerto: 8000 → escucha peticiones HTTP
- Al arrancar ejecuta `entrypoint.sh`: genera los caches de Laravel y luego lanza Apache

**`fitness_db`** (la base de datos):
- Imagen: `mysql:8.0`
- Contiene: el servidor MySQL con la base de datos `fitnesstracker`
- Puerto: 3306 (solo accesible desde dentro de la red Docker)
- Los datos persisten en el volumen `fitness_db_data`

**`fitness_pma`** (phpMyAdmin):
- Imagen: `phpmyadmin/phpmyadmin`
- Interfaz web para gestionar la BD visualmente
- Puerto: 8080

### Por qué no hay volume mount de código
El `Dockerfile` ya copia todo el código con `COPY . /var/www/html`. Si se montara la carpeta de Windows encima, PHP tendría que leer cada archivo a través del puente WSL2 (Windows → Linux), lo que añade 7-9 segundos por petición. Sin el mount, PHP lee del filesystem nativo de Linux del contenedor → respuesta en <200ms.

### Los caches de Laravel
El `entrypoint.sh` ejecuta al arrancar:
- `php artisan config:cache` → une todos los archivos de `config/` en uno solo
- `php artisan route:cache` → serializa todas las rutas en un archivo PHP
- `php artisan view:cache` → compila todas las plantillas Blade a PHP puro
- `php artisan storage:link` → crea el symlink `public/storage → storage/app/public`

Esto significa que en cada petición, Laravel no necesita leer ni parsear esos archivos: los tiene ya compilados en memoria gracias a OPcache.

---

## Glosario rápido de términos

| Término | Significado |
|---|---|
| **Framework** | Conjunto de herramientas y convenciones que aceleran el desarrollo |
| **ORM** | Capa que traduce tablas de BD a objetos PHP y viceversa |
| **Migración** | Archivo que describe un cambio en la estructura de la BD, versionado con Git |
| **Seeder** | Script que inserta datos iniciales o de prueba en la BD |
| **Factory** | Generador de datos falsos para tests |
| **Middleware** | Filtro que intercepta peticiones HTTP antes o después del controlador |
| **Blade** | Motor de plantillas de Laravel para generar HTML |
| **AJAX** | Técnica para actualizar partes de una página sin recargarla |
| **Eager Loading** | Cargar relaciones de BD en pocas consultas para evitar N+1 |
| **N+1** | Antipatrón en el que se hacen N consultas extra dentro de un bucle |
| **Sesión** | Mecanismo para recordar al usuario entre peticiones HTTP |
| **OPcache** | Extensión de PHP que cachea el bytecode compilado para no recompilar en cada petición |
| **Docker** | Plataforma de contenedores para empaquetar apps con sus dependencias |
| **Volume** | Carpeta compartida entre el host y un contenedor Docker |
| **bcrypt** | Algoritmo de hash para almacenar contraseñas de forma segura |
| **CSRF** | Ataque que ejecuta acciones en nombre del usuario; Laravel lo previene con tokens |
| **Pivot table** | Tabla intermedia que implementa relaciones muchos-a-muchos |
| **Flash message** | Mensaje que se guarda en sesión para mostrarse solo en la siguiente petición |

---

*Documento generado el 2026-05-19 como resumen técnico y guía de aprendizaje personal del proyecto SinergyFit.*
