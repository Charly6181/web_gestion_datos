<?php

declare(strict_types=1);

class Cliente
{

    private $id;
    private $usuarioId;
    private $nombre;
    private $apellidos;
    private $dni;
    private $email;
    private $passwordHash;
    private $direccion;
    private $activo;

    public function __construct(
        int $usuarioId = 0,
        string $nombre = '',
        string $apellidos = '',
        string $dni = '',
        string $email = '',
        string $passwordHash = '',
        ?string $direccion = null,
        bool $activo = false,
        ?int $id = null
    ) {
        $this->usuarioId = $usuarioId;
        $this->nombre = $nombre;
        $this->apellidos = $apellidos;
        $this->dni = $dni;
        $this->email = $email;
        $this->passwordHash = $passwordHash;
        $this->direccion = $direccion;
        $this->activo = $activo;
        $this->id = $id;
    }

    public function getUsuarioId(): int
    {
        return $this->usuarioId;
    }


    public function getNombre(): string
    {
        return $this->nombre;
    }

    public function getApellidos(): string
    {
        return $this->apellidos;
    }

    public function getDni(): string
    {
        return $this->dni;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getPasswordHash(): string
    {
        return $this->passwordHash;
    }

    public function getDireccion(): ?string
    {
        return $this->direccion;
    }

    public function getActivo(): bool
    {
        return (bool)$this->activo;
    }

    public function getId(): ?int
    {
        return $this->id;
    }



    //SETTERS

    public function setUsuarioId(int $usuarioId): self
    {
        $this->usuarioId = $usuarioId;
        return $this;
    }

    public function setId(int $id): self
    {
        $this->id = $id;
        return $this;
    }


    public function setNombre(string $nombre): self
    {
        $this->nombre = trim($nombre);
        return $this;
    }

    public function setApellidos(string $apellidos): self
    {
        $this->apellidos = trim($apellidos);
        return $this;
    }

    public function setDni(string $dni): self
    {
        $this->dni = trim($dni);
        return $this;
    }

    public function setDireccion(?string $direccion): self
    {
        $this->direccion = trim($direccion);
        return $this;
    }

    public function setActivo(bool $activo): self
    {
        $this->activo = $activo;
        return $this;
    }

    public function setEmail(string $email): self
    {
        $emailSaneado = filter_var(trim($email), FILTER_SANITIZE_EMAIL);
        if (!filter_var($emailSaneado, FILTER_SANITIZE_EMAIL)) {
            throw new InvalidArgumentException("Formato de correo inválido");
        }
        $this->email = $emailSaneado;
        return $this;
    }

    public function setPasswordPlana(string $passwordPlana): self
    {
        if (strlen($passwordPlana) < 8) {
            throw new InvalidArgumentException("La contraseña debe tener al menos 8 caracteres.");
        }
        $this->passwordHash = password_hash($passwordPlana, PASSWORD_DEFAULT);
        return $this;
    }

    public function setPasswordHash(string $hash): self
    {
        $this->passwordHash = $hash;
        return $this;
    }

    public function verificarPassword(string $passwordPlana): bool
    {
        return password_verify($passwordPlana, $this->passwordHash);
    }
}
