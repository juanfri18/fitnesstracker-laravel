@extends('layouts.app')

@section('titulo', 'Calendario de Entrenamientos')

@section('css_extra')
<style>
    .fc-event { cursor: pointer; transition: transform 0.2s; border: none; padding: 2px 4px; font-weight: bold;}
    .fc-event:hover { transform: scale(1.05); }
    .fc-toolbar-title { color: var(--primary-color); font-weight: bold; }
    /* Ajustes extra para asegurar que se vea de 10 */
    .fc-theme-standard td, .fc-theme-standard th { border-color: #dee2e6; }
    .fc-day-today { background-color: rgba(42, 81, 153, 0.05) !important; }
</style>
@endsection

@section('contenido')
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold" style="color: var(--primary-color);"><i class="far fa-calendar-alt me-2"></i>Mi Calendario de Asistencia</h2>
        <a href="/registro" class="btn btn-primary" style="border-radius: 25px;">
            <i class="fas fa-plus-circle me-1"></i> Añadir Registro
        </a>
    </div>

    <div class="card shadow-sm border-0" style="border-radius: 15px;">
        <div class="card-body p-4 bg-white rounded">
            <div id="calendar"></div>
        </div>
    </div>
</div>
@endsection

@section('scripts_extra')
<!-- FullCalendar JS v6 (Incluye estilos por defecto) -->
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js'></script>
<script src='https://cdn.jsdelivr.net/npm/@fullcalendar/core@6.1.10/locales-all.global.min.js'></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');

        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            locale: 'es', // Idioma español
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,listWeek'
            },
            buttonText: {
                today: 'Hoy',
                month: 'Mes',
                week: 'Semana',
                list: 'Lista'
            },
            // API AJAX
            events: '/api/calendario/eventos',
            eventDidMount: function(info) {
                info.el.style.borderRadius = "4px";
            }
        });

        calendar.render();
    });
</script>
@endsection
