<?php
declare(strict_types=1);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 1. SEGURIDAD OBLIGATORIA: Si no hay sesión, fuera antes de pintar nada
if (!isset($_SESSION['cliente_id'])) {
    header('Location: vistalogincliente.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Mi Panel de Cliente</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="../assets/css/styles.css">
    <link rel="stylesheet" href="../assets/css/stylesNavigation.css">
</head>

<body>

    <!-- Contenedor general -->
    <div class="dashboard-wrapper">

        <!-- Navbar superior -->
        <nav class="navbar navbar-expand-lg top-navbar px-4 py-3">
            <div class="container-fluid">
                <span class="navbar-brand text-light fw-bold">Portal de Clientes</span>

                <div class="d-flex align-items-center gap-3">
                    <span class="text-light">Hola, <strong class="text-info" id="nombre-usuario-nav">Cliente</strong></span>
                    <a href="logoutcliente.php" class="btn btn-outline-danger btn-sm">Cerrar sesión</a>
                </div>
            </div>
        </nav>

        <!-- Contenedor principal -->
        <div class="dashboard-container">
            <main class="main-content w-100">

                <div class="container py-5">
                    <div class="row justify-content-center">
                        <div class="col-md-8 col-lg-7">

                            <!-- Tarjeta de contenido -->
                            <div class="card card-welcome shadow-lg border-0 p-4 bg-dark text-light">
                                <div class="mb-4 text-center">
                                    <h2 class="text-info mb-1">Tus Datos de Registro</h2>
                                    <p class="text-muted mb-0">Información asociada a tu cuenta de cliente.</p>
                                </div>

                                <!-- Contenedor donde JavaScript inyectará los datos -->
                                <div id="contenedor-datos-cliente">
                                    <div class="text-center text-muted py-4">Cargando tus datos...</div>
                                </div>

                                <hr class="border-secondary my-4">

                                <div class="d-grid">
                                    <a href="logoutcliente.php" class="btn btn-outline-danger shadow-sm">Cerrar sesión</a>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                <div style="flex: 1;"></div>

            </main>
        </div>
    </div>

    <!-- Script de JavaScript que carga los datos mediante fetch -->
    <script src="../assets/js/perfilCliente.js"></script>
</body>

</html>