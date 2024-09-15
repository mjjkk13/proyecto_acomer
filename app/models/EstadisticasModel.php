<?php

class EstadisticasModel {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function getDailyData() {
        $sqlDaily = 'SELECT DATE_FORMAT(fecha, "%Y-%m-%d") as fecha, 
                            DATE_FORMAT(fecha, "%W") as dia, 
                            SUM(estudiantesqasistieron) as totalEstudiantes 
                     FROM estadisticasqr 
                     GROUP BY fecha 
                     ORDER BY fecha ASC';

        $stmtDaily = $this->pdo->prepare($sqlDaily);
        $stmtDaily->execute();
        return $stmtDaily->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getWeeklyData() {
        $sqlWeekly = 'SELECT WEEK(fecha, 1) as semana, 
                            MONTHNAME(fecha) as mes, 
                            SUM(estudiantesqasistieron) as totalEstudiantes 
                      FROM estadisticasqr 
                      WHERE DAYOFWEEK(fecha) BETWEEN 2 AND 6
                      GROUP BY semana 
                      HAVING COUNT(fecha) = 5
                      ORDER BY MIN(fecha) ASC';

        $stmtWeekly = $this->pdo->prepare($sqlWeekly);
        $stmtWeekly->execute();
        return $stmtWeekly->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
