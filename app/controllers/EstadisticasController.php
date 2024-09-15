<?php
class EstadisticasController {
    private $estadisticasModel;

    public function __construct($estadisticasModel) {
        $this->estadisticasModel = $estadisticasModel;
    }

    public function getEstadisticas() {
        try {
            $dailyData = $this->estadisticasModel->getDailyEstadisticas();
            $weeklyData = $this->estadisticasModel->getWeeklyEstadisticas();

            $data = [
                'daily' => $dailyData,
                'weekly' => $weeklyData
            ];

            // Enviar los datos a la vista o devolver JSON
            echo json_encode($data);
        } catch (PDOException $e) {
            echo 'Error: ' . $e->getMessage();
        }
    }
}
?>