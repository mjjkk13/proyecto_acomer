<?php
// controllers/EstadisticasController.php

require_once '../models/EstadisticasModel.php';

class EstadisticasController {
    private $model;

    public function __construct() {
        $this->model = new EstadisticasModel();
    }

    public function getEstadisticas() {
        $dailyData = $this->model->getDailyData();
        $weeklyData = $this->model->getWeeklyData();

        $data = [
            'daily' => $dailyData,
            'weekly' => $weeklyData
        ];

        return $data;
    }
}

// Crear una instancia del controlador y obtener estadísticas
$controller = new EstadisticasController();
$estadisticas = $controller->getEstadisticas();

// Establecer encabezado para JSON
header('Content-Type: application/json');
echo json_encode($estadisticas);
?>
