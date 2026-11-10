<?php

declare(strict_types=1);

class Usuario
{
    private $nombre;
    private $email;
    private $passwordHash;
    private $id;
    private $activo;

    public function __construct(
        string $nombre = '',
        string $email = '',
        string $passwordHash = '',
        bool $activo = true,
        ?int $id = null
    ) {
        $this->nombre = $nombre;
        $this->email = $email;
        $this->passwordHash = $passwordHash;
        $this->activo = $activo;
        $this->id = $id;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNombre(): string
    {
        return $this->nombre;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getPasswordHash(): string
    {
        return $this->passwordHash;
    }

    public function getActivo(): bool
    {
        return (bool)$this->activo;
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

    //HASHEO DE CONTRASEÑA

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

    /*  public function __toString(): string
    {
        return sprintf(
            "Usuario [ID: %s, Nombre: '%s', Email: '%s', Activo: %s, PasswordHash: '%s']",
            $this->id ?? 'N/A',
            $this->nombre,
            $this->email,
            $this->activo ? 'Sí' : 'No',
            $this->passwordHash ? '(Hasheada)' : '(Vacía)'
        );
    }

    public function equals(?self $otroUsuario): bool
    {
        // Validar si el objeto a comparar es nulo
        if ($otroUsuario === null) {
            return false;
        }

        // Si alguno de los dos IDs es nulo, comparamos si son exactamente la misma instancia en memoria
        if ($this->id === null || $otroUsuario->getId() === null) {
            return $this === $otroUsuario;
        }

        // Comparación principal basada estrictamente en el ID
        return $this->id === $otroUsuario->getId();
    } */
}
