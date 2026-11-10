<?php

declare(strict_types=1);

session_start();
header('Content-Type: application/json');


if (!isset($_SESSION['usuario_id'])) {
    echo json_encode([
        'exito' => false,
        'mensaje' => 'Acceso denegado. Debes iniciar sesión.'
    ]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode([
        'exito' => false,
        'mensaje' => 'Método no permitido.'
    ]);
    exit;
}


require_once __DIR__ . '/../controllers/clienteController.php';

$usuarioId       = (int)$_SESSION['usuario_id'];
$nombre          = $_POST['nombre'] ?? '';
$apellidos       = $_POST['apellidos'] ?? '';
$dni             = $_POST['dni'] ?? '';
$email           = $_POST['email'] ?? '';
$direccion       = $_POST['direccion'] ?? '';
$password        = $_POST['password'] ?? '';
$passwordConfirm = $_POST['password_confirm'] ?? '';

$controller = new ClienteController();
$resultado = $controller->registrarCliente(
    $usuarioId,
    $nombre,
    $apellidos,
    $dni,
    $email,
    $direccion,
    $password,
    $passwordConfirm
);

echo json_encode($resultado);
exit;
