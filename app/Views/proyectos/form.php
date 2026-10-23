<div class="row justify-content-center">
    <div class="col-lg-6">
        <h3 class="mb-4"><i class="bi bi-kanban"></i> Nuevo proyecto</h3>
        <div class="card p-4">
            <form method="post" action="/proyectos">
                <input type="hidden" name="csrf_token" value="<?= $this->csrfToken() ?>">

                <div class="mb-3">
                    <label class="form-label">Nombre del proyecto</label>
                    <input type="text" name="nombre" class="form-control" required maxlength="150">
                </div>

                <div class="mb-3">
                    <label class="form-label">Descripción</label>
                    <textarea name="descripcion" class="form-control" rows="3"></textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label">Departamento responsable</label>
                    <select name="departamento_id" class="form-select" required>
                        <option value="">Seleccioná...</option>
                        <?php foreach ($departamentos as $d): ?>
                            <option value="<?= $d['id'] ?>"><?= htmlspecialchars($d['nombre']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-4">
                        <label class="form-label">Fecha de inicio</label>
                        <input type="date" name="fecha_inicio" class="form-control" required value="<?= date('Y-m-d') ?>">
                    </div>
                    <div class="col-md-6 mb-4">
                        <label class="form-label">Fin estimado (opcional)</label>
                        <input type="date" name="fecha_fin_estimada" class="form-control">
                    </div>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary" style="background-color:#5b4fe9;border-color:#5b4fe9;">
                        <i class="bi bi-check2"></i> Crear proyecto
                    </button>
                    <a href="/proyectos" class="btn btn-outline-secondary">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
</div>
