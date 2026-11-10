<?php

declare(strict_types=1);

session_start();
header('Content-Type: application/json; charset=utf-8');

if (!isset($_SESSION['usuario_id'])) {
    echo json_encode(['exito' => false, 'mensaje' => 'No autorizado']);
    exit;
}

require_once __DIR__ . '/../controllers/clienteController.php';

$usuarioId = (int)$_SESSION['usuario_id'];
$controller = new ClienteController();
$clientes = $controller->obtenerClientesPorUsuario($usuarioId);

$datosClientes = [];
foreach ($clientes as $cliente) {
    $datosClientes[] = [
        'id' => $cliente->getId(),
        'nombre' => $cliente->getNombre(),
        'apellidos' => $cliente->getApellidos(),
        'dni' => $cliente->getDni(),
        'email' => $cliente->getEmail(),
        'direccion' => $cliente->getDireccion(),
        'activo' => $cliente->getActivo()
    ];
}

echo json_encode(['exito' => true, 'clientes' => $datosClientes]);
exit;
