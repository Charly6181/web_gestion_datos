<?php

declare(strict_types=1);

require_once __DIR__ . '/../repositories/DepositoCorteRepository.php';

class DepositoCorteController
{
    private DepositoCorteRepository $repository;

    public function __construct()
    {
        $this->repository = new DepositoCorteRepository();
    }

    public function obtenerLectura(string $fecha): array
    {
        // Validar si la sesión de usuario o cliente está activa
        if (!isset($_SESSION['usuario_id']) && !isset($_SESSION['cliente_id'])) {
            return [
                'exito' => false,
                'mensaje' => 'No autorizado. Inicie sesión para continuar.'
            ];
        }

        // Si no viene fecha, asigna por defecto hoy
        $fechaFiltro = !empty($fecha) ? $fecha : date('Y-m-d');

        try {
            $datos = "";//$this->repository->obtenerLecturaPorFecha($fechaFiltro);

            return [
                'exito' => true,
                'fecha' => $fechaFiltro,
                'datos' => $datos
            ];
        } catch (\Throwable $e) {
            return [
                'exito' => false,
                'mensaje' => 'Error al ejecutar la consulta: ' . $e->getMessage()
            ];
        }
    }
}