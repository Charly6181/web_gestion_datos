<?php

declare(strict_types=1);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['usuario_id'])) {
    header('Location: vistaloginusuario.php');
    exit;
}

require_once __DIR__ . '/../controllers/clienteController.php';

$controller = new ClienteController();
$usuarioId = (int)$_SESSION['usuario_id'];

// Obtener el ID del cliente de la URL
$clienteId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$cliente = $controller->obtenerClientePorId($clienteId);

// Validar que el cliente exista y pertenezca al usuario actual
if (!$cliente || $cliente->getUsuarioId() !== $usuarioId) {
    header('Location: vistacliente.php');
    exit;
}

// Capturar mensajes de error/éxito si vienen redirigidos del proceso
$mensajeError = $_SESSION['error_edicion'] ?? '';
$mensajeExito = $_SESSION['exito_edicion'] ?? '';
unset($_SESSION['error_edicion'], $_SESSION['exito_edicion']);

$pageTitle = "Editar Cliente";
$pageActive = "cliente";
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= $pageTitle ?></title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="../assets/css/styles.css">
    <link rel="stylesheet" href="../assets/css/stylesNavigation.css">
</head>

<body>

    <div class="dashboard-wrapper">

        <!-- Navbar superior -->
        <nav class="navbar navbar-expand-lg top-navbar px-4 py-3">
            <div class="container-fluid">
                <span class="navbar-brand text-light fw-bold">Editar Cliente</span>
                <div class="d-flex align-items-center gap-3">
                    <a href="logout.php" class="btn btn-outline-danger btn-sm">Cerrar sesión</a>
                </div>
            </div>
        </nav>

        <div class="dashboard-container">
            <?php require_once __DIR__ . '/includes/sidebar.php'; ?>

            <main class="main-content">
                <div class="container py-4">
                    <div class="row justify-content-center">
                        <div class="col-md-8">

                            <!-- Efecto tarjeta azul con animacion -->
                            <div class="card card-welcome shadow-lg border-0 p-4">
                                <div class="d-flex justify-content-between align-items-center mb-4">
                                    <h2 class="text-info mb-0">Actualizar datos de cliente</h2>
                                    <a href="vistacliente.php" class="btn btn-outline-secondary btn-sm">← Volver al listado</a>
                                </div>



                                <!-- Contenedor vacío donde JS inyectará las alertas de éxito o error -->
                                <div id="alerta-container"></div>

                                <!-- Formulario con ID para capturarlo desde JavaScript -->
                                <form id="form-editar-cliente" method="POST">
                                    <div class="row">
                                        <div class="mb-3 col-md-6">
                                            <label for="nombre" class="form-label">Nombre:</label>
                                            <input type="text" class="form-control bg-secondary text-light border-0" id="nombre" name="nombre" value="<?= htmlspecialchars($cliente->getNombre()) ?>" required>
                                        </div>
                                        <div class="mb-3 col-md-6">
                                            <label for="apellidos" class="form-label">Apellidos:</label>
                                            <input type="text" class="form-control bg-secondary text-light border-0" id="apellidos" name="apellidos" value="<?= htmlspecialchars($cliente->getApellidos()) ?>" required>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="mb-3 col-md-6">
                                            <label for="dni" class="form-label">DNI / CIF:</label>
                                            <input type="text" class="form-control bg-secondary text-light border-0" id="dni" name="dni" value="<?= htmlspecialchars($cliente->getDni()) ?>">
                                        </div>
                                        <div class="mb-3 col-md-6">
                                            <label for="email" class="form-label">Correo electrónico:</label>
                                            <input type="email" class="form-control bg-secondary text-light border-0" id="email" name="email" value="<?= htmlspecialchars($cliente->getEmail()) ?>" required>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="direccion" class="form-label">Dirección (Opcional):</label>
                                        <input type="text" class="form-control bg-secondary text-light border-0" id="direccion" name="direccion" value="<?= htmlspecialchars($cliente->getDireccion()) ?>">
                                    </div>

                                    <div class="d-flex justify-content-end gap-2 mt-4">
                                        <a href="vistacliente.php" class="btn btn-outline-light">Cancelar</a>
                                        <button type="submit" class="btn btn-info text-dark fw-bold">Guardar Cambios</button>
                                    </div>
                                </form>
                            </div>

                        </div>
                    </div>
                </div>

                <?php require_once __DIR__ . '/includes/footer.php'; ?>
            </main>
        </div>
    </div>

    <script src="../assets/js/actualizarCliente.js"></script>
</body>
</body>

</html>