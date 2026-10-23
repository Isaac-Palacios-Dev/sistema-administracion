<?php

namespace App\Models;

use App\Core\Model;

class Empleado extends Model
{
    protected string $tabla = 'empleados';

    public function conDepartamento(?int $departamentoId = null, bool $soloActivos = true): array
    {
        $sql = 'SELECT e.*, d.nombre AS departamento_nombre
                FROM empleados e
                JOIN departamentos d ON d.id = e.departamento_id
                WHERE 1 = 1';
        $parametros = [];

        if ($soloActivos) {
            $sql .= ' AND e.activo = 1';
        }
        if ($departamentoId) {
            $sql .= ' AND e.departamento_id = ?';
            $parametros[] = $departamentoId;
        }

        $sql .= ' ORDER BY e.apellido, e.nombre';

        $stmt = $this->db->prepare($sql);
        $stmt->execute($parametros);
        return $stmt->fetchAll();
    }

    public function activos(): array
    {
        return $this->db->query(
            "SELECT * FROM empleados WHERE activo = 1 ORDER BY apellido, nombre"
        )->fetchAll();
    }

    public function documentoExiste(string $documento, ?int $ignorarId = null): bool
    {
        $sql = 'SELECT COUNT(*) FROM empleados WHERE documento = ?';
        $parametros = [$documento];
        if ($ignorarId !== null) {
            $sql .= ' AND id != ?';
            $parametros[] = $ignorarId;
        }
        $stmt = $this->db->prepare($sql);
        $stmt->execute($parametros);
        return (int) $stmt->fetchColumn() > 0;
    }

    public function correoExiste(string $correo, ?int $ignorarId = null): bool
    {
        $sql = 'SELECT COUNT(*) FROM empleados WHERE correo = ?';
        $parametros = [$correo];
        if ($ignorarId !== null) {
            $sql .= ' AND id != ?';
            $parametros[] = $ignorarId;
        }
        $stmt = $this->db->prepare($sql);
        $stmt->execute($parametros);
        return (int) $stmt->fetchColumn() > 0;
    }
}
