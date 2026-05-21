# SinergyFit — FitnessTracker Laravel

¡Bienvenido a **SinergyFit**! Una aplicación web para llevar el control de tus entrenamientos, objetivos y progreso físico.

---

## Vista previa de la aplicación

A continuación se muestran las pantallas principales de la plataforma. Para una guía paso a paso con capturas de cada flujo, consulta el [Manual de Usuario](./MANUAL_USUARIO.md).

### 1. Dashboard principal
El tablero de inicio muestra tu progreso semanal en gráfica, las últimas 5 actividades registradas y acceso rápido a nueva actividad.

> ![Dashboard principal](./imagenes/capturas/01_dashboard.png)
> *(Registra tu primer entrenamiento para ver el gráfico en acción)*

### 2. Registro de entrenamiento
Formulario dinámico que adapta los campos según el tipo elegido: Fuerza (series, repeticiones, carga), Carrera o Caminata (distancia, sensación).

> ![Registro de entrenamiento](./imagenes/capturas/02_registro.png)

### 3. Estadísticas avanzadas
Panel de estadísticas con filtro por semana / mes / año, comparativa porcentual respecto al periodo anterior y dos gráficas interactivas (evolución de minutos y distribución por tipo).

> ![Estadísticas](./imagenes/capturas/03_estadisticas.png)

### 4. Gestión de objetivos
Crea metas de volumen (kg levantados), días entrenados o peso corporal. Cada objetivo muestra su barra de progreso actualizada en tiempo real y el estado (En progreso / Completado / Caducado).

> ![Objetivos](./imagenes/capturas/04_objetivos.png)

### 5. Calendario de entrenamientos
Vista mensual con todos los entrenamientos posicionados en su fecha y con código de colores por tipo. Haz clic en cualquier día para añadir un registro con la fecha preseleccionada.

> ![Calendario](./imagenes/capturas/05_calendario.png)

### 6. Perfil y logros
Edita tus métricas físicas (peso, altura, grasa corporal), sube foto de perfil y consulta los logros desbloqueados. Al cumplir un hito (p. ej. 1.000 minutos entrenados) aparece un modal de celebración con confetti.

> ![Perfil y logros](./imagenes/capturas/06_perfil_logros.png)

---

## Requisitos Previos

**Opción A — Local con XAMPP:**
- PHP >= 8.1
- Composer
- MySQL / MariaDB

**Opción B — Docker (recomendado):**
- Docker Desktop instalado y corriendo

---

## Instalación local (XAMPP)

1. Clona o descomprime el repositorio:
   ```bash
   git clone <url-del-repo>
   ```
2. Instala las dependencias PHP:
   ```bash
   composer install
   ```
3. Copia y configura el entorno:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```
4. Edita `.env` con tus credenciales de base de datos:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=sinergyfit
   DB_USERNAME=root
   DB_PASSWORD=
   ```
5. Crea las tablas y carga los datos de prueba:
   ```bash
   php artisan migrate
   php artisan db:seed
   ```
6. Lanza el servidor de desarrollo:
   ```bash
   php artisan serve
   ```
   Accede en `http://localhost:8000`.

---

## Ejecución con Docker

Si no quieres instalar PHP ni MySQL en tu máquina, levanta toda la aplicación con Docker:

1. Asegúrate de tener **Docker Desktop** instalado y en ejecución.
2. Construye y arranca los contenedores:
   ```bash
   docker-compose up -d --build
   ```
   Esto levanta tres contenedores:
   - `fitness_app` — Apache + PHP 8.2 con OPcache (puerto **8000**)
   - `fitness_db` — MySQL 8.0 (puerto 3306)
   - `fitness_pma` — phpMyAdmin (puerto **8080**)

3. La primera vez, ejecuta las migraciones y el seeder de logros:
   ```bash
   docker exec fitness_app php artisan migrate --force
   docker exec fitness_app php artisan db:seed --class=LogroSeeder --force
   ```

4. Accede a la aplicación en `http://localhost:8000`.

> **Nota:** Los arranques posteriores son instantáneos con `docker-compose up -d` (sin `--build`).
> Los datos de la base de datos y las fotos de perfil persisten entre reinicios gracias a los volúmenes Docker.

---

## Testing Automatizado

La aplicación cuenta con 8 pruebas automatizadas (PHPUnit) que cubren el cálculo de calorías,
la validación de objetivos y el cálculo de estadísticas, incluyendo casos límite (datos a cero,
usuario sin entrenamientos).

