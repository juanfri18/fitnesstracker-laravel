# Guion de Presentación — SinergyFit
### Proyecto Final de Desarrollo de Aplicaciones Web

---

## DIAPOSITIVA 1 — Introducción: ¿Qué es SinergyFit y por qué existe?

### Lo que dices:

Buenas, mi proyecto se llama **SinergyFit** y es una aplicación web de seguimiento de fitness.

La idea nació de una necesidad real. Soy una persona que entrena habitualmente y me di cuenta de que las aplicaciones que existen en el mercado —como Strava— tienen un problema: son o muy completas pero complicadas de usar, o muy sencillas pero demasiado limitadas. Por ejemplo, hay apps que para los entrenamientos de fuerza solo te ofrecen un cronómetro y un cálculo estimado de calorías quemadas. Nada más. No te dejan registrar qué ejercicios hiciste, cuánto peso levantaste ni cuántas series.

Eso me motivó. Quería algo que combinase la sencillez de uso con la profundidad suficiente para llevar un control real del entrenamiento. Algo que me permitiera registrar cada ejercicio concreto, con su peso, sus series y sus repeticiones, para poder ver mi evolución a lo largo del tiempo. Porque uno de los problemas más comunes cuando entrenas en el gimnasio es quedarte estancado sin darte cuenta. Si tienes un registro, puedes ver cómo empezaste hace tres meses y cómo estás ahora, y eso es lo que convierte una rutina en un hábito.

Así que empecé a construir esto, pensando en los tres tipos de actividad que más gente practica: **fuerza, carrera y caminata**.

---

## DIAPOSITIVA 2 — ¿Por qué SinergyFit? El nombre

### Lo que dices:

Este proyecto no siempre se llamó así. En sus inicios tenía un nombre predeterminado que me asignó mi profesora: **FitnessTracker**. La traducción sería algo así como "seguidor de actividad". Un nombre correcto, pero genérico. No tenía alma.

Cuando empecé a trabajar en el diseño de la aplicación, decidí que quería un nombre que tuviese significado real, que conectase con la intención del proyecto. Me puse a buscar y llegué al concepto de **sinergia**.

La sinergia describe cómo la cooperación entre dos o más elementos produce un resultado superior a la suma de sus partes por separado. "El todo es mayor que la suma de sus partes."

¿Qué tiene que ver esto con el fitness? Todo. En el mundo del deporte y la salud, está demostrado que no es suficiente con hacer solo cardio o solo fuerza. Lo óptimo es **combinarlos**: el entrenamiento cardiovascular mejora la resistencia y la salud del corazón, el entrenamiento de fuerza aumenta la masa muscular y el metabolismo. Cuando los combinas, el resultado es mucho mejor que si hicieras solo uno de los dos. Eso es sinergia aplicada al deporte.

De ahí **SinergyFit**: la unión de *sinergia* y *fitness*, porque la aplicación existe precisamente para ayudarte a combinar y registrar todos tus tipos de entrenamiento en un solo lugar.

---

## DIAPOSITIVA 3 — Frontend: ¿Cómo es la aplicación por dentro?

### Lo que dices:

La aplicación está formada por **7 secciones principales**, cada una con una función clara.

---

### **Página de Inicio — El Dashboard**
Es lo primero que ves al hacer login. Te muestra de un vistazo lo más importante: tus últimos 5 entrenamientos registrados, tu racha de días consecutivos entrenando, los logros que has desbloqueado y un mini-gráfico con tu actividad de los últimos 7 días. La idea es que en 5 segundos sepas cómo va tu semana.

---

### **Registrar una Actividad**
El formulario central de la app. Aquí registras cada sesión de entrenamiento. El formulario cambia según el tipo que elijas:
- **Fuerza:** puedes añadir todos los ejercicios que hiciste, con el grupo muscular, las series, las repeticiones y el peso utilizado.
- **Carrera o Caminata:** introduces la duración y opcionalmente las notas (distancia, sensaciones...).

Las calorías se calculan automáticamente en base al tipo y la duración.

---

### **Historial de Entrenamientos**
Una lista paginada de todos tus entrenamientos. Puedes filtrar por tipo (solo Fuerza, solo Carrera, etc.) y desde aquí puedes editar o eliminar cualquier sesión. Muestra 20 entrenamientos por página.

---

### **Estadísticas**
La página más visual. Tienes:
- Totales globales: minutos entrenados, número de sesiones.
- Una comparativa con el periodo anterior (semana, mes o año) que te dice si estás mejorando o bajando el ritmo, expresado en porcentaje.
- Una gráfica de líneas con tu evolución de minutos entrenados.
- Una gráfica donut con el reparto por tipo de entrenamiento.
- El progreso de tus objetivos activos.

---

