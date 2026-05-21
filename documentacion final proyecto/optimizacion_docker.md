# Optimización de Rendimiento Docker — SinergyFit

> **Resultado:** La aplicación pasó de tardar **10-18 segundos por clic** a responder en **menos de 200ms**.
> Medición real tras los cambios: `HTTP 200 — tiempo: 0.185605s`

---

## El problema

Al desplegar con `docker-compose up` en Windows, cada vez que se navegaba a cualquier página
la respuesta tardaba un mínimo de 10 segundos. No era un problema del código de la aplicación
en sí, sino de **cinco problemas de configuración apilados**, cada uno sumando segundos al total.

---

## Causa 1 — El volume mount de Windows (7-9 segundos)

### Qué era

En el `docker-compose.yml` original existía esta línea:

```yaml
volumes:
  - .:/var/www/html
```

Esto monta la carpeta del proyecto de Windows dentro del contenedor Docker.

### Por qué era tan lento

En Windows, Docker Desktop utiliza una capa de traducción llamada **WSL2/FUSE** para que el
contenedor Linux pueda leer archivos del sistema de archivos de Windows. Esta traducción tiene
una latencia muy alta por operación de lectura.

Laravel, al recibir cualquier petición HTTP, necesita cargar (`require`) más de **300 archivos
PHP** del directorio `vendor/` (el framework, sus dependencias, los modelos, controladores...).
Con el volume mount activo, **cada uno de esos 300 `require` cruzaba el puente WSL2**, sumando
entre 7 y 9 segundos solo en lecturas de disco antes de ejecutar una sola línea de código de
la aplicación.

### Por qué existía ese mount si el Dockerfile ya copia el código

El `Dockerfile` original ya tenía:

```dockerfile
COPY . /var/www/html
RUN composer install --no-dev --optimize-autoloader
```

Esto copia todo el código fuente al filesystem nativo del contenedor (rápido). Pero el volume
mount en `docker-compose.yml` **sobreescribía** ese filesystem rápido con el lento de Windows.
Era como hacer el trabajo dos veces y quedarse con el resultado peor.

### Cómo se arregló

Se **eliminó** la línea del volume mount del servicio `app`. El código del contenedor es ahora
el que se copió durante el `docker build`, que vive en el filesystem nativo de Linux del
contenedor y no sufre ninguna penalización de rendimiento.

Para que las **fotos de perfil** subidas por los usuarios persistan entre reinicios del
contenedor (sin este volumen se borrarían al hacer `docker-compose down`), se añadió un volumen
nombrado de Docker apuntando solo a la carpeta de subidas:

```yaml
volumes:
  - fitness_storage:/var/www/html/storage/app/public
```

Un volumen nombrado de Docker vive dentro de la VM de Docker Desktop (en Linux), por lo que
no sufre el problema de traducción WSL2.

---

## Causa 2 — Sin OPcache (2-3 segundos)

### Qué era

El `Dockerfile` original solo instalaba estas extensiones PHP:

```dockerfile
docker-php-ext-install pdo pdo_mysql gd
```

`opcache` no estaba incluido.

### Por qué era lento

PHP es un lenguaje interpretado. Por defecto, cada vez que se ejecuta un archivo `.php`,
el motor de PHP lo **lee, analiza y compila a bytecode** desde cero. Sin OPcache, esto ocurre
en **cada petición HTTP**.

Con OPcache activado, el bytecode compilado se guarda en memoria RAM. Las peticiones siguientes
simplemente ejecutan el bytecode ya compilado, sin volver a leer ni compilar ningún archivo.

En un proyecto Laravel con 300+ archivos PHP de vendor, la diferencia es de 2 a 3 segundos
por petición.

### Cómo se arregló

Se añadió `opcache` a la instalación de extensiones PHP en el `Dockerfile`:

```dockerfile
docker-php-ext-install pdo pdo_mysql gd opcache
```

Y se creó un archivo de configuración de OPcache con parámetros optimizados para producción:

```dockerfile
RUN { \
    echo 'opcache.enable=1'; \
    echo 'opcache.memory_consumption=256'; \
    echo 'opcache.interned_strings_buffer=16'; \
    echo 'opcache.max_accelerated_files=20000'; \
    echo 'opcache.validate_timestamps=0'; \
    echo 'opcache.revalidate_freq=0'; \
    echo 'opcache.fast_shutdown=1'; \
} > /usr/local/etc/php/conf.d/opcache.ini
```

