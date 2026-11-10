<?php

declare(strict_types=1);

class EncryptController     //metodo encriptacion OpenSSL usando el algoritmo de abajo
{
    private const CIPHER_METHOD = 'aes-256-cbc';    //para definir en algoritmo que se va a usar

    private function getOrGenerateAppKey(string $raizProyecto): string      //para generar una clave maestra si no existe
    {
        $envPath = $raizProyecto . '/.env';
        if (file_exists($envPath)) {
            $contenido = file_get_contents($envPath);
            if (preg_match('/^APP_KEY=(.+)$/m', $contenido, $matches)) {     //preg_match busca coincidencia exacta con APP KEY con REGEX
                return trim($matches[1]);       //devuelve la coincidencia
            }
        }

        return bin2hex(random_bytes(32));   //si no existe la genera
    }

    private function encryptPassword(string $password, string $appKeyHex): string
    {
        if (empty($password)) return '';

        $key = hex2bin($appKeyHex);     //coge la masterkey en hexadecimal y la convierte a bytes binarios puros
        $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length(self::CIPHER_METHOD));
        /* iv (Vector de Inicialización) aleatorio. Esto sirve para que, aunque escribas dos veces la misma contraseña en el formulario, 
         el resultado cifrado en el archivo sea completamente distinto */
        $cifrado = openssl_encrypt($password, self::CIPHER_METHOD, $key, 0, $iv);
        return base64_encode($iv . '::' . $cifrado);  //Une el IV y el texto cifrado separados por :: y los codifica en Base64 
    }

    public function procesarEncriptacion(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: views/vistaparametrosconexion.php');
            exit;
        }

        $host = trim($_POST['db_host'] ?? 'localhost');
        $user = trim($_POST['db_user'] ?? '');
        $passRaw = trim($_POST['db_pass'] ?? '');
        $name = trim($_POST['db_name'] ?? '');
        $port = trim($_POST['db_port'] ?? '3306');

        $raizProyecto = dirname(__DIR__);

        // se obtiene o se crea la pass segura
        $appKey = $this->getOrGenerateAppKey($raizProyecto);

        // cifrado de contraseña
        $passCifrada = $this->encryptPassword($passRaw, $appKey);

        // las variables se guardan en texto plano y la contraseña la cifra
        $envContenido = "DB_HOST={$host}\n";
        $envContenido .= "DB_USER={$user}\n";
        $envContenido .= "DB_PASS={$passCifrada}\n";
        $envContenido .= "DB_NAME={$name}\n";
        $envContenido .= "DB_PORT={$port}\n";
        $envContenido .= "APP_KEY={$appKey}\n";

        // sobreescribe el .env
        if (file_put_contents($raizProyecto . '/.env', $envContenido) !== false) {
            echo "<h3>¡Parámetros actualizados y encriptados correctamente en el archivo .env!</h3>";
            echo "<p><a href='../views/vistaparametrosconexion.php'>Volver al formulario</a></p>";
        } else {
            echo "<h3>Hubo un error al escribir en el archivo .env</h3>";
            echo "<p><a href='../views/vistaparametrosconexion.php'>Volver al formulario</a></p>";
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller = new EncryptController();
    $controller->procesarEncriptacion();
}
