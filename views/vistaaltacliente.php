<?php

declare(strict_types=1);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// si no hay sesion, al login
if (!isset($_SESSION['usuario_id'])) {
    header('Location: vistaloginusuario.php');
    exit;
}

$pageTitle = "Alta de Cliente";
$pageActive = "alta_cliente"; //para marcar en el sidebar donde estas
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
                <span class="navbar-brand text-light fw-bold">Gestión de Clientes</span>
                <div class="d-flex align-items-center gap-3">
                    <a href="logout.php" class="btn btn-outline-danger btn-sm">Cerrar sesión</a>
                </div>
            </div>
        </nav>

        <div class="dashboard-container">

            <?php
            // menu lateral
            require_once __DIR__ . '/includes/sidebar.php';
            ?>

            <main class="main-content">
                <div class="container py-4">
                    <div class="row justify-content-center">
                        <div class="col-md-10 col-lg-8">

                            <!-- Tarjeta del Formulario -->
                            <div class="card card-welcome shadow-lg border-0 p-4 bg-dark text-light">
                                <h2 class="mb-4 text-info border-bottom border-secondary pb-2">Registrar Nuevo Cliente</h2>

                                <!-- Contenedor para mostrar mensajes de error/éxito (JS lo usará) -->
                                <div id="alert-container"></div>

                                <!-- FORMULARIO -->
                                <form id="alta-cliente-form" action="altaclienteprocess.php" method="POST" novalidate>

                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="nombre" class="form-label text-light">Nombre:</label>
                                            <input type="text" class="form-control bg-transparent text-light border-secondary" id="nombre" name="nombre" required>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="apellidos" class="form-label text-light">Apellidos:</label>
                                            <input type="text" class="form-control bg-transparent text-light border-secondary" id="apellidos" name="apellidos" required>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="dni" class="form-label text-light">DNI / CIF:</label>
                                            <input type="text" class="form-control bg-transparent text-light border-secondary" id="dni" name="dni">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="email" class="form-label text-light">Correo electrónico:</label>
                                            <input type="email" class="form-control bg-transparent text-light border-secondary" id="email" name="email" required>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-12 mb-3">
                                            <label for="direccion" class="form-label text-light">Dirección (Opcional):</label>
                                            <input type="text" class="form-control bg-transparent text-light border-secondary" id="direccion" name="direccion">
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="password" class="form-label text-light">Contraseña:</label>
                                            <input type="password" class="form-control bg-transparent text-light border-secondary" id="password" name="password" placeholder="********" required>
                                        </div>
                                        <div class="col-md-6 mb-4">
                                            <label for="password_confirm" class="form-label text-light">Repita la contraseña:</label>
                                            <input type="password" class="form-control bg-transparent text-light border-secondary" id="password_confirm" name="password_confirm" placeholder="********" required>
                                        </div>
                                    </div>

                                    <div class="d-flex justify-content-end gap-2">
                                        <!-- Botón para volver al listado sin guardar -->
                                        <a href="vistacliente.php" class="btn btn-outline-secondary shadow-sm">Cancelar</a>
                                        <!-- Botón de guardado -->
                                        <button type="submit" class="btn btn-info text-dark fw-bold shadow-sm">Guardar Cliente</button>
                                    </div>
                                </form>

                            </div><!-- /card -->
                        </div>
                    </div>
                </div>
                <!-- Espaciador flexible para empujar el footer abajo si hay poco contenido -->
                <div style="flex: 1;"></div>

            </main>

            <?php require_once __DIR__ . '/includes/footer.php'; ?>

        </div>
    </div>

    <!-- Enlazamos el JS que gestionará el envío por AJAX -->
    <script src="../assets/js/altaCliente.js"></script>
</body>

</html>