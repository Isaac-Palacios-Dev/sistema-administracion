<div class="row justify-content-center">
    <div class="col-md-5 col-lg-4">
        <div class="card mt-5">
            <div class="card-body p-4">
                <h4 class="mb-1 text-center"><i class="bi bi-diagram-3"></i> Gerencia</h4>
                <p class="text-muted text-center mb-4">Sistema de Administración Gerencial</p>
                <form method="post" action="/login">
                    <input type="hidden" name="csrf_token" value="<?= $this->csrfToken() ?>">
                    <div class="mb-3">
                        <label class="form-label">Correo</label>
                        <input type="email" name="correo" class="form-control" required autofocus value="admin@gerencia.local">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Contraseña</label>
                        <input type="password" name="clave" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100" style="background-color:#5b4fe9;border-color:#5b4fe9;">Entrar</button>
                </form>
                <p class="text-muted small text-center mt-3 mb-0">
                    Usuario de prueba: <code>admin@gerencia.local</code> / <code>Admin123*</code>
                </p>
            </div>
        </div>
    </div>
</div>
