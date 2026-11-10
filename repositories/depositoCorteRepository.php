<?php

declare(strict_types=1);

require_once __DIR__ . '/../conecction/database.php';

class DepositoCorteRepository
{
    private mysqli $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }
/*
    public function obtenerLecturaPorFecha(string $fechaFiltro): array
    {
  
  
      $depositos = [];
        $sql = 'CALL olibest.depositoCorteLectura(?)';

        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('s', $fechaFiltro);
        $stmt->execute();

        $resultado = $stmt->get_result();

        if ($resultado) {
            while ($fila = $resultado->fetch_assoc()) {
                $depositos[] = $fila;
            }
            $resultado->free();
        }

        $stmt->close();
        // limpia el canal para poder hacer una nueva busqueda sin que de error
        while ($this->db->more_results() && $this->db->next_result()) {
            if ($extra = $this->db->use_result()) {
                $extra->free();
            }
        }
/*
        return $depositos;
    }
        */
}