Las pruebas usan una base de datos SQLite en memoria para no tocar los datos de producción:

```bash
php artisan test
```

Salida esperada:
```
PASS  Tests\Feature\CaloriaTest
✓ calorias carrera se calculan correctamente
✓ calorias fuerza se calculan correctamente
✓ calorias caminata se calculan correctamente
✓ calorias cero minutos rechazado por validacion

PASS  Tests\Feature\ObjetivoTest
✓ usuario puede crear objetivo validado
✓ objetivo falla con valores negativos o cero

PASS  Tests\Feature\MetricaTest
✓ metrica calcula correctamente estadisticas normales
✓ metrica no falla sin datos caso limite

Tests: 8 passed
```

---

## Problemas Frecuentes

| Problema | Causa probable | Solución |
| :--- | :--- | :--- |
| **La web no carga en Docker / error 500** | Los caches de Laravel no se generaron correctamente al arrancar. | Ejecuta `docker logs fitness_app` y comprueba que aparecen las líneas `Configuration cached successfully` y `Routes cached successfully`. Si no, reconstruye con `docker-compose up -d --build`. |
| **No veo las líneas de mi gráfica en Estadísticas** | No tienes entrenamientos registrados en el periodo seleccionado. | Asegúrate de tener al menos 1 entrenamiento dentro de las fechas del filtro activo (Semana / Mes / Año). |
| **Mi racha se reinició a 0 sola** | Ha pasado más de 1 día completo sin registrar ningún entrenamiento. | El sistema exige días consecutivos. Si fallas un día, la racha se reinicia a 1 en el siguiente registro. |
| **Error "Campo obligatorio" al guardar entrenamiento de Fuerza** | Hay filas de ejercicio con grupo muscular seleccionado pero sin ejercicio concreto (o viceversa). | Revisa que en cada fila de ejercicio hayas rellenado tanto el grupo muscular como el nombre del ejercicio. |
| **Las fotos de perfil no se muestran tras reiniciar Docker** | El symlink `public/storage` no existe o el volumen no está montado. | Ejecuta `docker exec fitness_app php artisan storage:link`. El `entrypoint.sh` ya lo hace automáticamente en cada arranque. |
| **Error de conexión a la base de datos al arrancar por primera vez** | MySQL tarda unos segundos en inicializarse y el contenedor de la app arranca antes. | Espera 15-20 segundos y recarga la página. Docker reiniciará el contenedor automáticamente hasta que la BD esté lista. |
| **Los correos de recordatorio no llegan** | Las credenciales SMTP no están configuradas en el entorno. | Configura `MAIL_HOST`, `MAIL_USERNAME` y `MAIL_PASSWORD` en el bloque `environment` de `docker-compose.yml` (o en `.env` para instalación local). |
| **Las fechas del calendario aparecen desfasadas** | La zona horaria del servidor no coincide con la del usuario. | Ajusta `APP_TIMEZONE=Europe/Madrid` en las variables de entorno de `docker-compose.yml`. |

---

## Documentación del proyecto

| Documento | Descripción |
| :--- | :--- |
| [MANUAL_USUARIO.md](./MANUAL_USUARIO.md) | Guía rápida de uso, flujos principales con capturas y troubleshooting para el usuario final |
| [optimizacion_docker.md](./optimizacion_docker.md) | Explicación detallada de las optimizaciones de rendimiento aplicadas al despliegue Docker (10s → 200ms) |
| [auditoria_final.md](./auditoria_final.md) | Auditoría técnica completa del proyecto: qué está implementado, dónde y cómo |
| [documentacion final proyecto/](./documentacion%20final%20proyecto/) | Carpeta con la documentación académica completa: memoria de sprints, guía del proyecto, ERD de la BD, diapositivas y PDF final |

---

## Tecnologías Utilizadas

| Categoría | Tecnología |
| :--- | :--- |
| Framework backend | Laravel 11 (PHP 8.2) |
| Motor de plantillas | Blade |
| Base de datos | MySQL 8.0 |
| Frontend | Bootstrap 5, Chart.js 4, FullCalendar 6, FontAwesome 6 |
| Testing | PHPUnit (SQLite in-memory) |
| Contenedores | Docker + Docker Compose |
| Servidor web | Apache 2.4 con OPcache |
