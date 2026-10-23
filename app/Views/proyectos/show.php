<div class="d-flex justify-content-between align-items-start mb-3">
    <div>
        <h3 class="mb-1"><?= htmlspecialchars($proyecto['nombre']) ?></h3>
        <div class="text-muted">
            <i class="bi bi-building"></i> <?= htmlspecialchars($proyecto['departamento_nombre']) ?>
            &middot; Inicio: <?= htmlspecialchars($proyecto['fecha_inicio']) ?>
            <?php if ($proyecto['fecha_fin_estimada']): ?>
                &middot; Fin estimado: <?= htmlspecialchars($proyecto['fecha_fin_estimada']) ?>
            <?php endif; ?>
        </div>
    </div>
    <form method="post" action="/proyectos/<?= $proyecto['id'] ?>/estado" class="d-flex gap-2">
        <input type="hidden" name="csrf_token" value="<?= $this->csrfToken() ?>">
        <select name="estado" class="form-select form-select-sm" onchange="this.form.submit()">
            <?php foreach (\App\Models\Proyecto::ETIQUETAS_ESTADO as $valor => $etiqueta): ?>
                <option value="<?= $valor ?>" <?= $proyecto['estado'] === $valor ? 'selected' : '' ?>><?= $etiqueta ?></option>
            <?php endforeach; ?>
        </select>
    </form>
</div>

<?php if ($proyecto['descripcion']): ?>
    <p class="mb-4"><?= nl2br(htmlspecialchars($proyecto['descripcion'])) ?></p>
<?php endif; ?>

<div class="card p-3 mb-4">
    <strong class="mb-2"><i class="bi bi-plus-circle"></i> Agregar tarea</strong>
    <form method="post" action="/proyectos/<?= $proyecto['id'] ?>/tareas" class="row g-2">
        <input type="hidden" name="csrf_token" value="<?= $this->csrfToken() ?>">
        <div class="col-md-4">
            <input type="text" name="titulo" class="form-control" placeholder="Título de la tarea" required>
        </div>
        <div class="col-md-3">
            <select name="empleado_id" class="form-select">
                <option value="">Sin asignar</option>
                <?php foreach ($empleados as $e): ?>
                    <option value="<?= $e['id'] ?>"><?= htmlspecialchars($e['nombre'] . ' ' . $e['apellido']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-2">
            <select name="prioridad" class="form-select">
                <option value="baja">Baja</option>
                <option value="media" selected>Media</option>
                <option value="alta">Alta</option>
            </select>
        </div>
        <div class="col-md-2">
            <input type="date" name="fecha_limite" class="form-control">
        </div>
        <div class="col-md-1">
            <button type="submit" class="btn btn-primary w-100" style="background-color:#5b4fe9;border-color:#5b4fe9;">
                <i class="bi bi-plus-lg"></i>
            </button>
        </div>
    </form>
</div>

<div id="app-kanban" class="row g-3">
    <div class="col-md-4" v-for="col in columnas" :key="col.estado">
        <div class="kanban-col">
            <h6 class="mb-3">{{ col.titulo }} <span class="badge text-bg-secondary">{{ col.tareas.length }}</span></h6>
            <div class="kanban-card" v-for="t in col.tareas" :key="t.id">
                <div class="d-flex justify-content-between">
                    <strong class="small">{{ t.titulo }}</strong>
                    <span :class="'badge text-bg-' + prioridadColor(t.prioridad)">{{ t.prioridad }}</span>
                </div>
                <div class="text-muted small" v-if="t.empleado_nombre">
                    <i class="bi bi-person"></i> {{ t.empleado_nombre }}
                </div>
                <div class="text-muted small" v-if="t.fecha_limite">
                    <i class="bi bi-calendar"></i> {{ t.fecha_limite }}
                </div>
                <div class="mt-2 d-flex gap-1">
                    <button v-if="col.estado !== 'pendiente'" class="btn btn-sm btn-outline-secondary"
                            @click="moverTarea(t, anterior(col.estado))">
                        <i class="bi bi-arrow-left"></i>
                    </button>
                    <button v-if="col.estado !== 'completada'" class="btn btn-sm btn-outline-primary"
                            @click="moverTarea(t, siguiente(col.estado))">
                        <i class="bi bi-arrow-right"></i>
                    </button>
                </div>
            </div>
            <p class="text-muted small mb-0" v-if="col.tareas.length === 0">Sin tareas aquí.</p>
        </div>
    </div>
</div>

<!-- Formulario oculto que hace el POST real de cambio de estado -->
<form id="form-mover-tarea" method="post" style="display:none">
    <input type="hidden" name="csrf_token" value="<?= $this->csrfToken() ?>">
    <input type="hidden" name="estado" id="input-nuevo-estado">
    <input type="hidden" name="volver_a" value="/proyectos/<?= $proyecto['id'] ?>">
</form>

<script>
const tareas = <?= json_encode($tareas, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>;
const ORDEN = ['pendiente', 'en_progreso', 'completada'];
const TITULOS = { pendiente: 'Pendiente', en_progreso: 'En progreso', completada: 'Completada' };

const { createApp } = Vue;
createApp({
    data() {
        return { tareas };
    },
    computed: {
        columnas() {
            return ORDEN.map(estado => ({
                estado,
                titulo: TITULOS[estado],
                tareas: this.tareas.filter(t => t.estado === estado),
            }));
        },
    },
    methods: {
        prioridadColor(p) {
            return p === 'alta' ? 'danger' : (p === 'media' ? 'warning' : 'secondary');
        },
        anterior(estado) {
            return ORDEN[ORDEN.indexOf(estado) - 1];
        },
        siguiente(estado) {
            return ORDEN[ORDEN.indexOf(estado) + 1];
        },
        moverTarea(tarea, nuevoEstado) {
            const form = document.getElementById('form-mover-tarea');
            form.action = '/tareas/' + tarea.id + '/estado';
            document.getElementById('input-nuevo-estado').value = nuevoEstado;
            form.submit();
        },
    },
}).mount('#app-kanban');
</script>
