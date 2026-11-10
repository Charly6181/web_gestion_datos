<?php
declare(strict_types=1);
session_start();
unset($_SESSION['cliente_id']);
unset($_SESSION['cliente_nombre']);
unset($_SESSION['cliente_email']);
session_destroy();
header('Location: vistalogincliente.php');
exit;