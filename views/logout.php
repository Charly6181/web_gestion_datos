<?php

declare(strict_types=1);

require_once __DIR__ . '/../controllers/authController.php';

$auth = new AuthController();
$auth->logout();

header('Location: vistaloginusuario.php');
exit;
