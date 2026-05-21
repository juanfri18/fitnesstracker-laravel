from pptx import Presentation
from pptx.util import Inches, Pt, Emu
from pptx.dml.color import RGBColor
from pptx.enum.text import PP_ALIGN
from pptx.util import Inches, Pt

# ── COLORES ──
C_BG       = RGBColor(0x0a, 0x0f, 0x1e)   # fondo azul muy oscuro
C_BG2      = RGBColor(0x0f, 0x17, 0x2a)   # fondo alternativo
C_BLUE     = RGBColor(0x2A, 0x51, 0x99)   # azul SinergyFit
C_ACCENT   = RGBColor(0x4f, 0xc3, 0xf7)   # azul claro acento
C_WHITE    = RGBColor(0xFF, 0xFF, 0xFF)
C_GRAY     = RGBColor(0xCC, 0xD6, 0xE8)   # texto secundario
C_DIMGRAY  = RGBColor(0x80, 0x90, 0xAA)   # texto terciario
C_GREEN    = RGBColor(0x22, 0xC5, 0x5E)
C_RED      = RGBColor(0xEF, 0x44, 0x44)

W = Inches(13.33)
H = Inches(7.5)

prs = Presentation()
prs.slide_width  = W
prs.slide_height = H

def blank_slide(prs):
    layout = prs.slide_layouts[6]  # completamente en blanco
    return prs.slides.add_slide(layout)

def set_bg(slide, color=C_BG):
    fill = slide.background.fill
    fill.solid()
    fill.fore_color.rgb = color

def txb(slide, text, l, t, w, h,
        size=18, bold=False, color=C_WHITE,
        align=PP_ALIGN.LEFT, italic=False, wrap=True):
    box = slide.shapes.add_textbox(Inches(l), Inches(t), Inches(w), Inches(h))
    box.word_wrap = wrap
    tf = box.text_frame
    tf.word_wrap = wrap
    p = tf.paragraphs[0]
    p.alignment = align
    run = p.add_run()
    run.text = text
    run.font.size = Pt(size)
    run.font.bold = bold
    run.font.italic = italic
    run.font.color.rgb = color
    return box

def txb_multi(slide, lines, l, t, w, h, default_size=14, default_color=C_GRAY):
    """lines = list of dicts: {text, size, bold, color, align, space_before}"""
    box = slide.shapes.add_textbox(Inches(l), Inches(t), Inches(w), Inches(h))
    box.word_wrap = True
    tf = box.text_frame
    tf.word_wrap = True
    first = True
    for ln in lines:
        if first:
            p = tf.paragraphs[0]
            first = False
        else:
            p = tf.add_paragraph()
        if ln.get('space_before'):
            p.space_before = Pt(ln['space_before'])
        p.alignment = ln.get('align', PP_ALIGN.LEFT)
        run = p.add_run()
        run.text = ln.get('text', '')
        run.font.size = Pt(ln.get('size', default_size))
        run.font.bold = ln.get('bold', False)
        run.font.italic = ln.get('italic', False)
        run.font.color.rgb = ln.get('color', default_color)
    return box

def rect(slide, l, t, w, h, fill=C_BLUE, alpha=None):
    shape = slide.shapes.add_shape(
        1,  # MSO_SHAPE_TYPE.RECTANGLE
        Inches(l), Inches(t), Inches(w), Inches(h)
    )
    shape.line.fill.background()
    shape.fill.solid()
    shape.fill.fore_color.rgb = fill
    return shape

def add_table(slide, data, headers, l, t, w, h):
    rows = len(data) + 1
    cols = len(headers)
    tbl = slide.shapes.add_table(rows, cols, Inches(l), Inches(t), Inches(w), Inches(h)).table
    # header row
    for ci, hdr in enumerate(headers):
        cell = tbl.cell(0, ci)
        cell.fill.solid()
        cell.fill.fore_color.rgb = C_BLUE
        p = cell.text_frame.paragraphs[0]
        run = p.add_run()
        run.text = hdr
        run.font.bold = True
        run.font.size = Pt(13)
        run.font.color.rgb = C_ACCENT
    # data rows
    for ri, row in enumerate(data):
        is_last = ri == len(data) - 1
        for ci, val in enumerate(row):
            cell = tbl.cell(ri + 1, ci)
            cell.fill.solid()
            cell.fill.fore_color.rgb = RGBColor(0x12, 0x1e, 0x38) if ri % 2 == 0 else RGBColor(0x0e, 0x18, 0x30)
            p = cell.text_frame.paragraphs[0]
            run = p.add_run()
            run.text = val
            run.font.size = Pt(13)
            run.font.bold = is_last
            run.font.color.rgb = C_WHITE if is_last else C_GRAY
    return tbl

