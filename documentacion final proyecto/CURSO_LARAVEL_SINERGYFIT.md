# 🎓 Curso Práctico de Laravel: Entendiendo SinergyFit

En este documento vamos a sumergirnos en el código puro de tu proyecto. Veremos conceptos básicos y avanzados de Laravel explicados exclusivamente con piezas de código que existen ahora mismo en **SinergyFit**.

---

## 1. RUTAS (Nivel Básico)
Las rutas son las "puertas de entrada". Cuando alguien escribe una URL, Laravel busca esa URL en `routes/web.php` para saber qué hacer.

### Ejemplo real en tu código:
```php
// routes/web.php
Route::get('/historial', [EntrenamientoController::class, 'index'])->middleware('auth');
Route::post('/entrenamientos', [EntrenamientoController::class, 'store'])->middleware('auth');
```
**¿Qué está pasando aquí?**
1.  **`Route::get` y `Route::post`:** `GET` es para pedir ver una página. `POST` es para enviar información oculta (como al rellenar el formulario de registrar entreno).
2.  **`[Controlador, 'función']`:** Le decimos a Laravel a quién llamar.
3.  **`->middleware('auth')`:** ¡Magia de seguridad! Esto significa: *"Si el usuario no ha iniciado sesión, ni intentes ejecutar esto, expúlsalo a la página de Login automáticamente"*. Así proteges tu app con una sola palabra.

---

## 2. CONTROLADORES Y VALIDACIÓN (Nivel Intermedio)
El controlador procesa lo que el usuario envía. 

### Ejemplo real: Guardando un Entrenamiento
```php
// app/Http/Controllers/EntrenamientoController.php -> función store()
public function store(Request $request)
{
    // 1. VALIDACIÓN
    $request->validate([
        'tipo' => 'required|string',
        'fecha' => 'required|date',
        'duracion_minutos' => 'required|integer|min:1',
    ]);

    // 2. CREACIÓN (Eloquent)
    $entrenamiento = Entrenamiento::create([
        'user_id' => Auth::id(), // Magia de sesión
        'tipo' => $request->tipo,
        'fecha' => $request->fecha,
        'duracion_minutos' => $request->duracion_minutos,
        // ... (cálculo de calorías)
    ]);

    // 3. REDIRECCIÓN CON MENSAJE FLASH
    return redirect('/')->with('success', 'Entrenamiento registrado con éxito.');
}
```
**Curiosidades Avanzadas aquí:**
*   **La Inyección `$request`:** Fíjate que la función recibe `(Request $request)`. Laravel es tan listo que atrapa todo lo que rellenaste en el HTML y te lo mete en esa variable. Accedes a los campos usando `$request->nombre_del_campo`.
*   **Validación:** La línea `$request->validate(...)` es brutal. Si alguien intenta hackear y pone `-5` en los minutos, esa línea lo detecta, frena la ejecución, y devuelve al usuario al formulario automáticamente pintando un mensaje de error rojo.
*   **`Auth::id()`:** Saca el ID del usuario que está logueado en este preciso instante de forma segura.
*   **Mensaje "Flash":** El `->with('success', ...)` guarda un mensaje temporal en la memoria que se borrará nada más que la pantalla se cargue y te muestre el recuadro verde de éxito.

---

## 3. MODELOS Y RELACIONES (Nivel Avanzado)
En PHP nativo tenías que hacer `SELECT * FROM tabla INNER JOIN otra_tabla ON...` En Laravel usamos **Eloquent ORM**. Todo son Objetos.

### Relación 1 a Muchos (Un usuario tiene muchos entrenamientos)
```php
// app/Models/User.php
public function entrenamientos()
{
    return $this->hasMany(Entrenamiento::class);
}
```
Con esto escrito, si quiero sacar todos los entrenamientos tuyos, solo escribo: `$usuario->entrenamientos`. Laravel escribe el SQL por detrás.

### Relación N a M (Muchos a Muchos)
Un entrenamiento de fuerza tiene varios ejercicios, y un ejercicio (ej. "Sentadilla") pertenece a muchos entrenamientos. ¡Esto requiere una tabla intermedia (`entrenamiento_detalles`)!

En SinergyFit lo gestionas de manera súper limpia:
```php
// app/Models/Entrenamiento.php
public function ejercicios()
{
    return $this->belongsToMany(Ejercicio::class, 'entrenamiento_detalles')
                ->withPivot('series', 'repeticiones', 'carga_kg');
}
```
**Ejemplo de cómo guardaste datos en la tabla intermedia:**
```php
// Esto lo haces en EntrenamientoController para la Fuerza
$entrenamiento->ejercicios()->attach($ejercicio_id, [
    'series' => $request->series[$i],
    'repeticiones' => $request->repeticiones[$i],
    'carga_kg' => $request->pesos[$i],
]);
```
La función `attach()` es puro poder. Inserta una fila en la tabla intermedia vinculando el ID del entrenamiento actual con el ID del ejercicio, y rellenando los campos extra (pivot) de golpe.

---

## 4. VISTAS Y BLADE (Nivel Básico/Intermedio)
Blade es el motor HTML de Laravel. Te permite programar lógica sin ensuciar el HTML con etiquetas feas de `<?php`.

### Bucles e Ifs
En tu página de Historial, recorres la base de datos así:
```html
<!-- resources/views/partials/actividad.blade.php -->
@if($actividad['tipo'] == 'Fuerza')
    <i class="fas fa-dumbbell fs-4"></i>
@elseif($actividad['tipo'] == 'Carrera')
    <i class="fas fa-running fs-4"></i>
@endif
```
O imprimiendo variables con la sintaxis de "bigotes" `{{ }}` que automáticamente limpia (sanitiza) el código para evitar ataques XSS:
```html
<span class="fw-bold">{{ $actividad['duracion_minutos'] }} min</span>
```

---

## 5. REGLAS DE NEGOCIO Y CONSTANTES (Nivel Avanzado)
Una mala práctica común de principiantes es llenar el código de "números mágicos" o textos escritos a mano (ej: `if($minutos > 1000)`). ¿Qué pasa si mañana el logro requiere 2000 minutos? Tendrías que buscar por todo el código.

En tu modelo de Logros, usamos buenas prácticas avanzadas definiendo **Constantes**:
```php
// app/Models/Logro.php
class Logro extends Model {
    const CRITERIO_1000_MINUTOS = '1000_minutos';
    const CRITERIO_10_SESIONES_FUERZA = '10_sesiones_fuerza';
    // ...
}
```
Y luego en tu controlador lo evaluabas así:
```php
// EntrenamientoController.php
if ($totalFuerza == 10) {
    $this->desbloquearLogro($user, Logro::CRITERIO_10_SESIONES_FUERZA);
}
```
Esto hace que el código sea a prueba de fallos y súper fácil de leer (código auto-documentado).

---

## Resumen: ¿Por qué SinergyFit es un buen código?
1.  **Seguridad delegada:** No validas sesiones a mano, usas `middleware('auth')`.
2.  **Anti-inyecciones:** Al usar Eloquent y Blade `{{ }}`, estás protegido contra inyecciones SQL y XSS por defecto.
3.  **Modularidad:** Has extraído las lógicas repetitivas a parciales visuales (`partials/actividad.blade.php`) y las lógicas matemáticas a modelos (como el cálculo de racha en `User.php`).
