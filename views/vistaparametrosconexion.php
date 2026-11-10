<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Configuración de Base de Datos</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="../assets/css/styles.css">
</head>

<body class="d-flex align-items-center justify-content-center vh-100">

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">

                <!-- Efecto tarjeta azul con animacion -->
                <div class="card card-welcome shadow-lg border-0 p-4">

                    <h2 class="mb-3 text-center fs-4 fw-bold text-light">Configuración de Base de Datos</h2>
                    <p class="text-center small mb-4 text-info">Los parámetros introducidos se guardarán cifrados de forma segura.</p>

                    <form action="../controllers/encryptController.php" method="POST">

                        <div class="mb-3">
                            <label for="db_host" class="form-label fw-semibold text-light">DB_HOST:</label>
                            <input type="text" class="form-control bg-transparent text-light border-secondary" id="db_host" name="db_host" placeholder="localhost" required>
                        </div>

                        <div class="mb-3">
                            <label for="db_user" class="form-label fw-semibold text-light">DB_USER:</label>
                            <input type="text" class="form-control bg-transparent text-light border-secondary" id="db_user" name="db_user" placeholder="root" required>
                        </div>

                        <div class="mb-3">
                            <label for="db_pass" class="form-label fw-semibold text-light">DB_PASS:</label>
                            <input type="password" class="form-control bg-transparent text-light border-secondary" id="db_pass" name="db_pass" placeholder="********">
                        </div>

                        <div class="mb-3">
                            <label for="db_name" class="form-label fw-semibold text-light">DB_NAME:</label>
                            <input type="text" class="form-control bg-transparent text-light border-secondary" id="db_name" name="db_name" placeholder="nombre_db" required>
                        </div>

                        <div class="mb-4">
                            <label for="db_port" class="form-label fw-semibold text-light">DB_PORT:</label>
                            <input type="text" class="form-control bg-transparent text-light border-secondary" id="db_port" name="db_port" value="3306" required>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg fw-bold shadow-sm">Guardar y Encriptar</button>
                        </div>
                    </form>

                </div>

            </div>
        </div>
    </div>

    <!-- Contenedor de olas fluidas en el fondo -->
    <div style="position: absolute; bottom: 0; left: 0; width: 100%; overflow: hidden; line-height: 0; z-index: 1; pointer-events: none; opacity: 0.4;">
        <svg viewBox="0 0 1200 120" preserveAspectRatio="none" style="position: relative; display: block; width: calc(100% + 1.3px); height: 90px;">
            <path d="M0,0 C150,90 350,-40 500,50 C650,140 900,10 1200,40 L1200,120 L0,120 Z" fill="#00b4d8"></path>
        </svg>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>