El parámetro clave es `validate_timestamps=0`. Normalmente OPcache comprueba si el archivo
en disco ha cambiado desde que lo cacheó (para regenerar el bytecode si el desarrollador
modifica código). En nuestro caso **el código está copiado en la imagen y nunca cambia
mientras el contenedor está corriendo**, así que desactivar esa comprobación da el máximo
rendimiento: PHP nunca toca el disco para comprobar timestamps.

---

## Causa 3 — APP_DEBUG=true y APP_ENV=local (1-2 segundos)

### Qué era

El archivo `.env` tenía:

```
APP_ENV=local
APP_DEBUG=true
LOG_LEVEL=debug
```

### Por qué era lento

Con `APP_DEBUG=true`, Laravel activa una serie de herramientas de diagnóstico en cada petición:
- Recoge y guarda en memoria el **log completo de todas las queries SQL** ejecutadas.
- Genera **stack traces completos** listos para mostrar si ocurre cualquier error.
- Deja habilitados middlewares y capas de introspección extra.
- Escribe logs detallados en disco para cada petición.

Todo esto tiene un coste de CPU y memoria que añade entre 1 y 2 segundos a cada respuesta.

`APP_ENV=local` además desactiva ciertas optimizaciones que Laravel solo aplica en modo
`production`.

### Cómo se arregló

Se configuraron las variables de entorno directamente en `docker-compose.yml` para que
el contenedor siempre arranque en modo producción, independientemente de lo que diga el
archivo `.env` local:

```yaml
environment:
  - APP_ENV=production
  - APP_DEBUG=false
  - LOG_LEVEL=error
```

`LOG_LEVEL=error` hace que solo se registren errores reales, no el ruido de debug habitual.

---

## Causa 4 — Sin caches de Laravel al arrancar (0.5 segundos)

### Qué era

Laravel no tenía generados los caches de configuración, rutas ni vistas.

### Por qué era lento

Cada petición HTTP que recibe Laravel necesita:
- **Leer y parsear** todos los archivos de `config/` (database.php, app.php, cache.php, etc.)
  para saber cómo está configurada la aplicación.
- **Leer y registrar** todas las rutas definidas en `routes/web.php` y `routes/api.php`.
- **Compilar** las plantillas Blade de `.blade.php` a PHP puro si no están ya compiladas.

Esto ocurre en cada petición si no se han generado los caches.

### Cómo se arregló

Se creó el archivo `entrypoint.sh` — un script que se ejecuta cada vez que el contenedor
arranca, **antes de iniciar Apache**. Este script genera todos los caches de una vez:

```bash
#!/bin/bash
set -e

echo "==> Generando caches de Laravel para producción..."

php artisan config:cache   # Une todos los config/*.php en un solo archivo PHP
php artisan route:cache    # Serializa todas las rutas en un archivo PHP
php artisan event:cache    # Cachea los listeners de eventos
php artisan view:cache     # Compila todas las plantillas Blade a PHP puro

php artisan storage:link --quiet 2>/dev/null || true  # Symlink para fotos de perfil

echo "==> Iniciando Apache..."
exec apache2-foreground
```

El `exec` al final es importante: reemplaza el proceso bash con Apache, de forma que Docker
gestiona las señales de parada (`docker stop`) directamente sobre Apache y el contenedor
se apaga limpiamente.

El `Dockerfile` copia este script y lo establece como el comando por defecto del contenedor:

```dockerfile
COPY entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh
CMD ["/entrypoint.sh"]
```

---

## Causa 5 — Assets externos cargados desde CDN (1-3 segundos)

### Qué era

El layout principal (`resources/views/layouts/app.blade.php`) cargaba 4 librerías desde
servidores externos en cada carga de página:

```html
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" ...>
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" ...>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js">
<script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.9.3/dist/confetti.browser.min.js">
```

Las páginas de estadísticas y calendario añadían otras más (Chart.js, FullCalendar).

### Por qué era lento

Dentro del entorno Docker, cada petición a un CDN externo implica:
1. Resolución DNS del dominio (`cdn.jsdelivr.net`, `cdnjs.cloudflare.com`).
2. Establecimiento de conexión TCP + TLS (handshake HTTPS).
3. Esperar la respuesta del servidor remoto.

