<?php

declare(strict_types=1);        //logica de negocio relacionada con la gestion de usuarios

require_once __DIR__ .  '/../repositories/usuarioRepository.php';

class AuthController
{
    private UsuarioRepository $repo;

    public function __construct()
    {
        $this->repo = new UsuarioRepository;
    }

    public function login(string $email, string $password): array
    {
        $emailSaneado = filter_var(trim($email), FILTER_SANITIZE_EMAIL);

        if (empty($emailSaneado) || empty($password)) {
            return [
                'exito' => false,
                'mensaje' => 'Por favor, completa todos los campos.'
            ];
        }

        $usuario = $this->repo->buscarPorEmail($emailSaneado);



        if ($usuario === null || !$usuario->verificarPassword($password)) {
            return [
                'exito' => false,
                'mensaje' => 'Credenciales incorrectas.'
            ];
        }

        if (!$usuario->getActivo()) {
            return [
                'exito' => false,
                'mensaje' => 'Tu cuenta está desactivada.'
            ];
        }

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        session_regenerate_id(true); //metodo seguridad para generar id nuevo a cada sesión

        $_SESSION['usuario_id'] = $usuario->getId();
        $_SESSION['usuario_nombre'] = $usuario->getNombre();
        $_SESSION['usuario_email'] = $usuario->getEmail();

        return [
            'exito' => true,
            'mensaje' => 'Autenticación exitosa.'
        ];
    }

    public function registrar(string $nombre, string $email, string $password, string $passwordConfirm): array
    {
        $nombreSaneado = trim($nombre);
        $emailSaneado = filter_var(trim($email), FILTER_SANITIZE_EMAIL);

        if (empty($nombreSaneado) || empty($emailSaneado) || empty($password) || empty($passwordConfirm)) {
            return [
                'exito' => false,
                'mensaje' => 'Por favor, rellene todos los campos.'
            ];
        }

        $patronEstricto = '/\.([a-zA-Z]{2,3}|info)$/i';
        // \. (indica el punto) [] (despues lo que puede ir despues del punto) {2,3} (te pide obligatoriamente 2 letras despues hasta 3 letra) i' case sensitive

        if (!filter_var($emailSaneado, FILTER_VALIDATE_EMAIL) || !preg_match($patronEstricto, $emailSaneado)) {      //Valida email para que tenga @ dominio.com .es .loquesea
            return [
                'exito' => false,
                'mensaje' => 'El formato de correo electrónico no es válido.'
            ];
        }

        if ($password !== $passwordConfirm) {     //Coincidencia contraseñas
            return [
                'exito' => false,
                'mensaje' => 'Las contraseñas introducidas no coinciden.'
            ];
        }

        if (strlen($password) < 8) {       //Longitud minima password
            return [
                'exito' => false,
                'mensaje' => 'La contraseña debe contener al menos 8 caracteres.'
            ];
        }

        if ($this->repo->buscarPorNombre($nombreSaneado) !== null) {        //para comprobar el usuario en bbdd no exista y lanze mensaje de error
            return [
                'exito' => false,
                'mensaje' => 'El nombre de usuario ya está en uso. Por favor, elige otro.'
            ];
        }

        if ($this->repo->buscarPorEmail($emailSaneado) !== null) {        //Verificar que no existe usuario en BBDD
            return [
                'exito' => false,
                'mensaje' => 'Ya existe una cuenta con este correo electrónico.'
            ];
        }

        try {
            $nuevoUsuario = new Usuario();
            $nuevoUsuario->setNombre($nombreSaneado);
            $nuevoUsuario->setEmail($emailSaneado);
            $nuevoUsuario->setPasswordPlana($password);
            $nuevoUsuario->setActivo(true);

            $guardado = $this->repo->guardar($nuevoUsuario);

            if ($guardado) {
                return [
                    'exito' => true,
                    'mensaje' => 'Usuario registrado con éxito'
                ];
            }

            return [
                'exito' => false,
                'mensaje' => 'Error: No se ha podido guardar el usuario en la BBDD.'
            ];
        } catch (\Throwable $e) {
            return [
                'exito' => false,
                'mensaje' => 'Error durante el registro: ' . $e->getMessage()
            ];
        }
    }
    public function logout(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // 1. Vacía el array en memoria
        $_SESSION = [];

        // 2. Destruye la cookie de sesión en el navegador
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000,     //Tiempo de cookie en el navegador, despues expira
                $params["path"],
                $params["domain"],
                $params["secure"],
                $params["httponly"]
            );
        }

        session_destroy();
    }
}
