# FitnessTracker Laravel

¡Bienvenido a FitnessTracker! Una aplicación web para llevar el control de tus entrenamientos y objetivos.

## Requisitos Previos
- PHP >= 8.1
- Composer
- Base de datos MySQL / MariaDB

## Pasos de Instalación

1. Clona este repositorio o descomprímelo en tu máquina local:
   ```bash
   git clone <url-del-repo>
   ```
2. Instala las dependencias de PHP usando Composer:
   ```bash
   composer install
   ```
3. Copia el archivo `.env.example` y renómbralo a `.env`:
   ```bash
   cp .env.example .env
   ```
4. Genera la clave de la aplicación Laravel:
   ```bash
   php artisan key:generate
   ```
5. Configura tu conexión a la base de datos en el archivo `.env`:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=fitness_tracker
   DB_USERNAME=root
   DB_PASSWORD=tu_contraseña
   ```
6. Ejecuta las migraciones para crear las tablas en la base de datos:
   ```bash
   php artisan migrate
   ```
7. (Opcional) Puedes ejecutar los seeders si dispones de datos de prueba:
   ```bash
   php artisan db:seed
   ```
8. Inicia el servidor de desarrollo local:
   ```bash
   php artisan serve
   ```
   Tu aplicación estará corriendo en `http://localhost:8000`.

## 🐳 Ejecución con Docker (Avanzado)
Si no deseas instalar PHP ni MySQL en tu máquina, puedes levantar la aplicación completa en un contenedor usando nuestro `docker-compose.yml` preconfigurado:
1. Asegúrate de tener **Docker** y **Docker Compose** instalados.
2. Abre la terminal en la raíz del proyecto y ejecuta:
   ```bash
   docker-compose up -d --build
   ```
3. El sistema levantará un servidor Apache+PHP y una base de datos MySQL de forma automática y persistente en el puerto `8000`. Accede a `http://localhost:8000`.
4. Una vez arrancados los contenedores, ejecuta las migraciones y seeders dentro del contenedor de la app:
   ```bash
   docker exec fitness_app php artisan migrate --force
   docker exec fitness_app php artisan db:seed --class=LogroSeeder --force
   ```

## 🧪 Testing Automatizado
La aplicación cuenta con cobertura de pruebas unitarias y de características usando **PHPUnit** y una base de datos temporal en memoria (`sqlite`) para no alterar tus datos de producción.
Para ejecutar la batería de pruebas y verificar que todas las matemáticas y controladores funcionan correctamente:
```bash
php artisan test
```

## 📚 Manual de Usuario
Puedes consultar el documento [MANUAL_USUARIO.md](./MANUAL_USUARIO.md) para ver la guía rápida de la plataforma, cómo configurar objetivos y un listado de problemas frecuentes.

## Tecnologías Utilizadas
- **Laravel** (Framework PHP)
- **Blade** (Motor de plantillas)
- **Bootstrap 5** (Diseño y UI)
- **MySQL** (Base de datos relacional)
