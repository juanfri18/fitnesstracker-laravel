# SinergyFit - Manual Rápido del Usuario

Bienvenido a tu nueva plataforma personal de entrenamiento. Esta guía te enseñará cómo dar tus primeros pasos y exprimir al máximo tus estadísticas.

## ⏱️ Guía Rápida: "Tus primeros 10 minutos"

Sigue estos 3 simples pasos para poner en marcha tu motor deportivo:

### 1. Registra tu perfil métrico
Al entrar por primera vez, dirígete a la pestaña **Perfil**. Completa tu peso, tu altura y tu nivel de actividad para que el sistema empiece a estimar cálculos para ti.
> **[PEGAR CAPTURA DE PANTALLA AQUÍ - Pantalla de Perfil]**

### 2. Márcate un objetivo
No corras sin rumbo. Ve a la pestaña **Mis Metas** en la barra lateral y establece tu primer reto (por ejemplo: Entrenar 3 veces por semana).
> **[PEGAR CAPTURA DE PANTALLA AQUÍ - Formulario de Metas]**

### 3. Registra tu primer entrenamiento
¡A sudar! Haz clic en el botón flotante "+ Nueva Actividad", selecciona el tipo (Fuerza, Carrera, etc) y detalla las series o kilómetros.
> **[PEGAR CAPTURA DE PANTALLA AQUÍ - Registro dinámico (Fuerza o Cardio)]**

---

## 📊 Entendiendo tu Dashboard (Tablero Principal)

Cada vez que inicies sesión, aterrizarás en tu Dashboard. Aquí podrás ver:
1. Tu **Progreso Semanal** visualizado en forma de gráfica.
2. Tu listado de 5 actividades más recientes. (Para verlas todas, pulsa "Ver todos los entrenamientos" al fondo de la lista).
> **[PEGAR CAPTURA DE PANTALLA AQUÍ - Dashboard / Inicio]**

### 📈 Gráficas y Filtros
Si quieres un análisis profundo, visita la sección **Estadísticas**.
Allí encontrarás un desplegable en la esquina superior derecha que dice "🗓️ Esta Semana". Si lo cambias a "Este Mes" o "Este Año", verás cómo la gráfica interactiva y los números verdes/rojos (Tendencia) se recalculan automáticamente en función del periodo seleccionado.
> **[PEGAR CAPTURA DE PANTALLA AQUÍ - Sección de Estadísticas Filtradas]**

---

## 🏆 Rachas y Gamificación
La consistencia tiene premio. Si encadenas entrenamientos en días consecutivos, tu "Fuego de Racha" 
crecerá. Si dejas pasar un día completo sin registrar nada... el fuego se apaga y vuelve a 0.
Podrás desbloquear "Medallas" automáticamente cuando alcances hitos matemáticos ocultos (como sumar 1.000 minutos totales). Las verás encenderse en tu Perfil.
> **[PEGAR CAPTURA DE PANTALLA AQUÍ - Vitrina de Logros en el Perfil]**

---

## 📅 Calendario de Entrenamientos
Accede a la sección **Calendario** desde la barra lateral para ver todos tus entrenamientos organizados visualmente por fecha y tipo. Cada color representa un tipo: rojo (Fuerza), verde (Carrera) y amarillo (Caminata). Haz clic en cualquier día para añadir un nuevo registro con la fecha preseleccionada.
> **[PEGAR CAPTURA DE PANTALLA AQUÍ - Vista de Calendario Mensual con eventos]**

---

## 🔧 Problemas Frecuentes y Soluciones (Troubleshooting)

| Problema | Causa Posible | Solución |
| :--- | :--- | :--- |
| **No veo las líneas de mi gráfica.** | No tienes entrenos en ese periodo. | Asegúrate de tener al menos 1 entreno registrado dentro de las fechas seleccionadas en el filtro (Semana/Mes). |
| **Mi racha se reinició a 0 sola.** | Han pasado más de 24h sin registro. | El sistema penaliza la inactividad diaria. Solo suman días consecutivos. |
| **Error "Campo obligatorio" al guardar un ejercicio de fuerza.** | Falta asignar las series/reps. | Verifica que has seleccionado el grupo muscular y un ejercicio válido en todas las filas antes de guardar. |
| **Los correos de recordatorio no me llegan.** | Entorno `.env` no configurado. | Verifica que el administrador del servidor ha configurado las credenciales de Mailtrap/SMTP en el entorno base. |
| **Las horas de los ejercicios salen desfasadas.** | Zona horaria del servidor. | Revisa si el calendario global del sistema está en el huso horario correcto (UTC/Madrid). |
