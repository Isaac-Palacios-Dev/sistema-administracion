<div class="d-flex justify-content-between align-items-center mb-3">
    <h3><i class="bi bi-kanban"></i> Proyectos</h3>
    <a href="/proyectos/nuevo" class="btn btn-primary" style="background-color:#5b4fe9;border-color:#5b4fe9;">
        <i class="bi bi-plus-circle"></i> Nuevo proyecto
    </a>
</div>

<div class="row g-3">
<?php foreach ($proyectos as $p): ?>
    <div class="col-md-6 col-lg-4">
        <a href="/proyectos/<?= $p['id'] ?>" class="text-decoration-none text-dark">
            <div class="card p-3 h-100">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <strong><?= htmlspecialchars($p['nombre']) ?></strong>
                    <span class="badge text-bg-light border"><?= \App\Models\Proyecto::ETIQUETAS_ESTADO[$p['estado']] ?></span>
                </div>
                <div class="text-muted small mb-2"><i class="bi bi-building"></i> <?= htmlspecialchars($p['departamento_nombre']) ?></div>
                <div class="barra-depto mb-1"><div style="width: <?= (int) $p['avance'] ?>%"></div></div>
                <div class="text-muted small"><?= (int) $p['avance'] ?>% completado</div>
            </div>
        </a>
    </div>
<?php endforeach; ?>
<?php if (empty($proyectos)): ?>
    <div class="col-12"><p class="text-muted text-center py-5">Todavía no hay proyectos registrados.</p></div>
<?php endif; ?>
</div>
