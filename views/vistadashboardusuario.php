<?php

declare(strict_types=1);

// 1. Control de sesión directo en la vista
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['usuario_id'])) {
    header('Location: vistaloginusuario.php');
    exit;
}

// Variables para esta vista
$pageTitle = "Inicio";
$pageActive = "inicio";
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

    <!-- Contenedor general -->
    <div class="dashboard-wrapper">

        <!-- Navbar superior colocado de forma explícita -->
        <nav class="navbar navbar-expand-lg top-navbar px-4 py-3">
            <div class="container-fluid">
                <span class="navbar-brand text-light fw-bold">Página principal</span>

                <div class="d-flex align-items-center gap-3">
                    <a href="logout.php" class="btn btn-outline-danger btn-sm">Cerrar sesión</a>
                </div>
            </div>
        </nav>

        <!-- Contenedor para sidebar y contenido -->
        <div class="dashboard-container">

            <?php
            // Cargamos el menú lateral (sidebar)
            require_once __DIR__ . '/includes/sidebar.php';
            ?>

            <!-- Contenido principal específico del Dashboard -->
            <main class="main-content">

                <!-- Espaciador invisible para empujar la tarjeta al centro exacto -->
                <div style="flex: 1;"></div>

                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-md-8 col-lg-6">

                            <!-- Tarjeta de bienvenida con estilo de cristal acuático -->
                            <div class="card card-welcome shadow-lg border-0 p-4 text-center">

                                <h1 class="h3 fw-bold mb-3 text-light">Bienvenido, <?= htmlspecialchars($_SESSION['usuario_nombre'] ?? 'Usuario') ?></h1>

                                <p class="text-light mb-4">
                                    Sesión activa para:<br>
                                    <strong class="text-info"><?= htmlspecialchars($_SESSION['usuario_email'] ?? '') ?></strong>
                                </p>

                                <hr class="border-secondary my-4">

                                <div class="d-grid">
                                    <a href="logout.php" class="btn btn-outline-danger btn-lg shadow-sm">Cerrar sesión</a>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                <!-- Segundo espaciador invisible para equilibrar el espacio hasta el footer -->
                <div style="flex: 1;"></div>

                <?php
                // Cargamos el footer que cierra el HTML y carga los scripts necesarios
                require_once __DIR__ . '/includes/footer.php';
                ?>
            </main>
        </div>
    </div>


</body>

</html>