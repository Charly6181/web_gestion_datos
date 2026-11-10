<?php
declare(strict_types=1);

session_start();
header('Content-Type: application/json; charset=utf-8');

// Comprobamos que sea un cliente autenticado
if (!isset($_SESSION['cliente_id'])) {
    echo json_encode(['exito' => false, 'mensaje' => 'No autorizado']);
    exit;
}

require_once __DIR__ . '/../Controllers/ClienteController.php';

$clienteId = (int)$_SESSION['cliente_id'];
$controller = new ClienteController();
$cliente = $controller->obtenerClientePorId($clienteId);

if (!$cliente) {
    echo json_encode(['exito' => false, 'mensaje' => 'Cliente no encontrado']);
    exit;
}

// Devolvemos únicamente los datos de este cliente en formato JSON
echo json_encode([
    'exito' => true,
    'cliente' => [
        'nombre' => $cliente->getNombre(),
        'apellidos' => $cliente->getApellidos(),
        'dni' => $cliente->getDni(),
        'email' => $cliente->getEmail(),
        'direccion' => $cliente->getDireccion()
    ]
]);
exit;