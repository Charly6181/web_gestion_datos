<?php

declare(strict_types=1);

session_start();
header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    echo json_encode([
        'exito' => false,
        'mensaje' => 'Método no permitido'
    ]);
    exit;
}

//require_once __DIR__ . '/../controllers/depositoCorteController.php';
require_once __DIR__ . '/../conecction/Database.php';
try {
    $fecha = trim($_GET['fecha_filtro'] ?? '');
    /* echo json_encode($fecha); */

    // $controller = new DepositoCorteController();
    //$resultado = $controller->obtenerLectura($fecha);
    $db = Database::getConnection();
    $depositos = [];
    $sql = 'CALL olibest.depositoCorteLectura(?)';

    $stmt = $db->prepare($sql);
    $stmt->bind_param('s', $fecha);
    $stmt->execute();

    $resultado = $stmt->get_result();

    if ($resultado) {
        while ($fila = $resultado->fetch_assoc()) {
            $depositos[] = $fila;
        }
        $resultado->free();
    }

    $stmt->close();
    // limpia el canal para poder hacer una nueva busqueda sin que de error
    while ($db->more_results() && $db->next_result()) {
        if ($extra = $db->use_result()) {
            $extra->free();
        }
    }

    echo json_encode([
        'exito' => true,
        'datos' => $depositos
    ]);
} catch (\Throwable $e) {
    echo json_encode([
        'exito' => false,
        'mensaje' => 'Error en el servidor. Inténtelo más tarde.'
    ]);
}
exit;
