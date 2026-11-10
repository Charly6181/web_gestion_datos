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
$clienteId = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$nombre = $_POST['nombre'] ?? '';
$apellidos = $_POST['apellidos'] ?? '';
$dni = $_POST['dni'] ?? '';
$email = $_POST['email'] ?? '';
$direccion = $_POST['direccion'] ?? '';

$resultado = $controller->actualizarCliente($clienteId, $nombre, $apellidos, $dni, $email, $direccion);

echo json_encode($resultado);
exit;
