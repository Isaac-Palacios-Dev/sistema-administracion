<div class="row justify-content-center">
    <div class="col-lg-7">
        <h3 class="mb-4"><i class="bi bi-people"></i> <?= htmlspecialchars($titulo) ?></h3>
        <div class="card p-4">
            <form method="post" action="<?= htmlspecialchars($accion) ?>">
                <input type="hidden" name="csrf_token" value="<?= $this->csrfToken() ?>">

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Nombre</label>
                        <input type="text" name="nombre" class="form-control" required
                               value="<?= htmlspecialchars($empleado['nombre'] ?? '') ?>">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Apellido</label>
                        <input type="text" name="apellido" class="form-control" required
                               value="<?= htmlspecialchars($empleado['apellido'] ?? '') ?>">
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Documento</label>
                        <input type="text" name="documento" class="form-control" required
                               value="<?= htmlspecialchars($empleado['documento'] ?? '') ?>" placeholder="00000000-0">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Teléfono</label>
                        <input type="text" name="telefono" class="form-control"
                               value="<?= htmlspecialchars($empleado['telefono'] ?? '') ?>">
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Correo</label>
                    <input type="email" name="correo" class="form-control" required
                           value="<?= htmlspecialchars($empleado['correo'] ?? '') ?>">
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Puesto</label>
                        <input type="text" name="puesto" class="form-control" required
                               value="<?= htmlspecialchars($empleado['puesto'] ?? '') ?>">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Departamento</label>
                        <select name="departamento_id" class="form-select" required>
                            <option value="">Seleccioná...</option>
                            <?php foreach ($departamentos as $d): ?>
                                <option value="<?= $d['id'] ?>" <?= (($empleado['departamento_id'] ?? '') == $d['id']) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($d['nombre']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Salario mensual</label>
                        <input type="number" step="0.01" min="0" name="salario" class="form-control"
                               value="<?= htmlspecialchars($empleado['salario'] ?? '0') ?>">
                    </div>
                    <div class="col-md-6 mb-4">
                        <label class="form-label">Fecha de contratación</label>
                        <input type="date" name="fecha_contratacion" class="form-control" required
                               value="<?= htmlspecialchars($empleado['fecha_contratacion'] ?? date('Y-m-d')) ?>">
                    </div>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary" style="background-color:#5b4fe9;border-color:#5b4fe9;">
                        <i class="bi bi-check2"></i> Guardar
                    </button>
                    <a href="/empleados" class="btn btn-outline-secondary">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
</div>
