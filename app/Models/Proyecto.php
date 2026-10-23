<?php

namespace App\Models;

use App\Core\Model;

class Proyecto extends Model
{
    protected string $tabla = 'proyectos';

    public const ESTADOS = ['planificacion', 'en_progreso', 'completado', 'cancelado'];

    public const ETIQUETAS_ESTADO = [
        'planificacion' => 'Planificación',
        'en_progreso' => 'En progreso',
        'completado' => 'Completado',
        'cancelado' => 'Cancelado',
    ];

    public function conDepartamentoYAvance(): array
    {
        $proyectos = $this->db->query(
            'SELECT p.*, d.nombre AS departamento_nombre
             FROM proyectos p
             JOIN departamentos d ON d.id = p.departamento_id
             ORDER BY FIELD(p.estado, "en_progreso", "planificacion", "completado", "cancelado"), p.fecha_inicio DESC'
        )->fetchAll();

        foreach ($proyectos as &$p) {
            $p['avance'] = $this->avance((int) $p['id']);
        }

        return $proyectos;
    }

    public function conDepartamento(int $id): ?array
    {
        $stmt = $this->db->prepare(
            'SELECT p.*, d.nombre AS departamento_nombre
             FROM proyectos p
             JOIN departamentos d ON d.id = p.departamento_id
             WHERE p.id = ?'
        );
        $stmt->execute([$id]);
        $fila = $stmt->fetch();
        return $fila ?: null;
    }

    /** Porcentaje de tareas completadas del proyecto (0 si no tiene tareas). */
    public function avance(int $proyectoId): int
    {
        $stmt = $this->db->prepare(
            "SELECT
                COUNT(*) AS total,
                SUM(CASE WHEN estado = 'completada' THEN 1 ELSE 0 END) AS completadas
             FROM tareas WHERE proyecto_id = ?"
        );
        $stmt->execute([$proyectoId]);
        $fila = $stmt->fetch();

        if (!$fila || (int) $fila['total'] === 0) {
            return 0;
        }

        return (int) round(((int) $fila['completadas'] / (int) $fila['total']) * 100);
    }

    public function enProgreso(): int
    {
        $stmt = $this->db->query("SELECT COUNT(*) FROM proyectos WHERE estado = 'en_progreso'");
        return (int) $stmt->fetchColumn();
    }
}
