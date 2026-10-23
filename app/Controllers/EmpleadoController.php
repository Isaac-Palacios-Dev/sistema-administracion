<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Departamento;
use App\Models\Empleado;

class EmpleadoController extends Controller
{
    public function index(): void
    {
        $this->exigirSesion();
        $empleados = new Empleado();
        $departamentos = new Departamento();

        $departamentoId = isset($_GET['departamento_id']) && $_GET['departamento_id'] !== ''
            ? (int) $_GET['departamento_id']
            : null;

        $this->vista('empleados/index', [
            'titulo' => 'Empleados',
            'empleados' => $empleados->conDepartamento($departamentoId),
            'departamentos' => $departamentos->todos('nombre'),
            'departamentoId' => $departamentoId,
        ]);
    }

    public function crearForm(): void
    {
        $this->exigirSesion();
        $departamentos = new Departamento();

        $this->vista('empleados/form', [
            'titulo' => 'Nuevo empleado',
            'empleado' => null,
            'departamentos' => $departamentos->todos('nombre'),
            'accion' => '/empleados',
        ]);
    }

    public function crear(): void
    {
        $this->exigirSesion();
        $this->validarCsrf();

        [$datos, $errores] = $this->validar($_POST);
        $empleados = new Empleado();

        if ($datos['documento'] !== '' && $empleados->documentoExiste($datos['documento'])) {
            $errores[] = 'Ya existe un empleado con ese documento.';
        }
        if ($datos['correo'] !== '' && $empleados->correoExiste($datos['correo'])) {
            $errores[] = 'Ya existe un empleado con ese correo.';
        }

        if ($errores) {
            $this->flash('error', implode(' ', $errores));
            $this->redirigir('/empleados/nuevo');
        }

        $empleados->crear($datos);
        $this->flash('exito', 'Empleado registrado correctamente.');
        $this->redirigir('/empleados');
    }

    public function editarForm(string $id): void
    {
        $this->exigirSesion();
        $empleados = new Empleado();
        $departamentos = new Departamento();
        $empleado = $empleados->buscar((int) $id);

        if (!$empleado) {
            $this->flash('error', 'Empleado no encontrado.');
            $this->redirigir('/empleados');
        }

        $this->vista('empleados/form', [
            'titulo' => 'Editar empleado',
            'empleado' => $empleado,
            'departamentos' => $departamentos->todos('nombre'),
            'accion' => '/empleados/' . $id,
        ]);
    }

    public function actualizar(string $id): void
    {
        $this->exigirSesion();
        $this->validarCsrf();

        [$datos, $errores] = $this->validar($_POST);
        $empleados = new Empleado();

        if ($datos['documento'] !== '' && $empleados->documentoExiste($datos['documento'], (int) $id)) {
            $errores[] = 'Ya existe otro empleado con ese documento.';
        }
        if ($datos['correo'] !== '' && $empleados->correoExiste($datos['correo'], (int) $id)) {
            $errores[] = 'Ya existe otro empleado con ese correo.';
        }

        if ($errores) {
            $this->flash('error', implode(' ', $errores));
            $this->redirigir('/empleados/' . $id . '/editar');
        }

        $empleados->actualizar((int) $id, $datos);
        $this->flash('exito', 'Empleado actualizado correctamente.');
        $this->redirigir('/empleados');
    }

    public function desactivar(string $id): void
    {
        $this->exigirSesion();
        $this->validarCsrf();

        $empleados = new Empleado();
        $empleados->actualizar((int) $id, ['activo' => 0]);
        $this->flash('exito', 'Empleado desactivado.');
        $this->redirigir('/empleados');
    }

    private function validar(array $datos): array
    {
        $errores = [];

        $campos = [
            'nombre' => trim($datos['nombre'] ?? ''),
            'apellido' => trim($datos['apellido'] ?? ''),
            'documento' => trim($datos['documento'] ?? ''),
            'correo' => trim($datos['correo'] ?? ''),
            'telefono' => trim($datos['telefono'] ?? ''),
            'puesto' => trim($datos['puesto'] ?? ''),
            'departamento_id' => (int) ($datos['departamento_id'] ?? 0),
            'salario' => (float) ($datos['salario'] ?? 0),
            'fecha_contratacion' => $datos['fecha_contratacion'] ?? '',
            'activo' => 1,
        ];

        if ($campos['nombre'] === '' || $campos['apellido'] === '') {
            $errores[] = 'El nombre y el apellido son obligatorios.';
        }
        if ($campos['documento'] === '') {
            $errores[] = 'El documento es obligatorio.';
        }
        if (!filter_var($campos['correo'], FILTER_VALIDATE_EMAIL)) {
            $errores[] = 'El correo no es válido.';
        }
        if ($campos['puesto'] === '') {
            $errores[] = 'El puesto es obligatorio.';
        }
        if ($campos['departamento_id'] <= 0) {
            $errores[] = 'Seleccioná un departamento.';
        }
        if ($campos['fecha_contratacion'] === '') {
            $errores[] = 'La fecha de contratación es obligatoria.';
        }
        if ($campos['salario'] < 0) {
            $errores[] = 'El salario no puede ser negativo.';
        }

        return [$campos, $errores];
    }
}
