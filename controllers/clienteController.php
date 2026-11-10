<?php

declare(strict_types=1);        //logica de negocio relacionada con la gestion de usuarios

require_once __DIR__ .  '/../repositories/clienteRepository.php';

class ClienteController
{
    private ClienteRepository $repo;

    public function __construct()
    {
        $this->repo = new ClienteRepository;
    }

    public function registrarCliente(
        int $usuarioId,
        string $nombre,
        string $apellidos,
        string $dni,
        string $email,
        string $direccion,
        string $password,
        string $passwordConfirm
    ): array {

        $nombreSaneado = trim($nombre);
        $apellidosSaneados = trim($apellidos);
        $dniSaneado = trim($dni);
        $emailSaneado = filter_var(trim($email), FILTER_SANITIZE_EMAIL);
        $direccionSaneada = trim($direccion);

        // Comprobación de campos obligatorios
        if (empty($nombreSaneado) || empty($apellidosSaneados) || empty($emailSaneado) || empty($password) || empty($passwordConfirm)) {
            return [
                'exito' => false,
                'mensaje' => 'Por favor, rellene todos los campos obligatorios.'
            ];
        }

        $patronEstricto = '/\.([a-zA-Z]{2,3}|info)$/i';

        if (!filter_var($emailSaneado, FILTER_VALIDATE_EMAIL) || !preg_match($patronEstricto, $emailSaneado)) {      //Valida email para que tenga @ dominio.com .es .loquesea
            return [
                'exito' => false,
                'mensaje' => 'El formato de correo electrónico no es válido.'
            ];
        }

        if ($password !== $passwordConfirm) {
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

        if ($this->repo->buscarPorEmail($emailSaneado) !== null) {        //Verificar que no existe usuario en BBDD
            return [
                'exito' => false,
                'mensaje' => 'Ya existe un cliente registrado con este correo electrónico.'
            ];
        }

        if (!empty($dniSaneado) && method_exists($this->repo, 'buscarPorDni') && $this->repo->buscarPorDni($dniSaneado) !== null) {
            return [
                'exito' => false,
                'mensaje' => 'Ya existe un cliente registrado con este DNI / CIF.'
            ];
        }

        if (!empty($dniSaneado) && !$this->validarDniCif($dniSaneado)) {
            return ['exito' => false, 'mensaje' => 'El formato del DNI/CIF no es válido.'];
        }

        try {
            $nuevoCliente = new Cliente();
            $nuevoCliente->setUsuarioId($usuarioId);
            $nuevoCliente->setNombre($nombreSaneado);
            $nuevoCliente->setApellidos($apellidosSaneados);
            $nuevoCliente->setDni($dniSaneado);
            $nuevoCliente->setEmail($emailSaneado);
            $nuevoCliente->setDireccion($direccionSaneada);
            $nuevoCliente->setPasswordPlana($password);
            $nuevoCliente->setActivo(true);

            // Guardar en bbdd
            $guardado = $this->repo->guardar($nuevoCliente);

            if ($guardado) {
                return [
                    'exito' => true,
                    'mensaje' => 'Cliente registrado con éxito.'
                ];
            }

            return [
                'exito' => false,
                'mensaje' => 'Error: No se ha podido guardar el cliente en la BBDD.'
            ];
        } catch (\Throwable $e) {
            return [
                'exito' => false,
                'mensaje' => 'Error durante el registro del cliente: ' . $e->getMessage()
            ];
        }
    }

    // Función para validar DNI o CIF en el cliente con algoritmo matematico oficial

    /*   private function validarDniCif(string $documento): bool
    {
        $documento = strtoupper(trim($documento));

        // Regex básica para ver si tiene estructura de DNI (8 números + 1 letra) o CIF (1 letra + 7 números + 1 dígito/letra)
        $patronDni = '/^[0-9]{8}[TRWAGMYFPDXBNJZSQVHLCKE]$/i';
        $patronCif = '/^[ABCDEFGHJKLMNPQRSUVW][0-9]{7}[0-9A-J]$/';

        if (preg_match($patronDni, $documento)) {
            // Validación de la letra de control del DNI
            $letras = 'TRWAGMYFPDXBNJZSQVHLCKE';
            $numero = (int)substr($documento, 0, 8);
            $letraCorrecta = $letras[$numero % 23];
            return $documento[8] === $letraCorrecta;
        }

        if (preg_match($patronCif, $documento)) {
            // El formato del CIF coincide
            return true;
        }

        return false;
    } */

    private function validarDniCif(string $documento): bool
    {
        $documento = strtoupper(trim($documento));

        // Expresiones regulares flexibles (8 números + cualquier letra de la A a la Z, o CIF válido)
        $patronDni = '/^[0-9]{8}[A-Z]$/';
        $patronCif = '/^[ABCDEFGHJKLMNPQRSUVW][0-9]{7}[0-9A-J]$/';

        // Si coincide con DNI o CIF flexible, se acepta directamente sin cálculos matemáticos
        if (preg_match($patronDni, $documento) || preg_match($patronCif, $documento)) {
            return true;
        }

        return false;
    }

    public function obtenerClientesPorUsuario(int $usuarioId): array
    {
        return $this->repo->obtenerPorUsuarioId($usuarioId);
    }

    public function obtenerClientePorId(int $id): ?Cliente
    {
        return $this->repo->buscarPorId($id);
    }

    public function actualizarCliente(int $id, string $nombre, string $apellidos, string $dni, string $email, string $direccion): array
    {
        $nombreSaneado = trim($nombre);
        $apellidosSaneados = trim($apellidos);
        $dniSaneado = strtoupper(trim($dni));       //convierte dni a mayus
        $emailSaneado = filter_var(trim($email), FILTER_SANITIZE_EMAIL);
        $direccionSaneada = trim($direccion);

        if (empty($nombreSaneado) || empty($apellidosSaneados) || empty($emailSaneado)) {
            return ['exito' => false, 'mensaje' => 'Por favor, rellene los campos obligatorios.'];
        }

        //para verificar si el mail ya esta siendo usado
        $clienteExistente = $this->repo->buscarPorEmail($emailSaneado);
        if ($clienteExistente !== null && $clienteExistente->getId() !== $id) {
            return ['exito' => false, 'mensaje' => 'Ya existe otro cliente con este correo electrónico.'];
        }

        try {
            $cliente = $this->repo->buscarPorId($id);
            if (!$cliente) {
                return ['exito' => false, 'mensaje' => 'Cliente no encontrado.'];
            }

            $cliente->setNombre($nombreSaneado);
            $cliente->setApellidos($apellidosSaneados);
            $cliente->setDni($dniSaneado);
            $cliente->setEmail($emailSaneado);
            $cliente->setDireccion($direccionSaneada);

            $actualizado = $this->repo->actualizar($cliente);

            if ($actualizado) {
                return ['exito' => true, 'mensaje' => 'Cliente actualizado con éxito.'];
            }

            return ['exito' => false, 'mensaje' => 'No se han realizado cambios o ha ocurrido un error.'];
        } catch (\Throwable $e) {
            return ['exito' => false, 'mensaje' => 'Error al actualizar: ' . $e->getMessage()];
        }
    }
    public function eliminarCliente(int $id): array
    {
        if ($id <= 0) {
            return ['exito' => false, 'mensaje' => 'ID de cliente incorrecto.'];
        }

        try {
            $cliente = $this->repo->buscarPorId($id);
            if (!$cliente) {
                return ['exito' => false, 'mensaje' => 'Cliente no encontrado.'];
            }

            $eliminado = $this->repo->eliminar($id);

            if ($eliminado) {
                return ['exito' => true, 'mensaje' => 'Cliente eliminado con éxito.'];
            }

            return ['exito' => false, 'mensaje' => 'No se ha podido eliminar el cliente de la base de datos.'];
        } catch (\Throwable $e) {
            return ['exito' => false, 'mensaje' => 'Error al eliminar: ' . $e->getMessage()];
        }
    }

    public function procesarLoginCliente(string $email, string $password): array
    {
        $emailSaneado = filter_var(trim($email), FILTER_SANITIZE_EMAIL);

        if (empty($emailSaneado) || empty($password)) {
            return [
                'exito' => false,
                'mensaje' => 'Por favor, introduzca el correo y la contraseña.'
            ];
        }

        $cliente = $this->repo->buscarPorEmail($emailSaneado);

        if (!$cliente) {
            return [
                'exito' => false,
                'mensaje' => 'El correo electrónico o la contraseña son incorrectos.'
            ];
        }

        if (!$cliente->getActivo()) {
            return [
                'exito' => false,
                'mensaje' => 'Su cuenta se encuentra inactiva. Contacte con el administrador.'
            ];
        }

        if (!password_verify($password, $cliente->getPasswordHash())) {
            return [
                'exito' => false,
                'mensaje' => 'El correo electrónico o la contraseña son incorrectos.'
            ];
        }

        return [
            'exito' => true,
            'cliente' => $cliente
        ];
    }
}
