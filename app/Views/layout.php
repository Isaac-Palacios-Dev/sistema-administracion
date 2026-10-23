<!doctype html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= htmlspecialchars($titulo ?? 'Sistema de Administración Gerencial') ?> · Gerencia</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        body { background-color: #f5f6fa; }
        .navbar-brand { font-weight: 600; }
        .card { border: none; box-shadow: 0 1px 3px rgba(0,0,0,.08); }
        .kpi-card { border-radius: .75rem; border-left: 4px solid #5b4fe9; }
        .kpi-card .value { font-size: 1.75rem; font-weight: 700; }
        table.table thead { background-color: #eef0fb; }
        .barra-depto { height: 10px; border-radius: 6px; background: #e3e6f5; overflow: hidden; }
        .barra-depto > div { height: 100%; background: linear-gradient(90deg, #5b4fe9, #8f7bf0); }
        .kanban-col { background: #eef0fb; border-radius: .5rem; padding: .75rem; min-height: 200px; }
        .kanban-card { background: white; border-radius: .5rem; padding: .6rem .75rem; margin-bottom: .5rem; box-shadow: 0 1px 2px rgba(0,0,0,.1); }
    </style>
</head>
<body>

<?php if (!empty($_SESSION['usuario_id'])): ?>
<nav class="navbar navbar-expand-lg navbar-dark" style="background-color:#2c2a4a;">
    <div class="container-fluid">
        <a class="navbar-brand" href="/"><i class="bi bi-diagram-3"></i> Gerencia</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#nav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="nav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item"><a class="nav-link" href="/"><i class="bi bi-speedometer2"></i> Panel</a></li>
                <li class="nav-item"><a class="nav-link" href="/departamentos"><i class="bi bi-building"></i> Departamentos</a></li>
                <li class="nav-item"><a class="nav-link" href="/empleados"><i class="bi bi-people"></i> Empleados</a></li>
                <li class="nav-item"><a class="nav-link" href="/proyectos"><i class="bi bi-kanban"></i> Proyectos</a></li>
                <li class="nav-item"><a class="nav-link" href="/tareas"><i class="bi bi-check2-square"></i> Tareas</a></li>
            </ul>
            <span class="navbar-text me-3">
                <i class="bi bi-person-circle"></i> <?= htmlspecialchars($_SESSION['usuario_nombre'] ?? '') ?>
            </span>
            <a href="/logout" class="btn btn-outline-light btn-sm"><i class="bi bi-box-arrow-right"></i> Salir</a>
        </div>
    </div>
</nav>
<?php endif; ?>

<main class="container-fluid py-4">
    <?php if (!empty($_SESSION['flash'])): ?>
        <?php $f = $_SESSION['flash']; unset($_SESSION['flash']); ?>
        <div class="alert alert-<?= $f['tipo'] === 'error' ? 'danger' : 'success' ?> alert-dismissible fade show" role="alert">
            <?= htmlspecialchars($f['mensaje']) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php require $rutaVista; ?>
</main>

<footer class="text-center text-muted small py-3">
    Sistema de Administración Gerencial &middot; Proyecto académico &middot; <?= date('Y') ?>
</footer>

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/vue/3.4.21/vue.global.prod.min.js"></script>
</body>
</html>
