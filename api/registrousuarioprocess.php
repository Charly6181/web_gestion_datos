<?php

declare(strict_types=1);

session_start();

// la respuesta será en formato JSON
header('Content-Type: application/json');

require_once __DIR__ . '/../controllers/authController.php';

// Seguridad: Si intentan entrar por URL directamente (GET), los mandamos al formulario
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: vistaregistrousuario.php');
    exit;
}

$nombre          = $_POST['nombre'] ?? '';
$email           = $_POST['email'] ?? '';
$password        = $_POST['password'] ?? '';
$passwordConfirm = $_POST['password_confirm'] ?? '';

$auth = new AuthController();
$resultado = $auth->registrar($nombre, $email, $password, $passwordConfirm);

echo json_encode($resultado);
exit;
