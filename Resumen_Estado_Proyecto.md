# 🚀 DICCIONARIO DE FUNCIONALIDADES (FT) - GUÍA DEFINITIVA PARA LA EXPOSICIÓN

Este documento es tu **mapa del tesoro**. En él están mapeadas todas y cada una de las "FT" (Functional Tasks / Historias de Usuario) de tu Backlog. Utilízalo durante tu defensa para explicar exactamente dónde está el código, qué hace y qué problema soluciona en la vida real.

---

## 🔒 ÉPICA: Autenticación y Perfiles (FT-10)
**Problema que soluciona:** Una app de fitness no sirve de nada si cualquiera puede ver tu peso o tus entrenamientos. Necesitábamos un sistema de identidad privada y segura.

*   **FT-74 (Login, Registro, Logout) y FT-75 (Rutas Privadas)**
    *   *Dónde está:* `app/Http/Controllers/AuthController.php` y `routes/web.php`.
    *   *Cómo funciona:* En el router usamos `->middleware(['auth'])`. Esto es un muro de contención. Si alguien sin iniciar sesión intenta entrar a `/estadisticas`, el muro lo rebota a `/login`. El AuthController verifica las contraseñas encriptadas.
*   **FT-76, FT-77 y FT-78 (Perfil ampliado, validado y privado)**
    *   *Dónde está:* `app/Http/Controllers/PerfilController.php` y base de datos `users`.
    *   *Cómo funciona:* Añadimos `peso`, `altura` y `foto` a la tabla usuarios. El controlador usa `Auth::id()` para asegurar que cuando le das a "Guardar peso", solo se guarda en TU cuenta y valida que no pongas valores imposibles (ej: peso negativo).
*   **FT-79 (Migraciones de base)**
    *   *Dónde está:* `database/migrations/`.
    *   *Cómo funciona:* Códigos PHP que construyen las tablas de la base de datos automáticamente sin tocar phpMyAdmin.

## 📊 ÉPICA: Dashboard y Gráficos AJAX (FT-9, FT-11)
**Problema que soluciona:** El usuario quiere ver su progreso nada más entrar, de forma visual y atractiva, sin tener que leer listas aburridas de números ni sufrir cargas de página lentas.

*   **FT-68 a FT-73 (Endpoint JSON y carga Asíncrona)**
    *   *Dónde está:* `routes/api.php` y `app/Http/Controllers/MetricaController.php@dashboardData`.
    *   *Cómo funciona:* Creamos una ruta invisible. La página principal no se recarga para mostrar el gráfico, sino que manda un "Fetch" (JavaScript) por detrás, recibe los datos puros en JSON y los dibuja al instante, dándole un toque de aplicación moderna (Single Page Application). Protegido contra caídas (si falla, muestra alerta amarilla en vez de colapsar la app).
*   **FT-80 a FT-84 (Renderización con Chart.js)**
    *   *Dónde está:* `resources/views/inicio.blade.php`.
    *   *Cómo funciona:* Recibe el JSON del controlador e invoca la librería `Chart.js` para pintar una curva de progreso de minutos entrenados. Si el usuario no tiene datos (es nuevo), se previenen errores y el gráfico simplemente sale vacío o avisa de que faltan registros.

## 📅 ÉPICA: Calendario de Entrenamientos (FT-12)
**Problema que soluciona:** A la gente le gusta ver su mes estructurado. Ver huecos en blanco motiva a entrenar para rellenarlos.

*   **FT-85, FT-86, FT-89 y FT-90 (Motor del Calendario)**
    *   *Dónde está:* `resources/views/calendario.blade.php`.
    *   *Cómo funciona:* Integra la potente librería externa `FullCalendar.js`. Se nutre de un endpoint API propio que devuelve solo tus entrenamientos, traduciéndolos a bloques de eventos en español.
*   **FT-87 y FT-88 (Navegabilidad y Click-To-Add)**
    *   *Dónde está:* Evento `dateClick` dentro de `calendario.blade.php` y `registro.blade.php`.
    *   *Cómo funciona:* Si haces click en la celda del "15 de mayo", el calendario captura esa fecha y te manda a `/registro?fecha=2026-05-15`. El formulario de registro lee la URL y autocompleta el campo de fecha. ¡Ahorra clicks al usuario!

## 🎮 ÉPICA: Gamificación, Logros y Rachas (FT-13, FT-94)
**Problema que soluciona:** El fitness requiere retención de usuarios. Mediante medallas y fuego de rachas, creamos un "refuerzo positivo" psicológico que "pica" al usuario a no abandonar su entrenamiento.

*   **FT-91, FT-92 y FT-95 (Catálogo y visualización de Logros)**
    *   *Dónde está:* `app/Http/Controllers/EntrenamientoController.php` y `resources/views/perfil.blade.php`.
    *   *Cómo funciona:* Cada vez que guardas un entreno, el controlador suma todos tus minutos históricos. Si superas un umbral (ej: 1000 mins), desbloqueas automáticamente la medalla en la BD y se pinta a color en tu perfil.
