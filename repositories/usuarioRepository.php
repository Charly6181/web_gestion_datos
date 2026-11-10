<?php

declare(strict_types=1);

require_once __DIR__ .  '/../conecction/database.php';
require_once __DIR__ .  '/../models/usuario.php';

class UsuarioRepository
{
    private mysqli $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    public function guardar(Usuario $usuario): bool
    {
        $sql = 'INSERT INTO usuarios (nombre, email, password_hash, activo) VALUES (?, ?, ?, ?)';

        $stmt = $this->db->prepare($sql);

        $nombre = $usuario->getNombre();
        $email = $usuario->getEmail();
        $passwordHash = $usuario->getPasswordHash();
        $activo = (int)$usuario->getActivo();

        // 'sssi' significa: string, string, string, integer
        $stmt->bind_param('sssi', $nombre, $email, $passwordHash, $activo);

        $exito = $stmt->execute();

        if ($exito) {
            $nuevoId = (int)$this->db->insert_id;
            $usuario->setId($nuevoId);
        }

        return $exito;
    }

    public function buscarPorEmail(string $email): ?Usuario
    {
        $sql = 'SELECT id, nombre, email, password_hash, activo FROM usuarios WHERE email = ? LIMIT 1';

        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('s', $email);
        $stmt->execute();

        $resultado = $stmt->get_result();
        $fila = $resultado->fetch_assoc();

        return $fila ? $this->mapearUsuario($fila) : null;      ////if else

    }

    public function buscarPorNombre(string $nombre): ?Usuario      //funcion para comprobar que si existe un usuario en la BBDD
    {
        $sql = 'SELECT id, nombre, email, password_hash, activo FROM usuarios WHERE nombre = ? LIMIT 1';

        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('s', $nombre);
        $stmt->execute();

        $resultado = $stmt->get_result();
        $fila = $resultado->fetch_assoc();

        return $fila ? $this->mapearUsuario($fila) : null;
    }



    public function buscarPorId(int $id): ?Usuario
    {
        $sql = 'SELECT id, nombre, email, password_hash, activo FROM usuarios WHERE id = ? LIMIT 1';

        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('i', $id);
        $stmt->execute();

        $resultado = $stmt->get_result();
        $fila = $resultado->fetch_assoc();

        return $fila ? $this->mapearUsuario($fila) : null;
    }


    private function mapearUsuario(array $fila): Usuario        //metodo para no repetir codigo en las funciones CRUD
    {
        return new Usuario(
            $fila['nombre'],
            $fila['email'],
            $fila['password_hash'],
            (bool)$fila['activo'],
            (int)$fila['id']
        );
    }
}
