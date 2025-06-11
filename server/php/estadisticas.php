<?php
header('Content-Type: application/json; charset=utf-8');
require 'cors.php';
require_once 'conexion.php';
$pdo = getPDO();

try {
    // 🗓️ Diarias: fecha, tipo menú, cantidad
    $sqlDaily = "
        SELECT 
            DATE(fecha_escaneo) AS fecha,
            CASE
              WHEN HOUR(fecha_escaneo) BETWEEN 6 AND 9 THEN 'Desayuno'
              WHEN HOUR(fecha_escaneo) BETWEEN 11 AND 14 THEN 'Almuerzo'
              WHEN HOUR(fecha_escaneo) BETWEEN 15 AND 17 THEN 'Refrigerio'
            END AS tipomenu,
            SUM(CAST(TRIM(SUBSTRING_INDEX(SUBSTRING_INDEX(qr_code, 'Estudiantes presentes: ', -1), '\n', 1)) AS UNSIGNED)) AS cantidad
        FROM qrescaneados
        WHERE qr_code LIKE '%Estudiantes presentes:%'
          AND (
            HOUR(fecha_escaneo) BETWEEN 6 AND 9 OR
            HOUR(fecha_escaneo) BETWEEN 11 AND 14 OR
            HOUR(fecha_escaneo) BETWEEN 15 AND 17
          )
        GROUP BY fecha, tipomenu
        ORDER BY fecha, tipomenu;
    ";
    $dailyData = $pdo->query($sqlDaily)->fetchAll(PDO::FETCH_ASSOC);

    // 📈 Semanales: semana (como "Año-Semana"), tipo menú, cantidad
    $sqlWeekly = "
        SELECT 
            CONCAT(YEAR(fecha_escaneo), '-W', LPAD(WEEK(fecha_escaneo, 1), 2, '0')) AS fecha,
            CASE
              WHEN HOUR(fecha_escaneo) BETWEEN 6 AND 9 THEN 'Desayuno'
              WHEN HOUR(fecha_escaneo) BETWEEN 11 AND 14 THEN 'Almuerzo'
              WHEN HOUR(fecha_escaneo) BETWEEN 15 AND 17 THEN 'Refrigerio'
            END AS tipomenu,
            SUM(CAST(TRIM(SUBSTRING_INDEX(SUBSTRING_INDEX(qr_code, 'Estudiantes presentes: ', -1), '\n', 1)) AS UNSIGNED)) AS cantidad
        FROM qrescaneados
        WHERE qr_code LIKE '%Estudiantes presentes:%'
          AND DAYOFWEEK(fecha_escaneo) BETWEEN 2 AND 6
          AND (
            HOUR(fecha_escaneo) BETWEEN 6 AND 9 OR
            HOUR(fecha_escaneo) BETWEEN 11 AND 14 OR
            HOUR(fecha_escaneo) BETWEEN 15 AND 17
          )
        GROUP BY fecha, tipomenu
        ORDER BY fecha, tipomenu;
    ";
    $weeklyData = $pdo->query($sqlWeekly)->fetchAll(PDO::FETCH_ASSOC);

    // 📅 Mensuales: mes (como "Año-Mes"), tipo menú, cantidad
    $sqlMonthly = "
        SELECT 
            DATE_FORMAT(fecha_escaneo, '%Y-%m') AS fecha,
            CASE
              WHEN HOUR(fecha_escaneo) BETWEEN 6 AND 9 THEN 'Desayuno'
              WHEN HOUR(fecha_escaneo) BETWEEN 11 AND 14 THEN 'Almuerzo'
              WHEN HOUR(fecha_escaneo) BETWEEN 15 AND 17 THEN 'Refrigerio'
            END AS tipomenu,
            SUM(CAST(TRIM(SUBSTRING_INDEX(SUBSTRING_INDEX(qr_code, 'Estudiantes presentes: ', -1), '\n', 1)) AS UNSIGNED)) AS cantidad
        FROM qrescaneados
        WHERE qr_code LIKE '%Estudiantes presentes:%'
          AND (
            HOUR(fecha_escaneo) BETWEEN 6 AND 9 OR
            HOUR(fecha_escaneo) BETWEEN 11 AND 14 OR
            HOUR(fecha_escaneo) BETWEEN 15 AND 17
          )
        GROUP BY fecha, tipomenu
        ORDER BY fecha, tipomenu;
    ";
    $monthlyData = $pdo->query($sqlMonthly)->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        'diario'   => $dailyData,
        'semanal'  => $weeklyData,
        'mensual'  => $monthlyData,
    ]);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Error en la base de datos: ' . $e->getMessage()]);
}