# ═══════════════════════════════════════════════════════════
# SLIDE 1 — PORTADA
# ═══════════════════════════════════════════════════════════
s = blank_slide(prs)
set_bg(s, C_BG)

# franja decorativa izquierda
r = rect(s, 0, 0, 0.06, 7.5, C_BLUE)

# etiqueta superior
txb(s, 'PROYECTO FINAL · DESARROLLO DE APLICACIONES WEB',
    0.5, 0.5, 12, 0.4, size=10, color=C_ACCENT)

# título principal
txb(s, 'SinergyFit', 0.5, 1.4, 10, 2.0,
    size=72, bold=True, color=C_WHITE)

# subtítulo
txb(s, 'Aplicación web de seguimiento de fitness',
    0.5, 3.2, 10, 0.5, size=22, color=C_GRAY)

# línea separadora (rect fino)
rect(s, 0.5, 3.85, 3.0, 0.04, C_BLUE)

# badges tecnologías
badges = ['Laravel 11', 'PHP 8.2', 'MySQL 8.0', 'Bootstrap 5', 'Docker', 'PHPUnit']
bx = 0.5
for b in badges:
    r2 = rect(s, bx, 4.1, len(b)*0.115 + 0.3, 0.38, RGBColor(0x1a, 0x2f, 0x5a))
    txb(s, b, bx + 0.12, 4.12, len(b)*0.115 + 0.15, 0.35,
        size=11, bold=True, color=C_ACCENT)
    bx += len(b)*0.115 + 0.45

txb(s, 'Juanfri  ·  2026', 0.5, 6.8, 5, 0.4, size=13, color=C_DIMGRAY)

# ═══════════════════════════════════════════════════════════
# SLIDE 2 — INTRODUCCIÓN
# ═══════════════════════════════════════════════════════════
s = blank_slide(prs)
set_bg(s, C_BG2)
rect(s, 0, 0, 0.06, 7.5, C_BLUE)

txb(s, '01 — INTRODUCCIÓN', 0.5, 0.3, 8, 0.3, size=10, color=C_ACCENT)
txb(s, '¿Qué es SinergyFit y por qué existe?',
    0.5, 0.65, 12, 0.8, size=30, bold=True, color=C_WHITE)
rect(s, 0.5, 1.45, 2.5, 0.04, C_BLUE)

# columna izquierda — EL PROBLEMA
rect(s, 0.5, 1.65, 5.9, 2.2, RGBColor(0x10, 0x1e, 0x38))
txb(s, 'EL PROBLEMA', 0.65, 1.75, 5.6, 0.3, size=9, bold=True, color=C_ACCENT)
txb(s, 'Las apps existentes son demasiado limitadas',
    0.65, 2.05, 5.6, 0.4, size=14, bold=True, color=C_WHITE)
txb(s, 'Apps como Strava son o muy complejas o muy simples. Los entrenamientos\n'
       'de fuerza se reducen a un cronómetro y calorías estimadas.\n'
       'Sin registro de ejercicios, series ni pesos levantados.',
    0.65, 2.45, 5.6, 1.1, size=12, color=C_GRAY)

rect(s, 0.5, 3.95, 5.9, 2.2, RGBColor(0x10, 0x1e, 0x38))
txb(s, 'LA MOTIVACIÓN', 0.65, 4.05, 5.6, 0.3, size=9, bold=True, color=C_ACCENT)
txb(s, 'Convertir una rutina en un hábito',
    0.65, 4.35, 5.6, 0.4, size=14, bold=True, color=C_WHITE)
txb(s, 'Quedarse estancado en el gimnasio es común. Con un registro real\n'
       'puedes comparar cómo empezaste hace meses con cómo estás ahora.\n'
       'Eso es lo que convierte el esfuerzo en progreso visible.',
    0.65, 4.75, 5.6, 1.1, size=12, color=C_GRAY)

# columna derecha — QUÉ PERMITE
rect(s, 6.7, 1.65, 6.1, 4.5, RGBColor(0x12, 0x25, 0x48))
txb(s, '¿QUÉ PERMITE SINERGYFIT?', 6.85, 1.75, 5.8, 0.3,
    size=9, bold=True, color=C_ACCENT)

