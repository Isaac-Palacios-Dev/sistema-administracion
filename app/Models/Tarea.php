<?php

namespace App\Models;

use App\Core\Model;

class Tarea extends Model
{
    protected string $tabla = 'tareas';

    public const ESTADOS = ['pendiente', 'en_progreso', 'completada'];
    public const PRIORIDADES = ['baja', 'media', 'alta'];

    public const ETIQUETAS_ESTADO = [
        'pendiente' => 'Pendiente',
        'en_progreso' => 'En progreso',
        'completada' => 'Completada',
    ];

    public function delProyecto(int $proyectoId): array
    {
        $stmt = $this->db->prepare(
            'SELECT t.*, CONCAT(e.nombre, " ", e.apellido) AS empleado_nombre
             FROM tareas t
             LEFT JOIN empleados e ON e.id = t.empleado_id
             WHERE t.proyecto_id = ?
             ORDER BY FIELD(t.prioridad, "alta", "media", "baja"), t.fecha_limite'
        );
        $stmt->execute([$proyectoId]);
        return $stmt->fetchAll();
    }

    public function conDetalle(array $filtros = []): array
    {
        $sql = 'SELECT t.*, p.nombre AS proyecto_nombre,
                       CONCAT(e.nombre, " ", e.apellido) AS empleado_nombre
                FROM tareas t
                JOIN proyectos p ON p.id = t.proyecto_id
                LEFT JOIN empleados e ON e.id = t.empleado_id
                WHERE 1 = 1';
        $parametros = [];

        if (!empty($filtros['estado'])) {
            $sql .= ' AND t.estado = ?';
            $parametros[] = $filtros['estado'];
        }
        if (!empty($filtros['empleado_id'])) {
            $sql .= ' AND t.empleado_id = ?';
            $parametros[] = $filtros['empleado_id'];
        }

        $sql .= ' ORDER BY FIELD(t.prioridad, "alta", "media", "baja"), t.fecha_limite';

        $stmt = $this->db->prepare($sql);
        $stmt->execute($parametros);
        return $stmt->fetchAll();
    }

    public function pendientes(): int
    {
        $stmt = $this->db->query("SELECT COUNT(*) FROM tareas WHERE estado != 'completada'");
        return (int) $stmt->fetchColumn();
    }

    public function vencidas(): int
    {
        $stmt = $this->db->prepare(
            "SELECT COUNT(*) FROM tareas
             WHERE estado != 'completada' AND fecha_limite IS NOT NULL AND fecha_limite < ?"
        );
        $stmt->execute([date('Y-m-d')]);
        return (int) $stmt->fetchColumn();
    }
}
