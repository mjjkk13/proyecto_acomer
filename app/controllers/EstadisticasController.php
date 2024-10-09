<?php
// controllers/EstadisticasController.php

require_once 'C:/xampp/htdocs/Proyecto/models/EstadisticasModel.php';

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
?>