features = [
    'Registrar cada ejercicio de fuerza: grupo muscular, series, repeticiones y peso',
    'Registrar sesiones de carrera y caminata con duración y notas',
    'Ver la evolución del volumen levantado a lo largo del tiempo',
    'Establecer objetivos y seguir su progreso automáticamente',
    'Rachas de días consecutivos y 5 logros desbloqueables',
    'Estadísticas comparativas con el periodo anterior',
]
ty = 2.1
for f in features:
    txb(s, '→  ' + f, 6.85, ty, 5.8, 0.45, size=12, color=C_GRAY)
    ty += 0.52

# tipos de actividad
rect(s, 6.7, 5.6, 6.1, 0.9, RGBColor(0x1a, 0x30, 0x5a))
txb(s, '🏋️  Fuerza       🏃  Carrera       🚶  Caminata',
    6.85, 5.75, 5.8, 0.5, size=14, bold=True, color=C_WHITE, align=PP_ALIGN.CENTER)

# ═══════════════════════════════════════════════════════════
# SLIDE 3 — EL NOMBRE
# ═══════════════════════════════════════════════════════════
s = blank_slide(prs)
set_bg(s, C_BG)
rect(s, 0, 0, 0.06, 7.5, C_BLUE)

txb(s, '02 — EL NOMBRE', 0.5, 0.3, 8, 0.3, size=10, color=C_ACCENT)
txb(s, '¿Por qué SinergyFit?', 0.5, 0.65, 10, 0.7, size=30, bold=True, color=C_WHITE)
rect(s, 0.5, 1.35, 2.5, 0.04, C_BLUE)

# nombre original (tachado visualmente — en gris apagado)
rect(s, 0.5, 1.55, 5.9, 1.9, RGBColor(0x0e, 0x18, 0x2e))
txb(s, 'EL NOMBRE ORIGINAL', 0.65, 1.65, 5.6, 0.3, size=9, color=C_DIMGRAY)
txb(s, 'FitnessTracker', 0.65, 1.95, 5.6, 0.55, size=26, bold=True,
    color=RGBColor(0x55, 0x65, 0x80))
txb(s, '"Seguidor de actividad". Correcto pero genérico, sin identidad ni alma.',
    0.65, 2.5, 5.6, 0.6, size=12, color=C_DIMGRAY, italic=True)

# nombre definitivo
rect(s, 0.5, 3.6, 5.9, 2.0, RGBColor(0x12, 0x25, 0x48))
txb(s, 'EL NOMBRE DEFINITIVO', 0.65, 3.7, 5.6, 0.3, size=9, bold=True, color=C_ACCENT)
txb(s, 'SinergyFit', 0.65, 4.0, 5.6, 0.7, size=32, bold=True, color=C_WHITE)
txb(s, 'Sinergia  +  Fitness', 0.65, 4.65, 5.6, 0.4, size=14, color=C_ACCENT)

# columna derecha — qué es la sinergia
rect(s, 6.7, 1.55, 6.1, 2.2, RGBColor(0x10, 0x1e, 0x38))
txb(s, '¿QUÉ ES LA SINERGIA?', 6.85, 1.65, 5.8, 0.3, size=9, bold=True, color=C_ACCENT)
txb(s, '"La cooperación entre dos o más elementos produce\n'
       'un resultado superior a la suma de sus partes."\n\n'
       'El todo es mayor que la suma de sus partes.',
    6.85, 2.0, 5.8, 1.5, size=13, color=C_GRAY, italic=True)

# ecuación
rect(s, 6.7, 3.9, 6.1, 0.85, RGBColor(0x1a, 0x30, 0x5a))
txb(s, '💪 Fuerza  +  🏃 Cardio  =  Resultado superior',
    6.85, 4.05, 5.8, 0.55, size=15, bold=True, color=C_WHITE, align=PP_ALIGN.CENTER)

rect(s, 6.7, 4.9, 6.1, 1.7, RGBColor(0x0e, 0x18, 0x2e))
txb(s, 'Solo fuerza o solo cardio es incompleto. La combinación de ambos\n'
       '—la sinergia aplicada al deporte— es exactamente el núcleo de\n'
       'SinergyFit: una app que une todos los tipos de entrenamiento\n'
       'en un solo lugar para maximizar los resultados.',
    6.85, 5.05, 5.8, 1.4, size=12, color=C_GRAY)

