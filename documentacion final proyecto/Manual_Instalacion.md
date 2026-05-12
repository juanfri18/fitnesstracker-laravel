# Manual de Instalación - SinergyFit

## Requisitos Previos
Para instalar y ejecutar SinergyFit en un entorno local, asegúrate de cumplir con los siguientes requisitos del sistema:
- **XAMPP / Laragon / MAMP** (o cualquier entorno con PHP y MySQL).
- **PHP** >= 8.1
- **MySQL** >= 8.0 o MariaDB.
- **Composer** (Gestor de dependencias de PHP).
- **Node.js y NPM** (Para compilar assets de frontend si fuera necesario).
- **Git** (Para clonar el repositorio).

## Pasos de Instalación

### 1. Clonar el repositorio
Abre tu terminal o consola y dirígete al directorio de tu servidor local (ej. `C:\xampp\htdocs` en Windows).
```bash
git clone https://github.com/tu-usuario/fitnesstracker-laravel.git
cd fitnesstracker-laravel
```

### 2. Instalar dependencias de PHP (Composer)
Ejecuta el siguiente comando para descargar las librerías del core de Laravel:
```bash
composer install
```

### 3. Instalar dependencias de Frontend (NPM)
```bash
npm install
npm run build
```

### 4. Configuración del Entorno (.env)
Copia el archivo de ejemplo para crear tu entorno local:
```bash
cp .env.example .env
```
Abre el archivo `.env` en tu editor de código y configura la conexión a la base de datos:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=fitnesstracker
DB_USERNAME=root
DB_PASSWORD=
```

### 5. Generar la Key de la Aplicación
Laravel requiere una clave de encriptación segura. Genérala con:
```bash
php artisan key:generate
```

### 6. Migraciones y Seeders (Base de Datos)
Asegúrate de haber creado una base de datos vacía llamada `fitnesstracker` en tu gestor (ej. phpMyAdmin). Luego ejecuta las migraciones para crear las tablas y volcar datos iniciales:
```bash
php artisan migrate --seed
```

### 7. Enlazar el Almacenamiento Público
Para que la subida de fotos de perfil funcione, crea el enlace simbólico:
```bash
php artisan storage:link
```

### 8. Iniciar el Servidor Local
Finalmente, levanta el servidor de desarrollo:
```bash
php artisan serve
```
Accede en tu navegador a: `http://localhost:8000`
