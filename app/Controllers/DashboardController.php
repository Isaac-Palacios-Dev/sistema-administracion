<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Departamento;
use App\Models\Empleado;
use App\Models\Proyecto;
use App\Models\Tarea;

class DashboardController extends Controller
{
    public function index(): void
    {
        $this->exigirSesion();

        $departamentos = new Departamento();
        $empleados = new Empleado();
        $proyectos = new Proyecto();
        $tareas = new Tarea();

        $porDepartamento = $departamentos->conConteoEmpleados();
        $maxEmpleados = max(array_column($porDepartamento, 'total_empleados') ?: [1]);

        $this->vista('dashboard/index', [
            'titulo' => 'Panel principal',
            'totalEmpleados' => count($empleados->activos()),
            'totalDepartamentos' => count($departamentos->todos()),
            'proyectosEnProgreso' => $proyectos->enProgreso(),
            'tareasPendientes' => $tareas->pendientes(),
            'tareasVencidas' => $tareas->vencidas(),
            'porDepartamento' => $porDepartamento,
            'maxEmpleados' => $maxEmpleados ?: 1,
            'proyectosRecientes' => array_slice($proyectos->conDepartamentoYAvance(), 0, 5),
        ]);
    }
}
