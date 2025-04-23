<?php
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: http://localhost:5173');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');
header('Access-Control-Allow-Credentials: true');

require_once 'conexion.php';

try {
    // Estadísticas diarias
    $sqlDaily = "
        SELECT 
            DATE(fecha_escaneo) AS fecha,
            SUM(CAST(
                SUBSTRING_INDEX(
                    SUBSTRING_INDEX(qr_code, 'Estudiantes presentes: ', -1), 
                '\n', 1
            ) AS UNSIGNED)) AS totalEstudiantes
        FROM qrescaneados
        GROUP BY DATE(fecha_escaneo)
        ORDER BY fecha ASC
    ";
    $stmtDaily = $pdo->prepare($sqlDaily);
    $stmtDaily->execute();
    $dailyData = $stmtDaily->fetchAll(PDO::FETCH_ASSOC);

    // Estadísticas semanales
    $sqlWeekly = "
        SELECT 
            WEEK(fecha_escaneo, 1) AS semana,
            MONTHNAME(MIN(fecha_escaneo)) AS mes,
            SUM(CAST(
                SUBSTRING_INDEX(
                    SUBSTRING_INDEX(qr_code, 'Estudiantes presentes: ', -1), 
                '\n', 1
            ) AS UNSIGNED)) AS totalEstudiantes
        FROM qrescaneados
        GROUP BY WEEK(fecha_escaneo, 1)
        ORDER BY MIN(fecha_escaneo) ASC
    ";
    $stmtWeekly = $pdo->prepare($sqlWeekly);
    $stmtWeekly->execute();
    $weeklyData = $stmtWeekly->fetchAll(PDO::FETCH_ASSOC);

    // Estadísticas mensuales
    $sqlMonthly = "
        SELECT 
            MONTH(fecha_escaneo) AS mes,
            MONTHNAME(MIN(fecha_escaneo)) AS nombre_mes,
            SUM(CAST(
                SUBSTRING_INDEX(
                    SUBSTRING_INDEX(qr_code, 'Estudiantes presentes: ', -1), 
                '\n', 1
            ) AS UNSIGNED)) AS totalEstudiantes
        FROM qrescaneados
        GROUP BY MONTH(fecha_escaneo)
        ORDER BY mes ASC
    ";
    $stmtMonthly = $pdo->prepare($sqlMonthly);
    $stmtMonthly->execute();
    $monthlyData = $stmtMonthly->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        'daily' => $dailyData,
        'weekly' => $weeklyData,
        'monthly' => $monthlyData
    ]);

} catch (PDOException $e) {
    echo json_encode([
        'error' => 'Error en la base de datos: ' . $e->getMessage()
    ]);
}
