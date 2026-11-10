<?php

declare(strict_types=1);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['usuario_id'])) {
    header('Location: vistaloginusuario.php');
    exit;
}

$pageTitle = "Lectura de Depósitos";
$pageActive = "dataTable";
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= $pageTitle ?></title>

    <!-- jsPDF -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <!-- jsPDF AutoTable -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.8.2/jspdf.plugin.autotable.min.js"></script>

    <!-- Librería para generar archivos Excel (.xlsx) -->
    <script src="https://cdn.jsdelivr.net/npm/xlsx-js-style@1.2.0/dist/xlsx.bundle.js"></script>

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
                <span class="navbar-brand text-light fw-bold">Lectura de Depósitos</span>

                <div class="d-flex align-items-center gap-3">
                    <a href="logout.php" class="btn btn-outline-danger btn-sm">Cerrar sesión</a>
                    <button type="button" id="btnExportarPdf" class="btn btn-outline-danger btn-sm px-3 fw-semibold">
                        Generar PDF
                    </button>
                    <button type="button" id="btnExportarExcel" class="btn btn-outline-success btn-sm px-3 fw-semibold">
                        Excel
                    </button>
                </div>
            </div>
        </nav>

        <!-- Contenedor para sidebar y contenido -->
        <div class="dashboard-container">

            <?php
            // Menú lateral
            require_once __DIR__ . '/includes/sidebar.php';
            ?>

            <!-- Contenido principal -->
            <main class="main-content p-4">
                <div class="container-fluid">

                    <!-- Contenedor general que hereda exactamente los estilos de tu CSS -->
                    <div class="card card-welcome p-4 shadow-lg border-0">

                        <!-- titulo y filtro fecha -->
                        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
                            <div>
                                <h1 class="h3 fw-bold mb-1">Lectura de Depósitos</h1>
                                <p class="mb-0">Consulta los pesajes y variaciones de corte por fecha.</p>
                            </div>

                            <form id="formFiltro" class="d-flex align-items-start gap-2">
                                <label for="fecha_filtro" class="mt-1 mb-0 fw-semibold text-light small text-nowrap">Fecha de Corte:</label>

                                <!-- Columna que agrupa el input y la cajita justo debajo arrancando en el mismo punto -->
                                <div class="d-flex flex-column align-items-start gap-1">
                                    <input type="date" id="fecha_filtro" name="fecha_filtro" class="form-control form-control-sm" required>

                                    <span class="badge bg-dark border border-secondary text-secondary py-1 px-2 font-monospace fw-normal" style="font-size: 0.9rem;">
                                        Datos mostrados: <strong id="fecha_datos_mostrada" class="text-light">--/--/----</strong>
                                    </span>
                                </div>

                                <!-- boton consulta -->
                                <button type="submit" class="btn btn-primary btn-sm px-3 fw-semibold">Consultar</button>

                            </form>

                        </div>

                        <div class="table-responsive">
                            <table class="table align-middle">
                                <thead>
                                    <tr>
                                        <!-- <th class="text-center" style="width: 70px;">ID</th> -->
                                        <th>DEPÓSITO</th>
                                        <!-- <th class="text-center">FECHA ANTERIOR</th> -->
                                        <th class="text-end">KILOS ANTERIOR</th>
                                        <!-- <th class="text-center">FECHA ACTUAL</th> -->
                                        <th class="text-end">KILOS ACTUAL</th>
                                        <th class="text-end">DIFERENCIA (KG)</th>
                                    </tr>
                                </thead>
                                <tbody id="tablaCuerpo">
                                    <!-- Filas inyectadas por JS -->
                                </tbody>
                            </table>
                        </div>

                    </div>

                </div>

                <?php require_once __DIR__ . '/includes/footer.php'; ?>
            </main>
        </div>
    </div>

    <!-- Script específico para consultar y pintar la tabla -->
    <script src="../assets/js/dataTable.js"></script>
</body>

</html>