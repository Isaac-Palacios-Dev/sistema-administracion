<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Empleado;
use App\Models\Tarea;

class TareaController extends Controller
{
    public function index(): void
    {
        $this->exigirSesion();
        $tareas = new Tarea();
        $empleados = new Empleado();

        $filtros = [
            'estado' => $_GET['estado'] ?? '',
            'empleado_id' => $_GET['empleado_id'] ?? '',
        ];

        $this->vista('tareas/index', [
            'titulo' => 'Tareas',
            'tareas' => $tareas->conDetalle($filtros),
            'empleados' => $empleados->activos(),
            'filtros' => $filtros,
        ]);
    }

    public function crear(string $proyectoId): void
    {
        $this->exigirSesion();
        $this->validarCsrf();

        $titulo = trim($_POST['titulo'] ?? '');
        $descripcion = trim($_POST['descripcion'] ?? '');
        $empleadoId = $_POST['empleado_id'] ?? '';
        $prioridad = $_POST['prioridad'] ?? 'media';
        $fechaLimite = trim($_POST['fecha_limite'] ?? '');

        if ($titulo === '') {
            $this->flash('error', 'El título de la tarea es obligatorio.');
            $this->redirigir('/proyectos/' . $proyectoId);
        }
        if (!in_array($prioridad, Tarea::PRIORIDADES, true)) {
            $prioridad = 'media';
        }

        $tareas = new Tarea();
        $tareas->crear([
            'proyecto_id' => (int) $proyectoId,
            'empleado_id' => $empleadoId !== '' ? (int) $empleadoId : null,
            'titulo' => $titulo,
            'descripcion' => $descripcion,
            'prioridad' => $prioridad,
            'fecha_limite' => $fechaLimite !== '' ? $fechaLimite : null,
            'estado' => 'pendiente',
        ]);

        $this->flash('exito', 'Tarea agregada al proyecto.');
        $this->redirigir('/proyectos/' . $proyectoId);
    }

    public function cambiarEstado(string $id): void
    {
        $this->exigirSesion();
        $this->validarCsrf();

        $estado = $_POST['estado'] ?? '';
        if (!in_array($estado, Tarea::ESTADOS, true)) {
            $this->flash('error', 'Estado de tarea no válido.');
            $this->redirigir('/tareas');
        }

        $tareas = new Tarea();
        $tarea = $tareas->buscar((int) $id);
        if (!$tarea) {
            $this->flash('error', 'Tarea no encontrada.');
            $this->redirigir('/tareas');
        }

        $tareas->actualizar((int) $id, ['estado' => $estado]);
        $this->flash('exito', 'Estado de la tarea actualizado.');

        $regreso = $_POST['volver_a'] ?? ('/proyectos/' . $tarea['proyecto_id']);
        $this->redirigir($regreso);
    }
}