# ═══════════════════════════════════════════════════════════
# SLIDE 4 — FRONTEND: LAS 7 SECCIONES
# ═══════════════════════════════════════════════════════════
s = blank_slide(prs)
set_bg(s, C_BG2)
rect(s, 0, 0, 0.06, 7.5, C_BLUE)

txb(s, '03 — FRONTEND', 0.5, 0.3, 8, 0.3, size=10, color=C_ACCENT)
txb(s, 'La aplicación por fuera: 8 secciones principales',
    0.5, 0.65, 12, 0.7, size=28, bold=True, color=C_WHITE)
rect(s, 0.5, 1.35, 2.5, 0.04, C_BLUE)

secciones = [
    ('🏠', 'Dashboard',           'Últimos 5 entrenos, racha,\nlogros y mini-gráfico semanal.'),
    ('➕', 'Registrar Actividad', 'Formulario adaptativo por tipo.\nFuerza: ejercicios, series, reps y peso.'),
    ('📋', 'Historial',           'Lista paginada. Filtro\npor tipo, edición y eliminación.'),
    ('📊', 'Estadísticas',        'Gráfica de evolución, donut\npor tipo y comparativa con periodo anterior.'),
    ('🎯', 'Objetivos',           'Días entrenados, volumen o peso.\nProgreso calculado automáticamente.'),
    ('📅', 'Calendario',          'Calendario mensual interactivo.\nEntrenos por color, clic para registrar.'),
    ('👤', 'Mi Perfil',           'Foto, métricas físicas\ne historial de peso corporal.'),
    ('🏆', 'Gamificación',        '5 logros desbloqueables\ny sistema de rachas de días.'),
]

cols = 4
cell_w = 3.0
cell_h = 2.3
margin_l = 0.5
margin_t = 1.55

for i, (icon, name, desc) in enumerate(secciones):
    col = i % cols
    row = i // cols
    lx = margin_l + col * (cell_w + 0.1)
    ty = margin_t + row * (cell_h + 0.1)
    rect(s, lx, ty, cell_w, cell_h, RGBColor(0x10, 0x1e, 0x38))
    txb(s, icon, lx + 0.15, ty + 0.15, cell_w - 0.3, 0.5, size=22)
    txb(s, name, lx + 0.15, ty + 0.65, cell_w - 0.3, 0.4,
        size=13, bold=True, color=C_WHITE)
    txb(s, desc, lx + 0.15, ty + 1.05, cell_w - 0.3, 1.0, size=11, color=C_GRAY)

# ═══════════════════════════════════════════════════════════
# SLIDE 5 — BACKEND: LARAVEL + MVC
# ═══════════════════════════════════════════════════════════
s = blank_slide(prs)
set_bg(s, C_BG)
rect(s, 0, 0, 0.06, 7.5, C_BLUE)

txb(s, '04 — BACKEND', 0.5, 0.3, 8, 0.3, size=10, color=C_ACCENT)
txb(s, 'Cómo funciona SinergyFit por dentro',
    0.5, 0.65, 12, 0.7, size=28, bold=True, color=C_WHITE)
rect(s, 0.5, 1.35, 2.5, 0.04, C_BLUE)

# framework box
rect(s, 0.5, 1.55, 12.33, 1.0, RGBColor(0x12, 0x25, 0x48))
txb(s, '⚙️  Framework: Laravel 11',
    0.7, 1.65, 6, 0.4, size=16, bold=True, color=C_WHITE)
txb(s, 'Una caja de herramientas ya construida: login, base de datos, validaciones, '
       'rutas y sesiones vienen resueltos y seguros por defecto.\n'
       'El desarrollador solo se centra en la lógica propia de la aplicación.',
    0.7, 2.0, 12.0, 0.45, size=12, color=C_GRAY)

txb(s, 'PATRÓN DE ARQUITECTURA: MVC — MODELO · VISTA · CONTROLADOR',
    0.5, 2.75, 12.5, 0.35, size=10, bold=True, color=C_DIMGRAY, align=PP_ALIGN.CENTER)

