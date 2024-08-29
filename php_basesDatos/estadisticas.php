<?php
include 'conexion.php';

try {
    // Consulta para los datos diarios (estudiantes por día), ordenados del más antiguo al más reciente
    $sqlDaily = 'SELECT fecha, estudiantesqasistieron 
                 FROM estadisticasqr 
                 ORDER BY fecha ASC';
    $stmtDaily = $pdo->prepare($sqlDaily);
    $stmtDaily->execute();
    $dailyData = $stmtDaily->fetchAll(PDO::FETCH_ASSOC);

    // Consulta para los datos semanales (total de estudiantes por semana), ordenados del más antiguo al más reciente
    $sqlWeekly = 'SELECT WEEK(fecha) as semana, SUM(estudiantesqasistieron) as totalEstudiantes 
                  FROM estadisticasqr 
                  GROUP BY semana 
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
