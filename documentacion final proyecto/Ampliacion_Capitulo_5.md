# Ampliación del Capítulo 5: Implementación (Para llegar a las 30 páginas)

*Instrucciones de uso: Copia este texto y pégalo DENTRO de tu Capítulo 5 (en el documento de Word final). Asegúrate de hacer capturas de pantalla de tu código o de tu aplicación en los lugares donde dice "[[ AÑADIR CAPTURA... ]]". Esto aumentará radicalmente la calidad técnica y el volumen de páginas de tu Memoria.*

---

## 5.1. Implementación del Motor Asíncrono de Gráficas (Fetch API + Chart.js)

Uno de los principales hitos tecnológicos de SinergyFit fue lograr que el *Dashboard* y las estadísticas principales se cargaran de manera asíncrona, evitando la molesta recarga completa de la página cada vez que el usuario desea ver sus avances. Para ello, se separó la lógica en un endpoint específico de la API.

**Lógica del Controlador Backend (`MetricaController.php`):**
En el lado del servidor, el controlador no devuelve una vista HTML, sino que procesa matemáticas complejas (agrupación de entrenamientos por fechas, conteo de minutos totales, cálculo de porcentajes de mejora) y devuelve un objeto JSON ligero.

```php
// Fragmento ilustrativo del controlador de métricas
public function dashboardData()
{
    // Extraemos la información del usuario autenticado de forma segura
    $user = Auth::user();
    
    // Procesamos la lógica para agrupar las métricas de la última semana
    // ... [código de agrupación] ...

    $datos = [
        'labels' => ['L', 'M', 'X', 'J', 'V', 'S', 'D'],
        'valores' => [10, 20, 30, 40, 50, 60, 70]
    ];
    
    // Devolvemos puro JSON
    return response()->json($datos);
}
```

*[[ AÑADIR CAPTURA DE PANTALLA DE LA GRÁFICA DEL DASHBOARD DE TU APLICACIÓN AQUÍ ]]*

El cliente (Front-End) recibe este JSON mediante un script con la API `Fetch` de JavaScript y lo inyecta directamente en el componente visual renderizado por la librería `Chart.js`. Esta técnica reduce drásticamente la carga del servidor, optimizando el ancho de banda.

## 5.2. Motor Transaccional y DOM Scripting (Entrenamientos Granulares)

A diferencia de otras aplicaciones simples, SinergyFit permite a los usuarios registrar entrenamientos de fuerza detallando tantas series y repeticiones como deseen. Esto requirió una implementación híbrida compleja.

**El Frontend (DOM Scripting):**
Se programó un script en JavaScript Vanilla en la vista `registro.blade.php` que permite "clonar" y añadir nuevas filas de inputs HTML al formulario en tiempo real, sin límite. Estos inputs se envían al servidor como un *Array*.

**El Backend (Transacciones ACID):**
Una vez que el controlador recibe el inmenso array de series, debe guardarlo en la base de datos de forma segura. Si una sola de las 10 series falla al guardarse (por ejemplo, porque el usuario puso letras en el campo "peso"), el entrenamiento quedaría corrupto. Para evitar esto, se implementaron transacciones en base de datos.

```php
DB::beginTransaction();
try {
    // 1. Guardar el entrenamiento maestro
    $entrenamiento = Entrenamiento::create([
        'user_id' => Auth::id(),
        'tipo' => $request->tipo,
        'fecha' => now()
    ]);
    
    // 2. Bucle dinámico para guardar los detalles (las series hijas)
    foreach ($request->series as $serie) {
        EntrenamientoDetalle::create([
            'entrenamiento_id' => $entrenamiento->id,
            'peso' => $serie['peso'],
            'repeticiones' => $serie['reps']
        ]);
    }
    
    // 3. Revisar Logros (Llamada al motor de Gamificación)
    LogroService::verificarLogros(Auth::user());
    
    // Si todo va bien, confirmar en BD de forma definitiva
    DB::commit();
} catch (\Exception $e) {
    // Si algo falla, deshacer todo para no ensuciar la base de datos
    DB::rollBack();
    return back()->withError('Error crítico al procesar el entrenamiento.');
}
```

Esta solución garantiza la Integridad Referencial de los datos y demuestra un control absoluto sobre el motor de base de datos MySQL a través del ORM Eloquent.

## 5.3. Sistema de Gamificación (Eventos en Segundo Plano)

Para incentivar la adherencia al ejercicio, se diseñó un módulo de Gamificación. Este sistema no requiere intervención manual. Se engancha de forma pasiva a los procesos de guardado (como se observa en la transacción anterior).

El servicio `verificarLogros()` consulta el histórico del usuario. Si detecta que ha superado la barrera de los 1.000 minutos acumulados, o que ha registrado su primer entrenamiento, inserta un registro en la tabla pivote `logro_user`.

*[[ AÑADIR CAPTURA DE PANTALLA DE LA VITRINA DE LOGROS DEL PERFIL EN COLOR GRIS Y COLOR ILUMINADO AQUÍ ]]*

Posteriormente, la vista `perfil.blade.php` actúa como vitrina, iterando sobre el catálogo maestro de logros e iluminando en color aquellos que el usuario posee, dejando opacos los que aún le faltan.

## 5.4. Seguridad de Rutas mediante Middlewares

La aplicación no tendría valor si cualquier persona pudiese ver los pesos o los tiempos de otros usuarios. Laravel proporciona un mecanismo de intercepción de peticiones llamado *Middleware*. En el archivo de enrutamiento web principal, se agruparon las rutas sensibles.

```php
// Rutas protegidas bajo el middleware 'auth'
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index']);
    Route::resource('/entrenamientos', EntrenamientoController::class);
    Route::get('/perfil', [PerfilController::class, 'edit']);
});
```

Cualquier intruso que intente navegar directamente escribiendo la URL `/dashboard` será interceptado por el núcleo de Laravel antes de llegar siquiera al Controlador, siendo redirigido automáticamente a la vista de `/login`.
