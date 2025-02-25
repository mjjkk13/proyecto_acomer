<?php
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: http://localhost:5173');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');
header('Access-Control-Allow-Credentials: true');

require_once 'conexion.php';

try {
    // Consulta semanal
    $sqlWeekly = 'SELECT WEEK(fecha, 1) AS semana, 
                         MONTHNAME(MIN(fecha)) AS mes, 
                         SUM(estudiantesqasistieron) AS totalEstudiantes 
                  FROM estadisticasqr 
                  GROUP BY WEEK(fecha, 1) 
                  ORDER BY MIN(fecha) ASC';
    $stmtWeekly = $pdo->prepare($sqlWeekly);
    $stmtWeekly->execute();
    $weeklyData = $stmtWeekly->fetchAll(PDO::FETCH_ASSOC);

    // Consulta mensual
    $sqlMonthly = 'SELECT MONTH(fecha) AS mes, 
                          MONTHNAME(MIN(fecha)) AS nombre_mes, 
                          SUM(estudiantesqasistieron) AS totalEstudiantes 
                   FROM estadisticasqr 
                   GROUP BY MONTH(fecha) 
                   ORDER BY mes ASC';
    $stmtMonthly = $pdo->prepare($sqlMonthly);
    $stmtMonthly->execute();
    $monthlyData = $stmtMonthly->fetchAll(PDO::FETCH_ASSOC);

    // Enviar datos al cliente
    $data = [
        'weekly' => $weeklyData,
        'monthly' => $monthlyData
    ];
    echo json_encode($data);

} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>
