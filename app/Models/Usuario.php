<?php

namespace App\Models;

use App\Core\Model;

class Usuario extends Model
{
    protected string $tabla = 'usuarios';

    public function porCorreo(string $correo): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM usuarios WHERE correo = ?');
        $stmt->execute([$correo]);
        $fila = $stmt->fetch();
        return $fila ?: null;
    }

    public function verificarClave(string $correo, string $clave): ?array
    {
        $usuario = $this->porCorreo($correo);
        if ($usuario && password_verify($clave, $usuario['clave'])) {
            return $usuario;
        }
        return null;
    }
}
