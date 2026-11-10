<?php

declare(strict_types=1);

session_start(); // Iniciar sesión obligatoriamente

$mensajeError = $_SESSION['mensajeError'] ?? '';        // Busca si existe un error o un email guardado previo en la sesion
unset($_SESSION['mensajeError']);   // Borra de la sesion el mensaje de error despues de recargar pagina (Flash message)

$emailAnterior = $_SESSION['emailAnterior'] ?? '';      // Para que si fallan las credenciales no se borre el correo y haya que volver a escribirlo
unset($_SESSION['emailAnterior']);

$emailValue = $_POST['email'] ?? $emailAnterior;

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Acceso Clientes</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="../assets/css/styles.css">
</head>
<!-- Comunicación entre PHP y js -->

<body class="d-flex align-items-center justify-content-center vh-100">

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">

                <!-- Tarjeta con la clase card-welcome y formato de cristal translúcido -->
                <div class="card card-welcome shadow-lg border-0 p-4">

                    <h2 class="fw-bold mb-4 text-center">Iniciar Sesión</h2>

                    <div id="error-container"></div> <!-- Contenedor error -->
                    <!-- añadido id para capturarlo con js -->
                    <form id="login-cliente-form" action="loginclienteprocess.php" method="POST">

                        <div class="mb-3">
                            <label for="email" class="form-label text-light">Correo electrónico:</label>

                            <input type="email" class="form-control bg-transparent text-light border-secondary" id="email" name="email" placeholder="correo@ejemplo.com" required>
                        </div>

                        <div class="mb-4">
                            <label for="password" class="form-label text-light">Contraseña:</label>
                            <!-- Contenedor input-group para integrar el botón del ojito de forma fluida -->
                            <div class="input-group">
                                <input type="password" class="form-control bg-transparent text-light border-secondary" id="password" name="password" placeholder="********" required>
                                <!-- Añadidas clases d-flex align-items-center justify-content-center para centrar el SVG -->
                                <button class="btn btn-outline-secondary border-secondary text-light bg-transparent d-flex align-items-center justify-content-center" type="button" id="togglePassword" title="Mostrar contraseña">
                                    <!-- Icono de ojo abierto (SVG) con pointer-events: none para capturar bien los clics -->
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-eye" viewBox="0 0 16 16" style="pointer-events: none;">
                                        <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8M1.173 8a13.133 13.133 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13.133 13.133 0 0 1 14.828 8c-.058.087-.122.183-.195.288-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5c-2.12 0-3.879-1.168-5.168-2.457A13.134 13.134 0 0 1 1.172 8z" />
                                        <path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5m4.5 2.5a4.5 4.5 0 1 1-9 0 4.5 4.5 0 0 1 9 0" />
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <div class="d-grid gap-2">
                            <!-- Añadimos un ID al botón para bloquearlo y mostrar estado de carga -->
                            <button type="submit" id="btn-submit" class="btn btn-primary btn-lg shadow-sm">Acceder</button>
                        </div>

                        <hr class="border-secondary my-4">

                        <p class="mt-2 text-center text-light mb-0">
                            ¿Eres gestor de clientes? <a href="vistaloginusuario.php" class="text-decoration-none fw-bold text-info">Accede aquí</a>
                        </p>
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="../assets/js/loginCliente.js"></script>
</body>

</html>