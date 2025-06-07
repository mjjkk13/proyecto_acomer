<?php
require 'vendor/autoload.php';
require_once 'conexion.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

// Conexión a la base de datos
$pdo = getPDO();

try {
    // Consultas (idénticas a tu API)
    $sqlDaily = "
        SELECT 
            DATE(fecha_escaneo) AS fecha,
            SUM(CASE WHEN TIME(fecha_escaneo) BETWEEN '07:00:00' AND '09:00:00' 
                THEN CAST(SUBSTRING_INDEX(SUBSTRING_INDEX(qr_code, 'Estudiantes presentes: ', -1), '\n', 1) AS UNSIGNED)
                ELSE 0 END) AS desayuno,
            SUM(CASE WHEN TIME(fecha_escaneo) BETWEEN '11:30:00' AND '13:00:00' 
                THEN CAST(SUBSTRING_INDEX(SUBSTRING_INDEX(qr_code, 'Estudiantes presentes: ', -1), '\n', 1) AS UNSIGNED)
                ELSE 0 END) AS almuerzo,
            SUM(CASE WHEN (TIME(fecha_escaneo) NOT BETWEEN '07:00:00' AND '09:00:00') 
                      AND (TIME(fecha_escaneo) NOT BETWEEN '11:30:00' AND '13:00:00')
                      AND TIME(fecha_escaneo) <= '13:00:00'
                THEN CAST(SUBSTRING_INDEX(SUBSTRING_INDEX(qr_code, 'Estudiantes presentes: ', -1), '\n', 1) AS UNSIGNED)
                ELSE 0 END) AS refrigerio,
            SUM(CAST(SUBSTRING_INDEX(SUBSTRING_INDEX(qr_code, 'Estudiantes presentes: ', -1), '\n', 1) AS UNSIGNED)) AS totalEstudiantes
        FROM qrescaneados
        WHERE TIME(fecha_escaneo) <= '13:00:00'
        GROUP BY DATE(fecha_escaneo)
        ORDER BY fecha ASC
    ";

    $sqlWeekly = "
        SELECT 
            WEEK(fecha_escaneo, 1) AS semana,
            MONTHNAME(MIN(fecha_escaneo)) AS mes,
            SUM(CASE WHEN TIME(fecha_escaneo) BETWEEN '07:00:00' AND '09:00:00' 
                THEN CAST(SUBSTRING_INDEX(SUBSTRING_INDEX(qr_code, 'Estudiantes presentes: ', -1), '\n', 1) AS UNSIGNED)
                ELSE 0 END) AS desayuno,
            SUM(CASE WHEN TIME(fecha_escaneo) BETWEEN '11:30:00' AND '13:00:00' 
                THEN CAST(SUBSTRING_INDEX(SUBSTRING_INDEX(qr_code, 'Estudiantes presentes: ', -1), '\n', 1) AS UNSIGNED)
                ELSE 0 END) AS almuerzo,
            SUM(CASE WHEN (TIME(fecha_escaneo) NOT BETWEEN '07:00:00' AND '09:00:00') 
                      AND (TIME(fecha_escaneo) NOT BETWEEN '11:30:00' AND '13:00:00')
                      AND TIME(fecha_escaneo) <= '13:00:00'
                THEN CAST(SUBSTRING_INDEX(SUBSTRING_INDEX(qr_code, 'Estudiantes presentes: ', -1), '\n', 1) AS UNSIGNED)
                ELSE 0 END) AS refrigerio,
            SUM(CAST(SUBSTRING_INDEX(SUBSTRING_INDEX(qr_code, 'Estudiantes presentes: ', -1), '\n', 1) AS UNSIGNED)) AS totalEstudiantes
        FROM qrescaneados
        WHERE TIME(fecha_escaneo) <= '13:00:00'
        GROUP BY WEEK(fecha_escaneo, 1)
        ORDER BY MIN(fecha_escaneo) ASC
    ";

    $sqlMonthly = "
        SELECT 
            MONTH(fecha_escaneo) AS mes,
            MONTHNAME(MIN(fecha_escaneo)) AS nombre_mes,
            SUM(CASE WHEN TIME(fecha_escaneo) BETWEEN '07:00:00' AND '09:00:00' 
                THEN CAST(SUBSTRING_INDEX(SUBSTRING_INDEX(qr_code, 'Estudiantes presentes: ', -1), '\n', 1) AS UNSIGNED)
                ELSE 0 END) AS desayuno,
            SUM(CASE WHEN TIME(fecha_escaneo) BETWEEN '11:30:00' AND '13:00:00' 
                THEN CAST(SUBSTRING_INDEX(SUBSTRING_INDEX(qr_code, 'Estudiantes presentes: ', -1), '\n', 1) AS UNSIGNED)
                ELSE 0 END) AS almuerzo,
            SUM(CASE WHEN (TIME(fecha_escaneo) NOT BETWEEN '07:00:00' AND '09:00:00') 
                      AND (TIME(fecha_escaneo) NOT BETWEEN '11:30:00' AND '13:00:00')
                      AND TIME(fecha_escaneo) <= '13:00:00'
                THEN CAST(SUBSTRING_INDEX(SUBSTRING_INDEX(qr_code, 'Estudiantes presentes: ', -1), '\n', 1) AS UNSIGNED)
                ELSE 0 END) AS refrigerio,
            SUM(CAST(SUBSTRING_INDEX(SUBSTRING_INDEX(qr_code, 'Estudiantes presentes: ', -1), '\n', 1) AS UNSIGNED)) AS totalEstudiantes
        FROM qrescaneados
        WHERE TIME(fecha_escaneo) <= '13:00:00'
        GROUP BY MONTH(fecha_escaneo)
        ORDER BY mes ASC
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