*   **FT-94 (Gestión de Rachas de Fuego)**
    *   *Dónde está:* `app/Console/Commands/VerificarInactividad.php` y `EntrenamientoController`.
    *   *Cómo funciona:* Un campo `racha_actual`. Si entrenas 5 días seguidos, tu fuego sube a 5. El CronJob de Laravel (VerificarInactividad) es un robot nocturno; si detecta que no has entrenado en más de 24h, extingue tu fuego a 0 y te envía un correo avisando.

## 📈 ÉPICA: Filtros Estadísticos y Métricas (FT-14, FT-100, FT-82)
**Problema que soluciona:** El usuario necesita analizar si su esfuerzo de este mes es superior o inferior al del mes pasado.

*   **FT-96 a FT-99 (Matemáticas Comparativas)**
    *   *Dónde está:* `app/Http/Controllers/MetricaController.php`.
    *   *Cómo funciona:* Compara el volumen actual (minutos) con el volumen del periodo anterior y saca un porcentaje verde (+X%) o rojo (-X%).
*   **FT-100 y FT-82 (Filtros Dinámicos)**
    *   *Dónde está:* Selector desplegable en `resources/views/estadisticas.blade.php`.
    *   *Cómo funciona:* Al cambiar de "Semana" a "Mes", se envía el parámetro por GET a la URL (`?periodo=mes`). El controlador lo lee, utiliza `Carbon` (herramienta de fechas de Laravel) para ajustar todas las consultas `whereBetween` y devuelve toda la vista recalculada.

## 🎯 ÉPICA: Sistema de Metas Personales (FT-8)
**Problema que soluciona:** Dar un horizonte claro. El usuario debe poder fijar un objetivo (ej: 10 sesiones al mes) y ver su cumplimiento.

*   **FT-62 y FT-65 (Creación de metas validadas)**
    *   *Dónde está:* `app/Http/Controllers/ObjetivoController.php`.
    *   *Cómo funciona:* Permite guardar un objetivo, exigiendo fecha de inicio y de fin explícitas para no forzar tiempos estrictos.
*   **FT-68 (Progreso Visual)**
    *   *Dónde está:* `resources/views/objetivos/index.blade.php`.
    *   *Cómo funciona:* Una barra de progreso de Bootstrap (`bg-success`) que calcula qué porcentaje llevas completado e incrementa su CSS de ancho (`width: X%`) proporcionalmente.

## 🎨 ÉPICA: Optimización UX (FT-15)
**Problema que soluciona:** La app tenía que sentirse profesional, unificada y no causar confusión visual ni cognitiva.

*   **FT-102 a FT-107 (Microinteracciones de Formulario y Colores dinámicos)**
    *   *Dónde está:* `resources/views/registro.blade.php` (Javascript) y `partials/actividad.blade.php`.
    *   *Cómo funciona:* Si eliges "Fuerza", todo el formulario se tiñe de rojo. Si eliges "Carrera", de verde. Los iconos del inicio hacen lo mismo. Javascript borra y añade las clases de Bootstrap en tiempo real (`classList.add('bg-danger')`). Esto evita que te equivoques de deporte al rellenar los datos.

## 🧪 ÉPICA: Testing Automatizado (QA) (FT-16)
**Problema que soluciona:** Evitar que un cambio en el código rompa las matemáticas del proyecto sin que nos demos cuenta.

*   **FT-108 a FT-112 (Pruebas unitarias y de integración)**
    *   *Dónde está:* `phpunit.xml`, `tests/Feature/MetricaTest.php`.
    *   *Cómo funciona:* Configuramos PHPUnit para que cree una Base de Datos `SQLite` temporal (para no borrar los tuyos reales). Escribimos robots que simulan acceder al controlador de métricas. Validamos que si el usuario tiene 0 entrenos, el servidor devuelva todo a `0` y no de un error mortal (`HTTP 500`). Se lanza todo con `php artisan test`.

## 🐳 ÉPICA: Dockerización (FT-17)
**Problema que soluciona:** Si se lleva la app al servidor de la universidad y no tienen XAMPP o tienen una versión vieja de PHP, nada funciona. Docker crea un ordenador portátil dentro del ordenador.

*   **FT-113 a FT-118 (Contenedores y Volúmenes)**
    *   *Dónde está:* `Dockerfile` y `docker-compose.yml`.
    *   *Cómo funciona:* El `Dockerfile` se descarga Linux, Apache y PHP 8.2 en 30 segundos. El `docker-compose` levanta ese servidor y una base de datos MySQL 8.0 simultáneamente en una red privada, usando un volumen persistente para que los datos sobrevivan a los reinicios. Solo escribes `docker-compose up -d`.

## 📚 ÉPICA: Manuales de Usuario (FT-18)
**Problema que soluciona:** El proyecto debe ser entendible y utilizable por el cliente final sin conocimientos de programación.

*   **FT-119 a FT-123 (Manual Rápido y Troubleshooting)**
    *   *Dónde está:* `MANUAL_USUARIO.md` y `README.md`.
    *   *Cómo funciona:* Un documento nativo en Markdown en la raíz del proyecto. Le explica al usuario los 3 pasos clave de los primeros 10 minutos (perfil, objetivo, entreno) y tiene una tabla maestra solucionando problemas comunes como "Mi racha volvió a 0 sola" (Respuesta: porque el sistema penaliza 24h de inactividad).