# tres columnas MVC
mvc = [
    ('M', 'MODELO',       'app/Models/',
     'Habla con la base de datos. Cada tabla tiene su clase PHP. '
     'En lugar de SQL crudo se usa Eloquent ORM.',
     "Entrenamiento::where(\n  'usuario_id', 5)->get()"),
    ('V', 'VISTA',        'resources/views/',
     'Todo lo que ve el usuario. Plantillas Blade que mezclan HTML '
     'con datos de forma limpia. No hacen cálculos, solo muestran.',
     "@foreach($entrenos as $e)\n  {{ $e->tipo }}\n@endforeach"),
    ('C', 'CONTROLADOR',  'app/Http/Controllers/',
     'El intermediario. Recibe la petición, consulta el Modelo y '
     'pasa los datos a la Vista. Aquí vive toda la lógica de negocio.',
     "return view('estadisticas',\n  ['datos' => $datos]);"),
]
col_w = 4.0
for i, (letter, name, path, desc, code) in enumerate(mvc):
    lx = 0.5 + i * (col_w + 0.16)
    ty = 3.15
    rect(s, lx, ty, col_w, 3.85, RGBColor(0x10, 0x1e, 0x38))
    txb(s, letter, lx + 0.2, ty + 0.15, 0.8, 0.9, size=44, bold=True, color=C_ACCENT)
    txb(s, name,   lx + 0.2, ty + 0.95, col_w - 0.4, 0.3, size=11, bold=True, color=C_ACCENT)
    rect(s, lx + 0.2, ty + 1.25, col_w - 0.4, 0.3, RGBColor(0x05, 0x0c, 0x1a))
    txb(s, path, lx + 0.25, ty + 1.28, col_w - 0.5, 0.25, size=9, color=C_DIMGRAY)
    txb(s, desc, lx + 0.2, ty + 1.65, col_w - 0.4, 1.1, size=11, color=C_GRAY)
    rect(s, lx + 0.2, ty + 2.85, col_w - 0.4, 0.75, RGBColor(0x05, 0x0c, 0x1a))
    txb(s, code, lx + 0.3, ty + 2.9, col_w - 0.6, 0.65, size=9, color=C_DIMGRAY)

# ═══════════════════════════════════════════════════════════
# SLIDE 6 — FLUJO DE UNA PETICIÓN HTTP
# ═══════════════════════════════════════════════════════════
s = blank_slide(prs)
set_bg(s, C_BG2)
rect(s, 0, 0, 0.06, 7.5, C_BLUE)

txb(s, '04 — BACKEND · FLUJO HTTP', 0.5, 0.3, 8, 0.3, size=10, color=C_ACCENT)
txb(s, 'Ejemplo real: el usuario abre Estadísticas',
    0.5, 0.65, 10, 0.65, size=28, bold=True, color=C_WHITE)
rect(s, 0.5, 1.3, 2.5, 0.04, C_BLUE)

pasos = [
    ('1', 'El navegador envía:  GET /estadisticas'),
    ('2', 'Apache recibe la petición y la pasa a  public/index.php  — punto de entrada de Laravel'),
    ('3', 'Laravel busca en  routes/web.php  qué controlador gestiona esa URL'),
    ('4', 'Middleware auth: ¿hay sesión activa? → Sí, continúa.  No → redirige a /login'),
    ('5', 'Se ejecuta  MetricaController::index()  — consulta BD con Eloquent, calcula totales y tendencias'),
    ('6', 'Blade compila  estadisticas.blade.php  con los datos → genera el HTML final'),
    ('7', 'El navegador renderiza la página y lanza peticiones AJAX para cargar los gráficos de Chart.js'),
]

ty = 1.5
for num, texto in pasos:
    rect(s, 0.5, ty, 0.45, 0.42, C_BLUE)
    txb(s, num, 0.5, ty + 0.03, 0.45, 0.38, size=13, bold=True,
        color=C_WHITE, align=PP_ALIGN.CENTER)
    rect(s, 1.05, ty, 11.75, 0.42, RGBColor(0x0e, 0x18, 0x2e))
    txb(s, texto, 1.15, ty + 0.05, 11.55, 0.35, size=12, color=C_GRAY)
    ty += 0.53

# métricas en la parte inferior
datos_m = [
    ('8', 'tablas en la BD'),
    ('8', 'tests PHPUnit'),
    ('3', 'contenedores Docker'),
    ('19', 'migraciones'),
]
mx = 0.5
mw = 3.0
for val, label in datos_m:
    rect(s, mx, 5.6, mw - 0.1, 1.4, RGBColor(0x12, 0x25, 0x48))
    txb(s, val,   mx + 0.15, 5.68, mw - 0.4, 0.75, size=36, bold=True, color=C_WHITE)
    txb(s, label, mx + 0.15, 6.35, mw - 0.4, 0.45, size=11, color=C_ACCENT)
    mx += mw + 0.1

