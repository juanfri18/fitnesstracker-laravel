FROM php:8.2-apache

# Habilitar mod_rewrite de Apache para Laravel
RUN a2enmod rewrite

# Instalar extensiones del sistema y de PHP requeridas por Laravel
# opcache añadido para cachear bytecode PHP y eliminar recompilaciones en cada petición
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    zip \
    unzip \
    git \
    wget \
    && docker-php-ext-install pdo pdo_mysql gd opcache \
    && rm -rf /var/lib/apt/lists/*

# Configuración de OPcache para producción.
# validate_timestamps=0: el código está copiado en la imagen y no cambia en runtime,
# desactivarlo da el máximo rendimiento (sin stat() por fichero en cada petición).
RUN { \
    echo 'opcache.enable=1'; \
    echo 'opcache.memory_consumption=256'; \
    echo 'opcache.interned_strings_buffer=16'; \
    echo 'opcache.max_accelerated_files=20000'; \
    echo 'opcache.validate_timestamps=0'; \
    echo 'opcache.revalidate_freq=0'; \
    echo 'opcache.fast_shutdown=1'; \
    echo 'opcache.enable_cli=0'; \
} > /usr/local/etc/php/conf.d/opcache.ini

# Configurar el DocumentRoot de Apache apuntando a /public
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Copiar el código al contenedor (filesystem nativo del contenedor, rápido)
WORKDIR /var/www/html
COPY . /var/www/html

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
RUN composer install --no-dev --optimize-autoloader

# --- Descargar assets de frontend a public/vendor/ ---
# Se sirven localmente en lugar de desde CDN externas, eliminando peticiones de red
# en cada carga de página dentro del entorno Docker.
RUN mkdir -p public/vendor/bootstrap/css \
             public/vendor/bootstrap/js \
             public/vendor/fontawesome/css \
             public/vendor/fontawesome/webfonts \
             public/vendor/confetti \
             public/vendor/chartjs \
             public/vendor/fullcalendar

# Bootstrap 5.3.0
RUN wget -q "https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" \
         -O public/vendor/bootstrap/css/bootstrap.min.css && \
    wget -q "https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" \
         -O public/vendor/bootstrap/js/bootstrap.bundle.min.js

# Font Awesome 6.0.0 — CSS + webfonts (la CSS usa rutas relativas ../webfonts/ que
# funcionan correctamente con la estructura de carpetas elegida)
RUN wget -q "https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" \
         -O public/vendor/fontawesome/css/all.min.css && \
    wget -q "https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/webfonts/fa-solid-900.woff2" \
         -O public/vendor/fontawesome/webfonts/fa-solid-900.woff2 && \
    wget -q "https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/webfonts/fa-solid-900.ttf" \
         -O public/vendor/fontawesome/webfonts/fa-solid-900.ttf && \
    wget -q "https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/webfonts/fa-regular-400.woff2" \
         -O public/vendor/fontawesome/webfonts/fa-regular-400.woff2 && \
    wget -q "https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/webfonts/fa-regular-400.ttf" \
         -O public/vendor/fontawesome/webfonts/fa-regular-400.ttf && \
    wget -q "https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/webfonts/fa-brands-400.woff2" \
         -O public/vendor/fontawesome/webfonts/fa-brands-400.woff2 && \
    wget -q "https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/webfonts/fa-brands-400.ttf" \
         -O public/vendor/fontawesome/webfonts/fa-brands-400.ttf

# canvas-confetti 1.9.3
RUN wget -q "https://cdn.jsdelivr.net/npm/canvas-confetti@1.9.3/dist/confetti.browser.min.js" \
         -O public/vendor/confetti/confetti.browser.min.js

# Chart.js 4.4.3 + plugin datalabels 2.2.0
RUN wget -q "https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js" \
         -O public/vendor/chartjs/chart.umd.min.js && \
    wget -q "https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.2.0/dist/chartjs-plugin-datalabels.min.js" \
         -O public/vendor/chartjs/chartjs-plugin-datalabels.min.js

# FullCalendar 6.1.10 (bundle global incluye CSS embebido en el JS)
RUN wget -q "https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js" \
         -O public/vendor/fullcalendar/index.global.min.js && \
    wget -q "https://cdn.jsdelivr.net/npm/@fullcalendar/core@6.1.10/locales-all.global.min.js" \
         -O public/vendor/fullcalendar/locales-all.global.min.js

# Dar permisos a storage y bootstrap/cache
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Copiar el script de arranque y hacerlo ejecutable
COPY entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh

# Sustituir el CMD por defecto de Apache con nuestro entrypoint
CMD ["/entrypoint.sh"]
