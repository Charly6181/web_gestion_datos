<?php

declare(strict_types=1);

class DepositoCorte
{
    private int $idActual;
    private string $deposito;
    private ?string $fechaAnterior;
    private ?float $kilosAnterior;
    private string $fechaActual;
    private float $kilosActual;
    private float $diferenciaKilos;

    public function __construct(
        int $idActual,
        string $deposito,
        ?string $fechaAnterior,
        ?float $kilosAnterior,
        string $fechaActual,
        float $kilosActual,
        float $diferenciaKilos
    ) {
        $this->idActual = $idActual;
        $this->deposito = $deposito;
        $this->fechaAnterior = $fechaAnterior;
        $this->kilosAnterior = $kilosAnterior;
        $this->fechaActual = $fechaActual;
        $this->kilosActual = $kilosActual;
        $this->diferenciaKilos = $diferenciaKilos;
    }

    public function getIdActual(): int
    {
        return $this->idActual;
    }
    public function getDeposito(): string
    {
        return $this->deposito;
    }
    public function getFechaAnterior(): ?string
    {
        return $this->fechaAnterior;
    }
    public function getKilosAnterior(): ?float
    {
        return $this->kilosAnterior;
    }
    public function getFechaActual(): string
    {
        return $this->fechaActual;
    }
    public function getKilosActual(): float
    {
        return $this->kilosActual;
    }
    public function getDiferenciaKilos(): float
    {
        return $this->diferenciaKilos;
    }
}