# ═══════════════════════════════════════════════════════════
# SLIDE 7 — PROBLEMAS: PHP NATIVO + MIGRACIÓN
# ═══════════════════════════════════════════════════════════
s = blank_slide(prs)
set_bg(s, C_BG)
rect(s, 0, 0, 0.06, 7.5, C_BLUE)

txb(s, '05 — DIFICULTADES', 0.5, 0.3, 8, 0.3, size=10, color=C_ACCENT)
txb(s, 'Problemas durante el desarrollo',
    0.5, 0.65, 10, 0.65, size=28, bold=True, color=C_WHITE)
rect(s, 0.5, 1.3, 2.5, 0.04, C_BLUE)

# problema 1
rect(s, 0.5, 1.5, 0.06, 2.7, C_ACCENT)
txb(s, 'PROBLEMA 01', 0.7, 1.5, 11.5, 0.3, size=9, bold=True, color=C_ACCENT)
txb(s, 'Empezar con PHP puro (sin framework)',
    0.7, 1.8, 11.5, 0.45, size=18, bold=True, color=C_WHITE)
problemas1 = [
    'Cada página mezclaba SQL, validaciones, HTML y CSS en el mismo archivo.',
    'El login, las sesiones y la protección contra SQL injection: todo desde cero.',
    'Añadir funcionalidades implicaba copiar y pegar código entre páginas.',
    'El código creció hasta ser imposible de mantener o ampliar.',
]
ty = 2.25
for p in problemas1:
    txb(s, '→  ' + p, 0.7, ty, 11.5, 0.38, size=13, color=C_GRAY)
    ty += 0.42
rect(s, 0.7, ty, 11.5, 0.4, RGBColor(0x0e, 0x18, 0x2e))
txb(s, 'Resultado: fue más eficiente reescribir la aplicación entera que seguir sobre esa base.',
    0.82, ty + 0.06, 11.3, 0.3, size=12, italic=True, color=C_DIMGRAY)

# problema 2
rect(s, 0.5, 4.35, 0.06, 2.7, C_ACCENT)
txb(s, 'PROBLEMA 02', 0.7, 4.35, 11.5, 0.3, size=9, bold=True, color=C_ACCENT)
txb(s, 'La migración a Laravel',
    0.7, 4.65, 11.5, 0.45, size=18, bold=True, color=C_WHITE)
problemas2 = [
    'Migraciones: la estructura de la BD pasa a archivos PHP versionados con Git.',
    'Consultas SQL reescritas como llamadas Eloquent ORM.',
    'Las páginas se reorganizan en controladores y vistas Blade separadas.',
    'Login, validación y sesiones: gestionados por Laravel de forma segura por defecto.',
]
ty = 5.1
for p in problemas2:
    txb(s, '→  ' + p, 0.7, ty, 11.5, 0.38, size=13, color=C_GRAY)
    ty += 0.42
rect(s, 0.7, ty, 11.5, 0.4, RGBColor(0x12, 0x25, 0x48))
txb(s, 'Resultado: base sólida, código limpio y mantenible. La inversión de tiempo mereció la pena.',
    0.82, ty + 0.06, 11.3, 0.3, size=12, italic=True, color=C_ACCENT)

# ═══════════════════════════════════════════════════════════
# SLIDE 8 — PROBLEMA DOCKER
# ═══════════════════════════════════════════════════════════
s = blank_slide(prs)
set_bg(s, C_BG2)
rect(s, 0, 0, 0.06, 7.5, C_BLUE)

txb(s, '05 — DIFICULTADES · DOCKER', 0.5, 0.3, 8, 0.3, size=10, color=C_ACCENT)
txb_multi(s, [
    {'text': 'De ', 'size': 28, 'bold': True, 'color': C_WHITE},
], 0.5, 0.65, 12, 0.7)
txb(s, 'De  18 segundos por clic  →  menos de 200ms',
    0.5, 0.65, 12, 0.65, size=28, bold=True, color=C_WHITE)
rect(s, 0.5, 1.3, 2.5, 0.04, C_BLUE)

