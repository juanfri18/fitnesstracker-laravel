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

## Tecnologías Utilizadas
- **Laravel** (Framework PHP)
- **Blade** (Motor de plantillas)
- **Bootstrap 5** (Diseño y UI)
- **MySQL** (Base de datos relacional)
