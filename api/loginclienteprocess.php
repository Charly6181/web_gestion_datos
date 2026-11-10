<?php
declare(strict_types=1);

session_start();
header('Content-Type: application/json; charset=utf-8');

// Comprobamos que la petición sea estrictamente por POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['exito' => false, 'mensaje' => 'Método no permitido']);
    exit;
}

$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';

if (empty($email) || empty($password)) {
    echo json_encode(['exito' => false, 'mensaje' => 'Por favor, completa todos los campos']);
    exit;
}

require_once __DIR__ . '/../controllers/clienteController.php';

try {
    $controller = new ClienteController();
    
    $resultado = $controller->procesarLoginCliente($email, $password);

    if ($resultado['exito']) {
        $cliente = $resultado['cliente'];

        // Guardamos los datos del cliente
        $_SESSION['cliente_id'] = $cliente->getId();
        $_SESSION['cliente_nombre'] = $cliente->getNombre();
        $_SESSION['cliente_apellidos'] = $cliente->getApellidos();
        $_SESSION['cliente_email'] = $cliente->getEmail();

        echo json_encode([
            'exito' => true,
            'redirect' => 'vistadashboardcliente.php' // Página privada a donde se redirigirá al cliente
        ]);
    } else {
        // Si falla el login, guardamos opcionalmente el email anterior y mandamos el mensaje de error
        $_SESSION['emailAnterior'] = $email;
        
        echo json_encode([
            'exito' => false,
            'mensaje' => $resultado['mensaje']
        ]);
    }

} catch (\Throwable $e) {
    echo json_encode([
        'exito' => false,
        'mensaje' => 'Error en el servidor. Inténtelo más tarde.'
    ]);
}
exit;