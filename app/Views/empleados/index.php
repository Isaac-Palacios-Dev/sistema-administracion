<div class="d-flex justify-content-between align-items-center mb-3">
    <h3><i class="bi bi-people"></i> Empleados</h3>
    <a href="/empleados/nuevo" class="btn btn-primary" style="background-color:#5b4fe9;border-color:#5b4fe9;">
        <i class="bi bi-plus-circle"></i> Nuevo empleado
    </a>
</div>

<form method="get" action="/empleados" class="row g-2 mb-3">
    <div class="col-auto">
        <select name="departamento_id" class="form-select" onchange="this.form.submit()">
            <option value="">Todos los departamentos</option>
            <?php foreach ($departamentos as $d): ?>
                <option value="<?= $d['id'] ?>" <?= $departamentoId == $d['id'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($d['nombre']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
</form>

<div class="card">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead>
                <tr><th>Nombre</th><th>Documento</th><th>Puesto</th><th>Departamento</th><th>Correo</th><th class="text-end">Acciones</th></tr>
            </thead>
            <tbody>
            <?php foreach ($empleados as $e): ?>
                <tr>
                    <td><?= htmlspecialchars($e['nombre'] . ' ' . $e['apellido']) ?></td>
                    <td><code><?= htmlspecialchars($e['documento']) ?></code></td>
                    <td><?= htmlspecialchars($e['puesto']) ?></td>
                    <td><?= htmlspecialchars($e['departamento_nombre']) ?></td>
                    <td class="small"><?= htmlspecialchars($e['correo']) ?></td>
                    <td class="text-end">
                        <a href="/empleados/<?= $e['id'] ?>/editar" class="btn btn-sm btn-outline-secondary">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <form method="post" action="/empleados/<?= $e['id'] ?>/desactivar" class="d-inline"
                              onsubmit="return confirm('¿Desactivar a este empleado?')">
                            <input type="hidden" name="csrf_token" value="<?= $this->csrfToken() ?>">
                            <button type="submit" class="btn btn-sm btn-outline-danger"><i class="bi bi-slash-circle"></i></button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
            <?php if (empty($empleados)): ?>
                <tr><td colspan="6" class="text-center text-muted py-4">No hay empleados para mostrar.</td></tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
