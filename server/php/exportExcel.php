<?php
require 'vendor/autoload.php';
require_once 'conexion.php';
require 'cors.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

// Conexión a la base de datos
$pdo = getPDO();

try {
    // Consultas (idénticas a tu API)
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

    // Ejecutar las consultas
    $daily = $pdo->query($sqlDaily)->fetchAll(PDO::FETCH_ASSOC);
    $weekly = $pdo->query($sqlWeekly)->fetchAll(PDO::FETCH_ASSOC);
    $monthly = $pdo->query($sqlMonthly)->fetchAll(PDO::FETCH_ASSOC);

    // Crear el documento
    $spreadsheet = new Spreadsheet();

    // -------------------- Hoja diaria --------------------
    $sheet = $spreadsheet->getActiveSheet();
    $sheet->setTitle('Diario');
    $sheet->fromArray(['Fecha', 'Desayuno', 'Almuerzo', 'Refrigerio', 'Total Estudiantes'], null, 'A1');

    $row = 2;
    foreach ($daily as $item) {
        $sheet->fromArray([
            $item['fecha'], $item['desayuno'], $item['almuerzo'], $item['refrigerio'], $item['totalEstudiantes']
        ], null, "A{$row}");
        $row++;
    }

    // -------------------- Hoja semanal --------------------
    $weeklySheet = $spreadsheet->createSheet();
    $weeklySheet->setTitle('Semanal');
    $weeklySheet->fromArray(['Semana', 'Mes', 'Desayuno', 'Almuerzo', 'Refrigerio', 'Total Estudiantes'], null, 'A1');

    $row = 2;
    foreach ($weekly as $item) {
        $weeklySheet->fromArray([
            $item['semana'], $item['mes'], $item['desayuno'], $item['almuerzo'], $item['refrigerio'], $item['totalEstudiantes']
        ], null, "A{$row}");
        $row++;
    }

    // -------------------- Hoja mensual --------------------
    $monthlySheet = $spreadsheet->createSheet();
    $monthlySheet->setTitle('Mensual');
    $monthlySheet->fromArray(['Mes', 'Nombre Mes', 'Desayuno', 'Almuerzo', 'Refrigerio', 'Total Estudiantes'], null, 'A1');

    $row = 2;
    foreach ($monthly as $item) {
        $monthlySheet->fromArray([
            $item['mes'], $item['nombre_mes'], $item['desayuno'], $item['almuerzo'], $item['refrigerio'], $item['totalEstudiantes']
        ], null, "A{$row}");
        $row++;
    }

    // Establecer headers y exportar
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="estadisticas_qr.xlsx"');
    header('Cache-Control: max-age=0');

    $writer = new Xlsx($spreadsheet);
    $writer->save('php://output');
    exit;

} catch (PDOException $e) {
    echo "Error al generar el Excel: " . $e->getMessage();
}
?>
