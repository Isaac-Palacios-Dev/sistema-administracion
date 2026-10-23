<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Departamento;
use App\Models\Empleado;
use App\Models\Proyecto;
use App\Models\Tarea;

class ProyectoController extends Controller
{
    public function index(): void
    {
        $this->exigirSesion();
        $proyectos = new Proyecto();

        $this->vista('proyectos/index', [
            'titulo' => 'Proyectos',
            'proyectos' => $proyectos->conDepartamentoYAvance(),
        ]);
    }

    public function ver(string $id): void
    {
        $this->exigirSesion();
        $proyectos = new Proyecto();
        $tareas = new Tarea();
        $empleados = new Empleado();

        $proyecto = $proyectos->conDepartamento((int) $id);
        if (!$proyecto) {
            $this->flash('error', 'Proyecto no encontrado.');
            $this->redirigir('/proyectos');
        }

        $this->vista('proyectos/show', [
            'titulo' => $proyecto['nombre'],
            'proyecto' => $proyecto,
            'tareas' => $tareas->delProyecto((int) $id),
            'empleados' => $empleados->activos(),
        ]);
    }

    public function crearForm(): void
    {
        $this->exigirSesion();
        $departamentos = new Departamento();

        $this->vista('proyectos/form', [
            'titulo' => 'Nuevo proyecto',
            'departamentos' => $departamentos->todos('nombre'),
        ]);
    }

    public function crear(): void
    {
        $this->exigirSesion();
        $this->validarCsrf();

        $nombre = trim($_POST['nombre'] ?? '');
        $descripcion = trim($_POST['descripcion'] ?? '');
        $departamentoId = (int) ($_POST['departamento_id'] ?? 0);
        $fechaInicio = $_POST['fecha_inicio'] ?? '';
        $fechaFin = trim($_POST['fecha_fin_estimada'] ?? '');

        $errores = [];
        if ($nombre === '') {
            $errores[] = 'El nombre del proyecto es obligatorio.';
        }
        if ($departamentoId <= 0) {
            $errores[] = 'Seleccioná un departamento responsable.';
        }
        if ($fechaInicio === '') {
            $errores[] = 'La fecha de inicio es obligatoria.';
        }
        if ($fechaFin !== '' && $fechaInicio !== '' && $fechaFin < $fechaInicio) {
            $errores[] = 'La fecha estimada de fin no puede ser anterior a la fecha de inicio.';
        }

        if ($errores) {
            $this->flash('error', implode(' ', $errores));
            $this->redirigir('/proyectos/nuevo');
        }

        $proyectos = new Proyecto();
        $id = $proyectos->crear([
            'nombre' => $nombre,
            'descripcion' => $descripcion,
            'departamento_id' => $departamentoId,
            'fecha_inicio' => $fechaInicio,
            'fecha_fin_estimada' => $fechaFin !== '' ? $fechaFin : null,
            'estado' => 'planificacion',
        ]);

        $this->flash('exito', 'Proyecto creado correctamente.');
        $this->redirigir('/proyectos/' . $id);
    }

    public function cambiarEstado(string $id): void
    {
        $this->exigirSesion();
        $this->validarCsrf();

        $estado = $_POST['estado'] ?? '';
        if (!in_array($estado, Proyecto::ESTADOS, true)) {
            $this->flash('error', 'Estado de proyecto no válido.');
            $this->redirigir('/proyectos/' . $id);
        }

        $proyectos = new Proyecto();
        $proyectos->actualizar((int) $id, ['estado' => $estado]);
        $this->flash('exito', 'Estado del proyecto actualizado.');
        $this->redirigir('/proyectos/' . $id);
    }
}
