<?php

declare(strict_types=1);

header('Content-Type: application/json');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['usuario_id'])) {
    echo json_encode(['exito' => false, 'mensaje' => 'No autorizado.']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['exito' => false, 'mensaje' => 'Método no permitido.']);
    exit;
}

require_once __DIR__ . '/../controllers/clienteController.php';

$controller = new ClienteController();
$clienteId = isset($_POST['id']) ? (int)$_POST['id'] : 0;

$resultado = $controller->eliminarCliente($clienteId);

echo json_encode($resultado);
exit;
