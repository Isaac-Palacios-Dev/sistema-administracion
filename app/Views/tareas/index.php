<h3 class="mb-4"><i class="bi bi-check2-square"></i> Tareas</h3>

<form method="get" action="/tareas" class="row g-2 mb-3">
    <div class="col-auto">
        <select name="estado" class="form-select" onchange="this.form.submit()">
            <option value="">Todos los estados</option>
            <?php foreach (\App\Models\Tarea::ETIQUETAS_ESTADO as $valor => $etiqueta): ?>
                <option value="<?= $valor ?>" <?= $filtros['estado'] === $valor ? 'selected' : '' ?>><?= $etiqueta ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="col-auto">
        <select name="empleado_id" class="form-select" onchange="this.form.submit()">
            <option value="">Todos los empleados</option>
            <?php foreach ($empleados as $e): ?>
                <option value="<?= $e['id'] ?>" <?= (string) $filtros['empleado_id'] === (string) $e['id'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($e['nombre'] . ' ' . $e['apellido']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
</form>

<div class="card">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead>
                <tr><th>Tarea</th><th>Proyecto</th><th>Responsable</th><th>Prioridad</th><th>Estado</th><th>Fecha límite</th></tr>
            </thead>
            <tbody>
            <?php foreach ($tareas as $t): ?>
                <?php
                $vencida = $t['estado'] !== 'completada' && $t['fecha_limite'] && $t['fecha_limite'] < date('Y-m-d');
                ?>
                <tr class="<?= $vencida ? 'table-danger' : '' ?>">
                    <td><?= htmlspecialchars($t['titulo']) ?></td>
                    <td><a href="/proyectos/<?= $t['proyecto_id'] ?>"><?= htmlspecialchars($t['proyecto_nombre']) ?></a></td>
                    <td><?= htmlspecialchars($t['empleado_nombre'] ?? 'Sin asignar') ?></td>
                    <td><span class="badge text-bg-<?= $t['prioridad'] === 'alta' ? 'danger' : ($t['prioridad'] === 'media' ? 'warning' : 'secondary') ?>">
                        <?= ucfirst($t['prioridad']) ?>
                    </span></td>
                    <td><?= \App\Models\Tarea::ETIQUETAS_ESTADO[$t['estado']] ?></td>
                    <td><?= $t['fecha_limite'] ? htmlspecialchars($t['fecha_limite']) : '—' ?> <?= $vencida ? '<span class="text-danger small">(vencida)</span>' : '' ?></td>
                </tr>
            <?php endforeach; ?>
            <?php if (empty($tareas)): ?>
                <tr><td colspan="6" class="text-center text-muted py-4">No hay tareas con esos filtros.</td></tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