# descripción
txb(s, 'Al desplegar en Docker en Windows, cada página tardaba entre 10 y 18 segundos.\n'
       'No era un solo problema: eran cinco problemas apilados.',
    0.5, 1.45, 12.3, 0.55, size=13, color=C_GRAY)

# tabla
table_data = [
    ['Volume mount de Windows (WSL2)', '7 – 9 s'],
    ['Sin OPcache activado',           '2 – 3 s'],
    ['Modo debug activo (APP_DEBUG=true)', '1 – 2 s'],
    ['Sin caches de Laravel generados', '0.5 s'],
    ['Assets cargados desde CDN externo', '1 – 3 s'],
    ['TOTAL', '10 – 18 segundos'],
]
add_table(s, table_data, ['Causa', 'Tiempo perdido'], 0.5, 2.1, 5.8, 3.2)

# causa principal + solución
rect(s, 6.6, 2.1, 6.2, 1.55, RGBColor(0x10, 0x1e, 0x38))
rect(s, 6.6, 2.1, 0.06, 1.55, C_ACCENT)
txb(s, 'CAUSA PRINCIPAL', 6.8, 2.15, 5.9, 0.3, size=9, bold=True, color=C_ACCENT)
txb(s, 'Volume mount + WSL2',
    6.8, 2.42, 5.9, 0.38, size=15, bold=True, color=C_WHITE)
txb(s, 'Laravel carga +300 archivos PHP en cada petición. Con el\n'
       'volume mount, cada lectura cruzaba el puente WSL2 (Windows\n'
       '→ Linux), sumando 7-9 s antes de ejecutar una línea de código.',
    6.8, 2.8, 5.9, 0.75, size=11, color=C_GRAY)

rect(s, 6.6, 3.75, 6.2, 1.55, RGBColor(0x0a, 0x1e, 0x12))
rect(s, 6.6, 3.75, 0.06, 1.55, C_GREEN)
txb(s, 'LA SOLUCIÓN', 6.8, 3.8, 5.9, 0.3, size=9, bold=True, color=C_GREEN)
txb(s, 'Código dentro del contenedor',
    6.8, 4.07, 5.9, 0.38, size=15, bold=True, color=C_WHITE)
txb(s, 'Se eliminó el volume mount. El código vive en el filesystem\n'
       'nativo Linux del contenedor (copiado en el docker build).\n'
       'Además: OPcache, modo producción y caches de Laravel.',
    6.8, 4.45, 5.9, 0.75, size=11, color=C_GRAY)

# antes / después
rect(s, 6.6, 5.4, 3.0, 1.55, RGBColor(0x2a, 0x10, 0x10))
txb(s, 'ANTES', 6.75, 5.5, 2.7, 0.3, size=9, bold=True, color=C_RED)
txb(s, '~15 s', 6.75, 5.78, 2.7, 0.75, size=36, bold=True, color=C_RED)

rect(s, 9.75, 5.4, 3.05, 1.55, RGBColor(0x0a, 0x22, 0x14))
txb(s, 'DESPUÉS', 9.9, 5.5, 2.7, 0.3, size=9, bold=True, color=C_GREEN)
txb(s, '185 ms', 9.9, 5.78, 2.7, 0.75, size=36, bold=True, color=C_GREEN)

# ═══════════════════════════════════════════════════════════
# SLIDE 9 — FAQ
# ═══════════════════════════════════════════════════════════
s = blank_slide(prs)
set_bg(s, C_BG)
rect(s, 0, 0, 0.06, 7.5, C_BLUE)

txb(s, '06 — PREGUNTAS FRECUENTES', 0.5, 0.3, 8, 0.3, size=10, color=C_ACCENT)
txb(s, 'FAQ', 0.5, 0.65, 10, 0.65, size=30, bold=True, color=C_WHITE)
rect(s, 0.5, 1.3, 2.5, 0.04, C_BLUE)

