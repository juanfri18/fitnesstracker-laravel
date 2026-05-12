# Guion para Presentación de Defensa del Proyecto

*(Recomendación para PowerPoint/Impress: Usa una plantilla de diseño moderna. No llenes las diapositivas de texto, pon solo 3-4 ideas clave por diapositiva y apóyate visualmente en capturas de pantalla de tu aplicación).*

## Diapositiva 1: Portada
- **Contenido Visual:** Logo de SinergyFit (o texto grande), Tu nombre, curso y fecha de defensa.
- **Lo que debes decir oralmente:** *"Buenos días a los miembros del tribunal. Vengo a presentar mi Proyecto Integrado llamado SinergyFit. Se trata de una aplicación web completa orientada al seguimiento deportivo, el análisis de datos físicos y la gamificación."*

## Diapositiva 2: El Problema y la Solución
- **Contenido Visual:** 
  - Izquierda: Icono de problema (Dispersión de datos y desmotivación). 
  - Derecha: Icono de solución (SinergyFit: Centralización y Gamificación).
- **Lo que debes decir oralmente:** *"El proyecto nace de una necesidad real. Quienes entrenamos a menudo apuntamos nuestras marcas en el block de notas del móvil, y a la larga se hace imposible ver nuestro progreso real. SinergyFit soluciona esto digitalizando todo el proceso de forma interactiva y premiando al usuario por su constancia."*

## Diapositiva 3: Arquitectura y Pila Tecnológica
- **Contenido Visual:** Logos de Laravel, PHP, MySQL, Chart.js, FullCalendar y HTML/JS.
- **Lo que debes decir oralmente:** *"Para construir la aplicación aposté por dar el salto al framework Laravel. Esto me ha permitido estructurar todo bajo el patrón MVC (Modelo-Vista-Controlador). Los modelos gestionan la base de datos de forma segura con Eloquent, las vistas están renderizadas en Blade y la lógica la manejo con los Controladores. En el Frontend he implementado JS Vanilla, Chart.js para las gráficas y FullCalendar."*

## Diapositiva 4: Hito 1 - Registro Dinámico y Transacciones
- **Contenido Visual:** Captura de pantalla del formulario de Entrenamiento (con las filas de series y repeticiones) + pequeño bloque de código de un `DB::beginTransaction()`.
- **Lo que debes decir oralmente:** *"A nivel de código, una de las partes más complejas fue el registro de entrenamientos de fuerza. Usando JavaScript en el navegador, el usuario puede clonar filas y añadir infinitas series. El controlador en Laravel recoge esos arrays y hace inserciones masivas dentro de una transacción. Si una serie falla, se hace un Rollback automático y la base de datos nunca se corrompe."*

## Diapositiva 5: Hito 2 - Asincronía y Gamificación
- **Contenido Visual:** Captura de la Vitrina de Logros en el perfil y Captura de las gráficas del Dashboard.
- **Lo que debes decir oralmente:** *"Para mejorar el rendimiento, implementé llamadas asíncronas con Fetch API. El servidor manda un JSON y las estadísticas se dibujan sin recargar la web entera. Además, creé un sistema de gamificación en el backend. Cada vez que guardas un entreno, el sistema revisa en segundo plano si mereces una medalla por tu esfuerzo y te la desbloquea en tu perfil."*

## Diapositiva 6: Conclusiones
- **Contenido Visual:** Iconos de (1) Código limpio, (2) Preparado para Producción, (3) Futura API móvil.
- **Lo que debes decir oralmente:** *"El mayor reto de este proyecto ha sido la curva de aprendizaje de pasar de código PHP plano a un framework estricto y seguro, pero el resultado ha merecido la pena. El proyecto es totalmente viable para salir a producción. A futuro, su arquitectura permitiría construir fácilmente una API REST para una aplicación móvil."*

## Diapositiva 7: Despedida
- **Contenido Visual:** "Gracias por su atención. Turno de preguntas."
- **Lo que debes decir oralmente:** *"Muchas gracias por vuestra atención y tiempo. Quedo a disposición del tribunal para responder cualquier pregunta que consideren oportuna."*
