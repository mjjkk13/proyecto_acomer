<?php
// Establecer cabeceras para permitir peticiones desde frontend con CORS
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: http://localhost:5173');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');
header('Access-Control-Allow-Credentials: true');

// Incluir archivo de conexión a la base de datos
require_once 'conexion.php';

try {
    // Consulta para obtener datos agrupados por semana
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

    // Consulta para obtener datos agrupados por mes
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

    // Estructura final para enviar al cliente
    $data = [
        'weekly' => $weeklyData,
        'monthly' => $monthlyData
    ];

    echo json_encode($data);

} catch (PDOException $e) {
    // Enviar mensaje de error si ocurre una excepción de base de datos
    echo json_encode([
        'error' => 'Error en la base de datos: ' . $e->getMessage()
    ]);
}
?>
