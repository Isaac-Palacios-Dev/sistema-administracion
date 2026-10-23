<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Departamento;

class DepartamentoController extends Controller
{
    public function index(): void
    {
        $this->exigirSesion();
        $departamentos = new Departamento();

        $this->vista('departamentos/index', [
            'titulo' => 'Departamentos',
            'departamentos' => $departamentos->conConteoEmpleados(),
        ]);
    }

    public function crearForm(): void
    {
        $this->exigirSesion();
        $this->vista('departamentos/form', [
            'titulo' => 'Nuevo departamento',
            'departamento' => null,
            'accion' => '/departamentos',
        ]);
    }

    public function crear(): void
    {
        $this->exigirSesion();
        $this->validarCsrf();

        [$datos, $errores] = $this->validar($_POST);
        $departamentos = new Departamento();

        if ($datos['nombre'] !== '' && $departamentos->nombreExiste($datos['nombre'])) {
            $errores[] = 'Ya existe un departamento con ese nombre.';
        }

        if ($errores) {
            $this->flash('error', implode(' ', $errores));
            $this->redirigir('/departamentos/nuevo');
        }

        $departamentos->crear($datos);
        $this->flash('exito', 'Departamento creado correctamente.');
        $this->redirigir('/departamentos');
    }

    public function editarForm(string $id): void
    {
        $this->exigirSesion();
        $departamentos = new Departamento();
        $departamento = $departamentos->buscar((int) $id);

        if (!$departamento) {
            $this->flash('error', 'Departamento no encontrado.');
            $this->redirigir('/departamentos');
        }

        $this->vista('departamentos/form', [
            'titulo' => 'Editar departamento',
            'departamento' => $departamento,
            'accion' => '/departamentos/' . $id,
        ]);
    }

    public function actualizar(string $id): void
    {
        $this->exigirSesion();
        $this->validarCsrf();

        [$datos, $errores] = $this->validar($_POST);
        $departamentos = new Departamento();

        if ($datos['nombre'] !== '' && $departamentos->nombreExiste($datos['nombre'], (int) $id)) {
            $errores[] = 'Ya existe otro departamento con ese nombre.';
        }

        if ($errores) {
            $this->flash('error', implode(' ', $errores));
            $this->redirigir('/departamentos/' . $id . '/editar');
        }

        $departamentos->actualizar((int) $id, $datos);
        $this->flash('exito', 'Departamento actualizado correctamente.');
        $this->redirigir('/departamentos');
    }

    private function validar(array $datos): array
    {
        $errores = [];
        $nombre = trim($datos['nombre'] ?? '');
        $descripcion = trim($datos['descripcion'] ?? '');

        if ($nombre === '') {
            $errores[] = 'El nombre es obligatorio.';
        }

        return [['nombre' => $nombre, 'descripcion' => $descripcion], $errores];
    }
}
