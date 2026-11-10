<?php

declare(strict_types=1);

require_once __DIR__ . '/../conecction/database.php';
require_once __DIR__ . '/../models/cliente.php';

class ClienteRepository
{
    private mysqli $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }


    public function guardar(Cliente $cliente): bool
    {
        $sql = 'INSERT INTO clientes (usuario_id, nombre, apellidos, dni, email, password_hash, direccion,  activo) VALUES (?, ?, ?, ?, ?, ?, ?, ?)';

        $stmt = $this->db->prepare($sql);

        $usuarioId = $cliente->getUsuarioId();
        $nombre = $cliente->getNombre();
        $apellidos = $cliente->getApellidos();
        $dni = $cliente->getDni();
        $email = $cliente->getEmail();
        $passwordHash = $cliente->getPasswordHash();
        $direccion = $cliente->getDireccion();
        $activo = (int)$cliente->getActivo();

        // 'issssssi' significa: integer, string x6, integer
        $stmt->bind_param('issssssi', $usuarioId, $nombre, $apellidos, $dni,  $email, $passwordHash, $direccion, $activo);

        $exito = $stmt->execute();

        if ($exito) {
            $nuevoId = (int)$this->db->insert_id;
            $cliente->setId($nuevoId);
        }

        return $exito;
    }

    public function obtenerPorUsuarioId(int $usuarioId): array  //consulta para buscar a los clientes que pertenezcan a un usuario
    {
        $sql = 'SELECT id, usuario_id, nombre, apellidos, dni, email, password_hash, direccion, activo 
                FROM clientes WHERE usuario_id = ? ORDER BY activo DESC, id DESC';   //activo DESC pone los activos primero / id desc asegura que creados mas recientemente aparezcan primero en la lista
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('i', $usuarioId);
        $stmt->execute();

        $resultado = $stmt->get_result();
        $clientes = [];

        while ($fila = $resultado->fetch_assoc()) {
            $clientes[] = $this->mapearCliente($fila);
        }

        return $clientes;
    }

    public function buscarPorEmail(string $email): ?Cliente
    {
        $sql = 'SELECT id, usuario_id, nombre, apellidos, dni, email, password_hash, direccion, activo FROM clientes WHERE email = ? LIMIT 1';

        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('s', $email);
        $stmt->execute();

        $resultado = $stmt->get_result();
        $fila = $resultado->fetch_assoc();

        return $fila ? $this->mapearCliente($fila) : null;      ////if else

    }

    public function buscarPorDni(string $dni): ?Cliente
    {
        $sql = 'SELECT id, usuario_id, nombre, apellidos, dni, email, password_hash, direccion, activo FROM clientes WHERE dni = ? LIMIT 1';

        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('s', $dni);
        $stmt->execute();

        $resultado = $stmt->get_result();
        $fila = $resultado->fetch_assoc();

        return $fila ? $this->mapearCliente($fila) : null;
    }

    public function buscarPorId(int $id): ?Cliente
    {
        $sql = 'SELECT id, usuario_id, nombre, apellidos, dni, email, password_hash, direccion, activo FROM clientes WHERE id = ? LIMIT 1';

        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('i', $id);
        $stmt->execute();

        $resultado = $stmt->get_result();
        $fila = $resultado->fetch_assoc();

        return $fila ? $this->mapearCliente($fila) : null;
    }

    public function actualizar(Cliente $cliente): bool
    {
        $sql = 'UPDATE clientes SET nombre = ?, apellidos = ?, dni = ?, email = ?, direccion = ? WHERE id = ?';

        $stmt = $this->db->prepare($sql);

        $nombre = $cliente->getNombre();
        $apellidos = $cliente->getApellidos();
        $dni = $cliente->getDni();
        $email = $cliente->getEmail();
        $direccion = $cliente->getDireccion();
        $id = $cliente->getId();

        $stmt->bind_param('sssssi', $nombre, $apellidos, $dni, $email, $direccion, $id);

        return $stmt->execute();
    }

    private function mapearCliente(array $fila): Cliente
    {
        return new Cliente(
            (int)$fila['usuario_id'],
            $fila['nombre'],
            $fila['apellidos'],
            $fila['dni'] ?? '',
            $fila['email'],
            $fila['password_hash'],
            $fila['direccion'],
            (bool)$fila['activo'],
            (int)$fila['id']
        );
    }

    public function eliminar(int $id): bool
    {
        $sql = 'DELETE FROM clientes WHERE id = ?';

        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('i', $id);

        return $stmt->execute();
    }
}
