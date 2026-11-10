<?php

declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__), '.env');
$dotenv->load();

// desencripta la contraseña para pasarla a la conexion
function desencriptarPassword(string $dbPassCifrada, string $appKeyHex): string
{
    if (empty($dbPassCifrada)) {

        return ''; // Si la contraseña llega vacia se detiene
    }

    $key = hex2bin(trim($appKeyHex)); //Convierte la clave maestra de cifrado de formato hexadecimal a formato binario puro
    $decoded = base64_decode($dbPassCifrada);

    if (!str_contains($decoded, '::')) {
        return $dbPassCifrada;
    }

    [$iv, $encrypted] = explode('::', $decoded, 2);     //explode separa un texto cuando encuentre el separador (en este caso '::')
    $desencriptada = openssl_decrypt($encrypted, 'aes-256-cbc', $key, 0, $iv);


    return $desencriptada !== false ? $desencriptada : '';      //operador ternario 
}

define('DB_HOST', $_ENV['DB_HOST']);
define('DB_USER', $_ENV['DB_USER']);
// desencriptamos la pass usando la maestra
define('DB_PASS', desencriptarPassword($_ENV['DB_PASS'] ?? '', $_ENV['APP_KEY'] ?? ''));
define('DB_NAME', $_ENV['DB_NAME']);
define('DB_PORT', (int)$_ENV['DB_PORT']);

class Database
{
    private static ?mysqli $instance = null;

    private function __construct() {}

    public static function getConnection(): mysqli
    {
        if (self::$instance === null) {
            mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

            try {
                self::$instance = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME, DB_PORT);
                self::$instance->set_charset("utf8mb4");
            } catch (mysqli_sql_exception $e) {
                die("Error de conexión a la base de datos (Clase): " . $e->getMessage());
            }
        }

        return self::$instance;
    }
}