faqs_izq = [
    ('¿Por qué Laravel y no otro framework?',
     'Es el framework PHP más usado, mejor documentado y con mayor comunidad. '
     'Equilibrio perfecto entre potencia y curva de aprendizaje para este proyecto.'),
    ('¿Por qué PHP y no Node.js o Python?',
     'PHP es el lenguaje servidor más extendido (WordPress, Wikipedia...). '
     'Laravel lo eleva al nivel de frameworks modernos. Era también el lenguaje con más experiencia.'),
    ('¿Se pueden añadir más tipos de entrenamiento?',
     'Sí. Solo hay que añadir el nuevo tipo en la validación del controlador '
     'y en la fórmula de calorías. La base de datos no requiere ningún cambio estructural.'),
    ('¿Funciona en móvil?',
     'Sí. La interfaz está construida sobre Bootstrap 5, que es responsive por diseño '
     'y se adapta automáticamente a cualquier tamaño de pantalla.'),
]
faqs_der = [
    ('¿La aplicación es segura?',
     'Sí: contraseñas con bcrypt, SQL injection prevenido por Eloquent, '
     'XSS bloqueado por Blade, tokens CSRF en todos los formularios y límite '
     'de 5 intentos de login por minuto.'),
    ('¿Cómo se despliega la aplicación?',
     'Con un solo comando: docker-compose up --build. Docker levanta los tres '
     'contenedores automáticamente: servidor web (PHP + Apache), MySQL y phpMyAdmin.'),
    ('¿Qué cubren los tests automáticos?',
     '8 tests con PHPUnit: cálculo de calorías por tipo, validación de objetivos '
     'y cálculo de estadísticas con y sin datos. Detectan regresiones automáticamente.'),
]

ty = 1.5
for q, a in faqs_izq:
    rect(s, 0.5, ty, 6.1, 1.2, RGBColor(0x0e, 0x18, 0x2e))
    txb(s, q, 0.65, ty + 0.08, 5.85, 0.35, size=12, bold=True, color=C_ACCENT)
    txb(s, a, 0.65, ty + 0.45, 5.85, 0.65, size=11, color=C_GRAY)
    ty += 1.3

ty = 1.5
for q, a in faqs_der:
    rect(s, 6.85, ty, 6.1, 1.2, RGBColor(0x0e, 0x18, 0x2e))
    txb(s, q, 7.0, ty + 0.08, 5.85, 0.35, size=12, bold=True, color=C_ACCENT)
    txb(s, a, 7.0, ty + 0.45, 5.85, 0.65, size=11, color=C_GRAY)
    ty += 1.3

# ═══════════════════════════════════════════════════════════
# SLIDE 10 — FIN
# ═══════════════════════════════════════════════════════════
s = blank_slide(prs)
set_bg(s, C_BG)
rect(s, 0, 0, 0.06, 7.5, C_BLUE)

# icono central
txb(s, '🏋️', 5.8, 1.0, 1.8, 1.2, size=56, align=PP_ALIGN.CENTER)

txb(s, 'SINERGYFIT · PROYECTO FINAL DAW',
    1.5, 2.3, 10.5, 0.35, size=10, bold=True, color=C_ACCENT, align=PP_ALIGN.CENTER)
txb(s, 'Muchas gracias',
    1.5, 2.7, 10.5, 1.2, size=54, bold=True, color=C_WHITE, align=PP_ALIGN.CENTER)

rect(s, 4.5, 3.9, 4.5, 0.04, C_BLUE)

txb(s, 'Quedo a vuestra disposición para responder cualquier pregunta.',
    1.5, 4.1, 10.5, 0.5, size=18, color=C_GRAY, align=PP_ALIGN.CENTER)

# badges
badges2 = ['Laravel 11', 'PHP 8.2', 'MySQL 8.0', 'Docker', 'PHPUnit']
total_w = sum(len(b)*0.115 + 0.45 for b in badges2) - 0.15
bx = (13.33 - total_w) / 2
by = 4.85
for b in badges2:
    bw = len(b)*0.115 + 0.3
    rect(s, bx, by, bw, 0.38, RGBColor(0x1a, 0x2f, 0x5a))
    txb(s, b, bx + 0.1, by + 0.04, bw - 0.1, 0.3,
        size=11, bold=True, color=C_ACCENT, align=PP_ALIGN.CENTER)
    bx += bw + 0.15

txb(s, 'Juanfri  ·  2026',
    1.5, 6.7, 10.5, 0.4, size=13, color=C_DIMGRAY, align=PP_ALIGN.CENTER)

# ═══════════════════════════════════════════════════════════
# GUARDAR
# ═══════════════════════════════════════════════════════════
prs.save(r'c:\xampp\htdocs\fitnesstracker-laravel\SinergyFit_Presentacion.pptx')
print("OK — SinergyFit_Presentacion.pptx generado correctamente.")