### **Objetivos (Metas)**
Puedes definirte metas personales de tres tipos:
- **Días entrenados** en un rango de fechas (ej: entrenar 20 días en mayo).
- **Volumen levantado** en kg (ej: levantar 5.000 kg en el mes).
- **Peso corporal** objetivo (ej: llegar a 75 kg).

El progreso se calcula automáticamente en cada visita comparando tus registros con la meta. Cuando llegas al 100%, el objetivo se marca como completado.

---

### **Calendario**
Un calendario mensual interactivo donde cada entrenamiento aparece posicionado en su fecha, con un color distinto según el tipo (rojo para Fuerza, azul para Carrera, verde para Caminata). Puedes navegar entre meses y hacer clic en cualquier día para registrar un entreno con esa fecha ya preseleccionada.

---

### **Mi Perfil**
Aquí gestionas tus datos personales: nombre, correo, foto de perfil y tus métricas físicas (peso, altura, edad, nivel de actividad). Cada vez que actualizas tu peso, se guarda en un historial para poder ver tu evolución corporal a lo largo del tiempo.

---

## DIAPOSITIVA 4 — Backend: ¿Cómo funciona SinergyFit por dentro?

### Lo que dices:

SinergyFit está construida sobre **Laravel**, un framework de PHP. Pero antes de explicar qué es Laravel, conviene entender qué es un framework.

Un **framework** es como una caja de herramientas ya hecha. En lugar de construir desde cero el sistema de login, el acceso a la base de datos, la validación de formularios o el enrutamiento de URLs, Laravel ya los tiene resueltos y bien testados. Tú solo te centras en la lógica de tu aplicación.

Laravel utiliza un patrón de arquitectura llamado **MVC: Modelo - Vista - Controlador**.

---

### ¿Qué es el patrón MVC?

Imagina que la aplicación está dividida en tres capas, cada una con una responsabilidad única:

**MODELO — `app/Models/`**
Es la capa que habla con la base de datos. Cada tabla de la base de datos tiene su modelo en PHP. Por ejemplo, el modelo `Entrenamiento` representa la tabla `entrenamientos`. En lugar de escribir SQL directamente, usas el modelo: `Entrenamiento::where('usuario_id', 5)->get()` y Laravel construye y ejecuta la consulta por ti. Esto se llama **Eloquent ORM**.

**VISTA — `resources/views/`**
Es todo lo que ve el usuario: el HTML. Las vistas en Laravel son plantillas **Blade**, que permiten mezclar HTML con datos de forma limpia y segura. Una vista no hace cálculos ni consultas a la base de datos, solo muestra lo que le llega.

**CONTROLADOR — `app/Http/Controllers/`**
Es el intermediario. Recibe la petición del usuario, le pide los datos al Modelo, y se los pasa a la Vista para que los muestre. Toda la lógica de negocio vive aquí.

---

### ¿Cómo funciona una petición completa? Ejemplo real:

Cuando el usuario hace clic en "Estadísticas":

```
1. El navegador envía:  GET /estadisticas
2. Laravel busca en routes/web.php qué hacer con esa URL
3. Pasa primero por el middleware "auth": ¿hay sesión activa? → Sí, continúa
4. Laravel llama a MetricaController::index()
5. El controlador consulta la BD con Eloquent (totales, tendencias, objetivos...)
6. Devuelve la vista con los datos calculados
7. Blade genera el HTML final
8. El navegador renderiza la página y lanza las peticiones AJAX para los gráficos
```

La base de datos de SinergyFit tiene **8 tablas**: usuarios, entrenamientos, entrenamiento_detalles, ejercicios, objetivos, metricas, logros y logro_usuario.

---

## DIAPOSITIVA 5 — Problemas y Complicaciones

### Lo que dices:

Ningún proyecto sale perfecto a la primera. Estos son los principales obstáculos que encontré durante el desarrollo.

---

### **Problema 1 — Empezar con PHP nativo**

El proyecto comenzó construyéndose en **PHP puro**, sin ningún framework. Al principio parece una buena idea porque tienes control total, pero rápidamente se vuelve un problema:

- Cada página era un archivo PHP que mezclaba lógica de base de datos, validaciones, HTML y CSS en el mismo sitio. El código se volvía imposible de mantener.
- No había sistema de autenticación: había que programar el login desde cero, gestionar las sesiones manualmente, prevenir ataques SQL injection línea a línea.
- Añadir cualquier nueva funcionalidad implicaba replicar código que ya existía en otras páginas.

Llegó un punto en que el código era tan enredado que resultaba más difícil añadir cosas nuevas que hacerlas desde cero. Ahí tomé la decisión de migrar.

---

### **Problema 2 — La migración a Laravel**

Migrar no es simplemente "copiar el código". Hay que reaprender cómo se organizan las cosas:

