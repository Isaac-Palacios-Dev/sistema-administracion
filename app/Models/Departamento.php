<?php

namespace App\Models;

use App\Core\Model;

class Departamento extends Model
{
    protected string $tabla = 'departamentos';

    public function nombreExiste(string $nombre, ?int $ignorarId = null): bool
    {
        $sql = 'SELECT COUNT(*) FROM departamentos WHERE nombre = ?';
        $parametros = [$nombre];
        if ($ignorarId !== null) {
            $sql .= ' AND id != ?';
            $parametros[] = $ignorarId;
        }
        $stmt = $this->db->prepare($sql);
        $stmt->execute($parametros);
        return (int) $stmt->fetchColumn() > 0;
    }

    /** Departamentos con el conteo de empleados activos, para el panel y los reportes. */
    public function conConteoEmpleados(): array
    {
        return $this->db->query(
            'SELECT d.*, COUNT(e.id) AS total_empleados
             FROM departamentos d
             LEFT JOIN empleados e ON e.departamento_id = d.id AND e.activo = 1
             GROUP BY d.id
             ORDER BY d.nombre'
        )->fetchAll();
    }

    public function tieneEmpleados(int $id): bool
    {
        $stmt = $this->db->prepare('SELECT COUNT(*) FROM empleados WHERE departamento_id = ?');
        $stmt->execute([$id]);
        return (int) $stmt->fetchColumn() > 0;
    }
}
