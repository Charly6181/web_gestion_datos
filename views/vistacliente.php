<?php

declare(strict_types=1);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['usuario_id'])) {
    header('Location: vistaloginusuario.php');
    exit;
}

$pageTitle = "Vista Cliente";
$pageActive = "cliente";
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Listado de Clientes</title>

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
                <span class="navbar-brand text-light fw-bold">Vista de Clientes</span>

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

            <!-- Contenido principal específico de la vista de cliente -->
            <main class="main-content">

                <div class="container py-4">
                    <div class="row">
                        <div class="col-12">

                            <!-- Tarjeta de contenido -->
                            <div class="card card-welcome shadow-lg border-0 p-4 bg-dark text-light">
                                <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3 mb-4">
                                    <div>
                                        <h2 class="text-info mb-1">Listado de Clientes</h2>
                                        <p class="text-muted mb-0">Aquí podrás visualizar y gestionar los datos de tus clientes registrados.</p>
                                    </div>
                                    <!-- Botón rápido para ir al alta de un nuevo cliente -->
                                    <a href="vistaaltacliente.php" class="btn btn-info text-dark fw-bold shadow-sm text-nowrap">+ Nuevo Cliente</a>
                                </div>
                                <!-- Tabla responsive -->
                                <div class="table-responsive">
                                    <table class="table table-dark table-hover align-middle">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Nombre y Apellidos</th>
                                                <th>DNI / CIF</th>
                                                <th>Email</th>
                                                <th>Dirección</th>
                                                <th class="text-center">Estado</th>
                                                <th class="text-center">Editar</th>
                                                <th class="text-center">Eliminar</th>
                                            </tr>
                                        </thead>
                                        <tbody id="lista-clientes">
                                            <!-- JavaScript inyectará las filas de forma asíncrona aquí -->
                                            <tr>
                                                <td colspan="8" class="text-center text-muted py-4">Cargando clientes...</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                <!-- Espaciador flexible para empujar el footer abajo si hay poco contenido -->
                <div style="flex: 1;"></div>

                <?php
                // Cargamos el footer general que cierra el HTML y carga los scripts de Bootstrap
                require_once __DIR__ . '/includes/footer.php';
                ?>
            </main>
        </div>
    </div>

    <!-- Script JS encargado de pedir y pintar los clientes por AJAX -->
    <script src="../assets/js/listarClientes.js"></script>
    <!-- Script para eliminar clientes -->
    <script src="../assets/js/eliminarCliente.js"></script>


</body>

</html>