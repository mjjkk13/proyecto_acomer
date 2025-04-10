<?php
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: http://localhost:5173');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');
header('Access-Control-Allow-Credentials: true');

require_once 'conexion.php';

try {
    $sqlWeekly = "
        SELECT 
            WEEK(fecha, 1) AS semana, 
            MONTHNAME(MIN(fecha)) AS mes, 
            SUM(estudiantes_q_asistieron) AS totalEstudiantes 
        FROM estadisticasqr 
        GROUP BY WEEK(fecha, 1) 
        ORDER BY MIN(fecha) ASC
    ";
    $stmtWeekly = $pdo->prepare($sqlWeekly);
    $stmtWeekly->execute();
    $weeklyData = $stmtWeekly->fetchAll(PDO::FETCH_ASSOC);

    $sqlMonthly = "
        SELECT 
            MONTH(fecha) AS mes, 
            MONTHNAME(MIN(fecha)) AS nombre_mes, 
            SUM(estudiantes_q_asistieron) AS totalEstudiantes 
        FROM estadisticasqr 
        GROUP BY MONTH(fecha) 
        ORDER BY mes ASC
    ";
    $stmtMonthly = $pdo->prepare($sqlMonthly);
    $stmtMonthly->execute();
    $monthlyData = $stmtMonthly->fetchAll(PDO::FETCH_ASSOC);

    $sqlDaily = "
        SELECT 
            DATE(fecha) AS fecha, 
            SUM(estudiantes_q_asistieron) AS totalEstudiantes 
        FROM estadisticasqr 
        GROUP BY DATE(fecha) 
        ORDER BY fecha ASC
    ";
    $stmtDaily = $pdo->prepare($sqlDaily);
    $stmtDaily->execute();
    $dailyData = $stmtDaily->fetchAll(PDO::FETCH_ASSOC);

    $data = [
        'daily' => $dailyData,
        'weekly' => $weeklyData,
        'monthly' => $monthlyData
    ];

    echo json_encode($data);

} catch (PDOException $e) {
    echo json_encode([
        'error' => 'Error en la base de datos: ' . $e->getMessage()
    ]);
}
