# 📚 Guía Definitiva de SinergyFit y Laravel

Esta guía está diseñada para que entiendas **cada rincón de tu proyecto** y cómo funciona la magia de Laravel por debajo. Laravel puede parecer intimidante al principio, pero una vez entiendes cómo se comunican sus piezas, verás que todo tiene una lógica aplastante.

---

## 1. El Corazón de Todo: El Patrón MVC

Laravel funciona usando un patrón de arquitectura llamado **MVC (Modelo-Vista-Controlador)**. Imagina tu aplicación como un restaurante:

*   **V (Vista - El Camarero):** Es lo que ve el usuario (el HTML, CSS, los botones). El camarero recoge el pedido (cuando haces clic en "Guardar") y te entrega el plato terminado (la página web).
*   **C (Controlador - El Chef):** Recibe el pedido del camarero, decide qué hacer, pide los ingredientes necesarios y cocina el plato. 
*   **M (Modelo - La Despensa/Base de Datos):** Es quien gestiona los ingredientes. El Chef (Controlador) le pide datos (ej: "Dame el entrenamiento #5") y el Modelo busca en la Base de Datos y se lo entrega.

---

## 2. El Flujo de Vida: ¿Cómo se comunica todo?

Vamos a seguir el viaje de un clic. ¿Qué pasa cuando entras a `http://localhost/historial`?

1.  **La Ruta (`routes/web.php`):** Actúa como el recepcionista. Dice: *"Ah, quieres ir a /historial. Déjame llamar al chef de Entrenamientos"*. 
2.  **El Controlador (`app/Http/Controllers/EntrenamientoController.php`):** La función `index()` se pone a trabajar. 
3.  **El Modelo (`app/Models/Entrenamiento.php`):** El controlador le dice al modelo: *"Dame todos los entrenamientos de este usuario ordenados por fecha"*. El Modelo genera la consulta SQL (`SELECT * FROM entrenamientos...`), saca los datos y se los da al Controlador.
4.  **La Vista (`resources/views/historial.blade.php`):** El Controlador coge esos datos y se los inyecta a la plantilla HTML. La plantilla dibuja los cuadritos azules y rojos y se la manda a tu navegador.

---

## 3. El Mapa del Tesoro: ¿Dónde está cada cosa?

Aquí tienes el mapa exacto de tu proyecto y para qué sirve cada carpeta principal:

### 📍 `app/` (El Motor de Lógica)
*   `app/Models/`: Aquí están `User.php`, `Entrenamiento.php`, `Logro.php`. Definen cómo es un objeto en la base de datos y sus relaciones (ej: Un entrenamiento pertenece a un usuario).
*   `app/Http/Controllers/`: Aquí están los "Jefes de sección". `ObjetivoController` gestiona las metas, `EntrenamientoController` los entrenos. Si hay un error al guardar o borrar algo, el culpable suele estar aquí.

### 📍 `resources/` (Lo Visual)
*   `resources/views/`: Todas tus páginas web. Los archivos terminan en `.blade.php`. Blade es un motor que permite mezclar PHP dentro del HTML de forma limpia (usando `{{ }}` en vez de `<?php echo ?>`).
*   `resources/views/layouts/`: Tu esqueleto principal (`app.blade.php`).
*   `resources/views/partials/`: Trozos de código reciclables (la barra lateral, las tarjetas de actividad).

### 📍 `database/` (Los Cimientos)
*   `database/migrations/`: Son "recetas" para crear tablas. Si mañana queremos añadir el campo `pulsaciones`, no vamos a PHPMyAdmin a crearlo a mano. Creamos una migración con código PHP. Esto permite que cualquiera que descargue tu proyecto pueda crear la BD con un solo comando.
*   `database/seeders/`: Archivos para "sembrar" o rellenar la base de datos con datos de prueba (como el `AddDemoDataToUserSeeder` que acabamos de usar).

### 📍 `routes/` (El Mapa de Carreteras)
*   `routes/web.php`: Aquí está la lista de todas las URLs de tu página web y a qué Controlador apuntan. Si inventas una página nueva, tienes que registrar su URL aquí.

---

## 4. Curiosidades y Magia Oscura de Laravel

Laravel hace muchas cosas por detrás para facilitarte la vida. Aquí tienes algunas curiosidades que estás usando en SinergyFit:

### ✨ Eloquent ORM (Magia de Bases de Datos)
Tú rara vez escribes consultas `SELECT` o `INSERT` en Laravel. Laravel usa "Eloquent". 
Si quieres el nombre del usuario del primer entrenamiento de fuerza, en SQL sería algo larguísimo con `JOIN`. En SinergyFit escribes:
```php
$entrenamiento->user->name;
```
¡Magia! Laravel sabe que `user` está relacionado con `entrenamiento` y hace el `JOIN` por detrás de forma invisible.

### ✨ Protección CSRF (Seguridad Integrada)
¿Te has fijado que en todos tus formularios, justo debajo de la etiqueta `<form>`, tienes un `@csrf`?
Es un token de seguridad obligatorio. Laravel genera una contraseña temporal secreta cada vez que cargas un formulario. Al enviarlo, comprueba si esa contraseña coincide. Esto evita ataques de hackers que intenten falsificar formularios desde otras webs.

### ✨ Artisan (Tu Asistente Personal)
Cuando ejecutas comandos en la terminal que empiezan por `php artisan ...`, estás llamando al "artesano" de Laravel. 
*   `php artisan make:controller` te crea un archivo vacío con todo el código base.
*   `php artisan migrate` lee las recetas de tu base de datos y ejecuta los comandos SQL en MySQL por ti.

---

## 5. Caso Práctico en SinergyFit: El Cálculo de Calorías

Para que veas todo esto en acción, veamos cómo hemos programado que se calculen tus calorías:

1.  **VISTA:** En `registro.blade.php`, el usuario pone que ha corrido 30 minutos y le da a Guardar. El formulario hace un `POST` a la ruta `/entrenamientos`.
2.  **RUTA:** En `routes/web.php` Laravel ve el POST y dice: *"Mándalo a EntrenamientoController@store"*.
3.  **CONTROLADOR:** En la función `store()`, nosotros escribimos una lógica: 
    *   *Si el deporte es carrera, multiplica duración x 11.*
    *   *Si es fuerza, multiplica duración x 6.5.*
4.  **MODELO/BD:** El controlador llama a `Entrenamiento::create(...)` pasándole los datos y las calorías que acabamos de calcular, guardándolo para siempre en MySQL.

¡Espero que esta guía te sirva como un manual de consulta rápido! Cada vez que te pierdas, recuerda el viaje: **Ruta -> Controlador -> Modelo -> Vista.**
