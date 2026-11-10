<?php

declare(strict_types=1);

if (session_status() === PHP_SESSION_NONE) {      // Comprobar conexion
    session_start();
}

if (isset($_SESSION['usuario_id'])) {     // Si tiene sesion abierta se manda a pagina principal
    header('Location: vistadashboardusuario.php');
    exit;
}

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Registro de Usuario</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <link rel="stylesheet" href="../assets/css/styles.css">
</head>


<body class="d-flex align-items-center justify-content-center vh-100">

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">

                <!-- Tarjeta con la clase card-welcome y diseño de cristal translúcido -->
                <div class="card card-welcome shadow-lg border-0 p-4">

                    <h2 class="fw-bold mb-4 text-center">Crear cuenta</h2>

                    <!-- Contenedor dinámico para alertas -->
                    <div id="alert-container"></div>

                    <!-- El formulario entero (el script JS ocultará esto si hay éxito) -->
                    <form id="registro-form" method="POST" novalidate>

                        <div class="mb-3">
                            <label for="nombre" class="form-label text-light">Nombre usuario:</label>
                            <input
                                type="text"
                                class="form-control bg-transparent text-light border-secondary"
                                id="nombre"
                                name="nombre"
                                placeholder="Introduce tu nombre"
                                required>
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label text-light">Correo electrónico:</label>
                            <input
                                type="email"
                                class="form-control bg-transparent text-light border-secondary"
                                id="email"
                                name="email"
                                placeholder="Introduce tu email"
                                required>
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label text-light">Contraseña (mínimo 8 caracteres):</label>
                            <input
                                type="password"
                                class="form-control bg-transparent text-light border-secondary"
                                id="password"
                                name="password"
                                placeholder="********"
                                required>
                        </div>

                        <div class="mb-4">
                            <label for="password_confirm" class="form-label text-light">Repita la contraseña:</label>
                            <input
                                type="password"
                                class="form-control bg-transparent text-light border-secondary"
                                id="password_confirm"
                                name="password_confirm"
                                placeholder="********"
                                required>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg shadow-sm">Registrarse</button>
                        </div>
                    </form>

                    <hr class="border-secondary my-4">

                    <p class="mt-2 text-center text-light mb-0">
                        ¿Ya tienes cuenta? <a href="vistaloginusuario.php" class="text-decoration-none fw-bold text-info">Inicia sesión aquí</a>
                    </p>

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

    <script src="../assets/js/registroUsuario.js"></script>
</body>

</html>