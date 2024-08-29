<?php
include 'conexion.php';

try {
    // Consulta para los datos diarios (estudiantes por día)
    $sqlDaily = 'SELECT fecha, estudiantesqasistieron 
                 FROM estadisticasqr 
                 ORDER BY fecha ASC';
    $stmtDaily = $pdo->prepare($sqlDaily);
    $stmtDaily->execute();
    $dailyData = $stmtDaily->fetchAll(PDO::FETCH_ASSOC);

    // Consulta para calcular estadísticas semanales cada 5 días
    $sqlWeekly = 'SELECT WEEK(fecha, 1) as semana, SUM(estudiantesqasistieron) as totalEstudiantes 
                  FROM estadisticasqr 
                  WHERE DAYOFWEEK(fecha) BETWEEN 2 AND 6
                  GROUP BY semana 
                  HAVING COUNT(fecha) = 5
                  ORDER BY MIN(fecha) ASC';
    $stmtWeekly = $pdo->prepare($sqlWeekly);
    $stmtWeekly->execute();
    $weeklyData = $stmtWeekly->fetchAll(PDO::FETCH_ASSOC);

    // Convertir los resultados a JSON
    $data = [
        'daily' => $dailyData,
        'weekly' => $weeklyData
    ];

    echo json_encode($data);
} catch (PDOException $e) {
    echo 'Error: ' . $e->getMessage();
}

?>