Aunque el CDN esté rápido, la red de Docker en Windows añade latencia adicional. Con 4-6
recursos bloqueantes en el `<head>`, el navegador no puede renderizar la página hasta
que todos han respondido, lo que suma entre 1 y 3 segundos en cada navegación.

### Cómo se arregló

Se añadió al `Dockerfile` la descarga de todos estos archivos **durante la construcción
de la imagen** (`docker build`), usando `wget`. Los archivos quedan almacenados en
`public/vendor/` dentro de la imagen:

```dockerfile
# Bootstrap 5.3.0
RUN wget -q "https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" \
         -O public/vendor/bootstrap/css/bootstrap.min.css && \
    wget -q "https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" \
         -O public/vendor/bootstrap/js/bootstrap.bundle.min.js

# Font Awesome 6.0.0 (CSS + webfonts woff2/ttf)
RUN wget -q "https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" \
         -O public/vendor/fontawesome/css/all.min.css
# ... (más webfonts: fa-solid, fa-regular, fa-brands)

# canvas-confetti, Chart.js 4.4.3, chartjs-plugin-datalabels 2.2.0, FullCalendar 6.1.10
# ...
```

La estructura de carpetas se diseñó para que FontAwesome funcione sin modificar su CSS:
el archivo descargado usa rutas relativas `../webfonts/...`, que con la estructura
`fontawesome/css/all.min.css` y `fontawesome/webfonts/*.woff2` funcionan correctamente
sin reescribir nada.

Las vistas se actualizaron para apuntar a las rutas locales:

```html
<!-- Antes -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" ...>

<!-- Después -->
<link href="/vendor/bootstrap/css/bootstrap.min.css" ...>
```

La descarga ocurre **una sola vez al construir la imagen**. Una vez construida, los assets
se sirven desde el filesystem del contenedor sin ninguna petición de red externa.

---

## Resumen de archivos modificados

| Archivo | Tipo | Cambio principal |
|---|---|---|
| `Dockerfile` | Modificado | OPcache, descarga de assets, entrypoint |
| `entrypoint.sh` | Nuevo | Caches de Laravel + arranque Apache |
| `docker-compose.yml` | Modificado | Sin volume mount, vars de producción |
| `resources/views/layouts/app.blade.php` | Modificado | Bootstrap, FA, confetti → local |
| `resources/views/inicio.blade.php` | Modificado | Chart.js → local |
| `resources/views/estadisticas.blade.php` | Modificado | Chart.js + datalabels → local |
| `resources/views/calendario.blade.php` | Modificado | FullCalendar → local |

---

## Resumen del impacto

| Causa | Tiempo eliminado |
|---|---|
| Volume mount WSL2 | ~7-9 s |
| Sin OPcache | ~2-3 s |
| APP_DEBUG=true | ~1-2 s |
| Sin caches de Laravel | ~0.5 s |
| Assets CDN externos | ~1-3 s |
| **Total** | **10-18 s → < 200 ms** |

---

## Cómo usar después de estos cambios

```powershell
# Reconstruir la imagen y arrancar (necesario si se cambia código o configuración)
docker-compose down
docker-compose up -d --build

# Primera vez con una base de datos nueva:
docker exec fitness_app php artisan migrate --force
docker exec fitness_app php artisan db:seed --force

# Arranques normales posteriores (sin reconstruir, instantáneo):
docker-compose up -d

# Verificar que los caches se generaron correctamente:
docker logs fitness_app
```

La salida de `docker logs fitness_app` debe mostrar siempre:

```
==> Generando caches de Laravel para producción...
   INFO  Configuration cached successfully.
   INFO  Routes cached successfully.
   INFO  Events cached successfully.
   INFO  Blade templates cached successfully.
==> Iniciando Apache...
```

---

## Nota importante: desarrollo vs. despliegue

Con esta configuración **el código dentro del contenedor es el que se copió al hacer
`docker build`**. Si modificas un archivo PHP, una vista o una ruta, los cambios no se
reflejan automáticamente — necesitas reconstruir la imagen:

```powershell
docker-compose up -d --build
```

Esto es correcto para **despliegue** (que es el caso de uso de este proyecto). Si en algún
momento necesitas un entorno de **desarrollo** con recarga automática de cambios, habría
que re-añadir el volume mount y cambiar `opcache.validate_timestamps` a `1`.
