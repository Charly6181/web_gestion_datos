<?php
declare(strict_types=1);

session_start();

// Si el usuario ya ha iniciado sesión, nos evitamos mostrar la página de bienvenida 
// y lo mandamos directamente al Dashboard
if (isset($_SESSION['usuario_id'])) {
    header('Location: views/vistadashboardusuario.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bienvenido</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <link rel="stylesheet" href="assets/css/styles.css">
</head>

<body class="d-flex align-items-center justify-content-center vh-100">

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">

                <!-- Tarjeta con la clase card-welcome integrada -->
                <div class="card card-welcome shadow-lg border-0 p-4 text-center">
                    <h1 class="fw-bold mb-3">¡Bienvenido!</h1>
                    <p class="mb-4">Accede a tu cuenta o regístrate para comenzar.</p>
                    
                    <div class="d-grid gap-3">
                        <a href="views/vistaloginusuario.php" class="btn btn-primary btn-lg shadow-sm">Iniciar Sesión</a>
                        <a href="views/vistaregistrousuario.php" class="btn btn-outline-secondary btn-lg shadow-sm">Registro</a>
                        <a href="views/vistalogincliente.php" class="btn btn-outline-secondary btn-lg shadow-sm">Clientes</a>
                        <a href="views/vistaparametrosconexion.php" class="btn btn-outline-secondary btn-lg shadow-sm">Cambiar Base de Datos</a>
                    </div>
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

</html>