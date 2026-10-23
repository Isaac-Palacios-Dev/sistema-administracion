<h3 class="mb-4"><i class="bi bi-speedometer2"></i> Panel principal</h3>

<div class="row g-3 mb-4">
    <div class="col-sm-6 col-lg-3">
        <div class="card kpi-card bg-white p-3">
            <div class="text-muted small"><i class="bi bi-people"></i> Empleados activos</div>
            <div class="value"><?= (int) $totalEmpleados ?></div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="card kpi-card bg-white p-3">
            <div class="text-muted small"><i class="bi bi-building"></i> Departamentos</div>
            <div class="value"><?= (int) $totalDepartamentos ?></div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="card kpi-card bg-white p-3">
            <div class="text-muted small"><i class="bi bi-kanban"></i> Proyectos en progreso</div>
            <div class="value"><?= (int) $proyectosEnProgreso ?></div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="card kpi-card bg-white p-3">
            <div class="text-muted small"><i class="bi bi-check2-square"></i> Tareas pendientes</div>
            <div class="value">
                <?= (int) $tareasPendientes ?>
                <?php if ($tareasVencidas > 0): ?>
                    <span class="badge text-bg-danger ms-1"><?= (int) $tareasVencidas ?> vencidas</span>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-5">
        <div class="card p-3 mb-3">
            <strong class="mb-3"><i class="bi bi-bar-chart"></i> Empleados por departamento</strong>
            <?php foreach ($porDepartamento as $d): ?>
                <div class="mb-2">
                    <div class="d-flex justify-content-between small">
                        <span><?= htmlspecialchars($d['nombre']) ?></span>
                        <span class="text-muted"><?= (int) $d['total_empleados'] ?></span>
                    </div>
                    <div class="barra-depto">
                        <div style="width: <?= $maxEmpleados > 0 ? round(($d['total_empleados'] / $maxEmpleados) * 100) : 0 ?>%"></div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    <div class="col-lg-7">
        <div class="card">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <strong>Proyectos recientes</strong>
                <a href="/proyectos" class="small">Ver todos</a>
            </div>
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead><tr><th>Proyecto</th><th>Departamento</th><th>Estado</th><th class="text-end">Avance</th></tr></thead>
                    <tbody>
                    <?php foreach ($proyectosRecientes as $p): ?>
                        <tr>
                            <td><a href="/proyectos/<?= $p['id'] ?>"><?= htmlspecialchars($p['nombre']) ?></a></td>
                            <td><?= htmlspecialchars($p['departamento_nombre']) ?></td>
                            <td><span class="badge text-bg-light border"><?= \App\Models\Proyecto::ETIQUETAS_ESTADO[$p['estado']] ?></span></td>
                            <td class="text-end"><?= (int) $p['avance'] ?>%</td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if (empty($proyectosRecientes)): ?>
                        <tr><td colspan="4" class="text-center text-muted py-4">Todavía no hay proyectos.</td></tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