- Toda la lógica de base de datos pasa a **migraciones** (archivos que describen la estructura de las tablas, versionados con Git).
- Las consultas SQL se reescriben como llamadas **Eloquent**.
- Las páginas se reorganizan en **controladores y vistas Blade**.
- El sistema de autenticación, validación y sesiones lo gestiona Laravel de forma segura por defecto.

El proceso fue reescribir la aplicación entera, pero el resultado fue una base mucho más sólida y un código infinitamente más limpio y mantenible.

---

### **Problema 3 — Docker: de 10-18 segundos por clic a menos de 200ms**

Este fue sin duda el problema más técnico. Al desplegar la aplicación en Docker para tener un entorno reproducible, cada vez que navegaba por la web tardaba **entre 10 y 18 segundos por clic**. La aplicación era prácticamente inutilizable.

Después de investigar, descubrí que no era un problema, sino **cinco problemas apilados**:

| Causa | Tiempo perdido |
|---|---|
| Volume mount de Windows (WSL2) | 7-9 segundos |
| Sin OPcache activado | 2-3 segundos |
| Modo debug activado (`APP_DEBUG=true`) | 1-2 segundos |
| Sin caches de Laravel generados | 0.5 segundos |
| Assets cargados desde CDN externo | 1-3 segundos |
| **Total** | **10-18 segundos** |

**La causa principal** era el volume mount. Docker en Windows utiliza una capa de traducción llamada WSL2 para que los contenedores Linux puedan leer archivos de Windows. El problema es que Laravel, al arrancar, necesita cargar más de **300 archivos PHP** del framework. Con el mount activo, cada uno de esos 300 archivos cruzaba el puente WSL2, sumando entre 7 y 9 segundos antes de ejecutar una sola línea de código de la aplicación.

**La solución** fue eliminar el volume mount: el código vive directamente dentro del contenedor (copiado durante el `docker build`), sin ningún puente de traducción. PHP lee del filesystem nativo de Linux del contenedor → respuesta en menos de 200ms.

El resto de causas se corrigieron activando **OPcache** (PHP cachea el código compilado en memoria para no recompilarlo en cada petición), poniendo la app en **modo producción** (desactiva herramientas de debug que consumen recursos), generando los **caches de Laravel** al arrancar el contenedor, y descargando todos los assets (Bootstrap, Chart.js, FullCalendar, FontAwesome) **dentro de la imagen Docker** para no depender de conexiones a CDN externos en cada carga de página.

---

## DIAPOSITIVA 6 — Preguntas Frecuentes (FAQ)

**¿Por qué Laravel y no otro framework?**
Laravel es el framework PHP más usado en el mercado, bien documentado y con una comunidad enorme. Para un proyecto académico de este nivel ofrece el equilibrio perfecto entre potencia y curva de aprendizaje razonable.

**¿Por qué PHP y no Node.js o Python?**
PHP sigue siendo el lenguaje de servidor más utilizado en aplicaciones web (WordPress, Wikipedia...). Laravel lo lleva al nivel de frameworks modernos. Además, era el lenguaje que mejor conocía.

**¿Se pueden añadir más tipos de entrenamiento?**
Sí. La arquitectura del proyecto lo permite sin grandes cambios: se añadiría el nuevo tipo en la validación del controlador, en la fórmula de calorías y en los filtros de historial. La base de datos no necesitaría cambios de estructura.

**¿La aplicación es segura?**
Sí, a varios niveles: las contraseñas se almacenan con **bcrypt** (hash irreversible), Laravel previene **SQL injection** automáticamente con Eloquent, todas las vistas Blade escapan los datos para prevenir **XSS**, los formularios llevan token **CSRF** anti-falsificación de petición, y el login tiene un límite de 5 intentos por minuto para proteger contra ataques de fuerza bruta.

**¿Funciona en móvil?**
Sí. La interfaz está construida sobre Bootstrap 5, que es responsive por diseño. Se adapta automáticamente a cualquier tamaño de pantalla.

**¿Cómo se despliega?**
Con un solo comando: `docker-compose up --build`. Docker levanta automáticamente los tres contenedores necesarios: el servidor web con PHP y Apache, la base de datos MySQL y phpMyAdmin para gestión visual de la BD.

**¿Qué son los tests y qué cubren?**
La aplicación tiene 8 tests automáticos escritos con PHPUnit que comprueban: el cálculo correcto de calorías por tipo de entrenamiento, la validación de objetivos, y el cálculo de estadísticas con y sin datos. Si en el futuro se modifica algo, los tests detectan si algo deja de funcionar.

---

## DIAPOSITIVA 7 — Fin de la Exposición

---

# Muchas gracias

### Quedo a vuestra disposición para responder cualquier pregunta.

---

*SinergyFit — Proyecto Final de DAW · Juanfri · 2026*
