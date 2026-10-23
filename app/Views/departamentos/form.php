<div class="row justify-content-center">
    <div class="col-lg-6">
        <h3 class="mb-4"><i class="bi bi-building"></i> <?= htmlspecialchars($titulo) ?></h3>
        <div class="card p-4">
            <form method="post" action="<?= htmlspecialchars($accion) ?>">
                <input type="hidden" name="csrf_token" value="<?= $this->csrfToken() ?>">

                <div class="mb-3">
                    <label class="form-label">Nombre</label>
                    <input type="text" name="nombre" class="form-control" required maxlength="100"
                           value="<?= htmlspecialchars($departamento['nombre'] ?? '') ?>" placeholder="Ej. Tecnología">
                </div>

                <div class="mb-4">
                    <label class="form-label">Descripción</label>
                    <textarea name="descripcion" class="form-control" rows="3"
                              placeholder="Breve descripción del departamento"><?= htmlspecialchars($departamento['descripcion'] ?? '') ?></textarea>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary" style="background-color:#5b4fe9;border-color:#5b4fe9;">
                        <i class="bi bi-check2"></i> Guardar
                    </button>
                    <a href="/departamentos" class="btn btn-outline-secondary">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
</div>
