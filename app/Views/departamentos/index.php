<div class="d-flex justify-content-between align-items-center mb-3">
    <h3><i class="bi bi-building"></i> Departamentos</h3>
    <a href="/departamentos/nuevo" class="btn btn-primary" style="background-color:#5b4fe9;border-color:#5b4fe9;">
        <i class="bi bi-plus-circle"></i> Nuevo departamento
    </a>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead><tr><th>Nombre</th><th>Descripción</th><th>Empleados activos</th><th class="text-end">Acciones</th></tr></thead>
            <tbody>
            <?php foreach ($departamentos as $d): ?>
                <tr>
                    <td><?= htmlspecialchars($d['nombre']) ?></td>
                    <td class="text-muted"><?= htmlspecialchars($d['descripcion']) ?></td>
                    <td><span class="badge text-bg-light border"><?= (int) $d['total_empleados'] ?></span></td>
                    <td class="text-end">
                        <a href="/departamentos/<?= $d['id'] ?>/editar" class="btn btn-sm btn-outline-secondary">
                            <i class="bi bi-pencil"></i>
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
            <?php if (empty($departamentos)): ?>
                <tr><td colspan="4" class="text-center text-muted py-4">No hay departamentos registrados.</td></tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
