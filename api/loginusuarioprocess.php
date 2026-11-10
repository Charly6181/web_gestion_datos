<?php

declare(strict_types=1);

session_start();

require_once __DIR__ . '/../controllers/authController.php';

// Aseguramos que la respuesta sea siempre en formato JSON
header('Content-Type: application/json; charset=utf-8');

// Seguridad: Si intentan entrar por URL directamente (GET), los mandamos al login
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: vistaloginusuario.php');
    exit;
}

$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';

$auth = new AuthController();
$resultado = $auth->login($email, $password);

if (!$resultado['exito']) {
    // si falla sale mensaje de error
    echo json_encode([
        'exito' => false,
        'mensaje' => $resultado['mensaje']
    ]);
    exit;
}

// si login ok te manda al dashboard
echo json_encode([
    'exito' => true,
    'redirect' => 'vistadashboardusuario.php'
]);
exit;


/* if ($resultado['exito']) {

    unset($_SESSION['mensajeError'], $_SESSION['emailAnterior']);
    header('Location: vistadashboardusuario.php');
    exit;
} else {

    $_SESSION['mensajeError'] = $resultado['mensaje'];
    $_SESSION['emailAnterior'] = $email;

    header('Location: vistaloginusuario.php');
    exit;
} */
