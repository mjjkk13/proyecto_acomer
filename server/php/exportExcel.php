<?php
require 'conexion.php';
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

// Configuración CORS
require 'cors.php';


if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

try {
    $spreadsheet = new Spreadsheet();
    $spreadsheet->removeSheetByIndex(0); // Elimina la hoja por defecto

    // Función para ejecutar consultas y crear hojas
    function crearHojaEstadisticas($pdo, $spreadsheet, $nombreHoja, $query, $headers) {
        $stmt = $pdo->prepare($query);
        $stmt->execute();
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $sheet = $spreadsheet->createSheet();
        $sheet->setTitle($nombreHoja);

        // Agregar encabezados
        $col = 'A';
        foreach ($headers as $header) {
            $sheet->setCellValue($col . '1', $header);
            $col++;
        }

        // Agregar datos
        $row = 2;
        foreach ($data as $fila) {
            $col = 'A';
            foreach ($fila as $valor) {
                $sheet->setCellValue($col . $row, $valor);
                $col++;
            }
            $row++;
        }
    }

    // Consulta para estadísticas diarias
    $queryDiario = "
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
            SUM(CAST(SUBSTRING_INDEX(SUBSTRING_INDEX(qr_code, 'Estudiantes presentes: ', -1), '\n', 1) AS UNSIGNED)) AS total
        FROM qrescaneados
        WHERE TIME(fecha_escaneo) <= '13:00:00'
        GROUP BY DATE(fecha_escaneo)
        ORDER BY fecha ASC
    ";

    // Consulta para estadísticas semanales
    $querySemanal = "
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
            SUM(CAST(SUBSTRING_INDEX(SUBSTRING_INDEX(qr_code, 'Estudiantes presentes: ', -1), '\n', 1) AS UNSIGNED)) AS total
        FROM qrescaneados
        WHERE TIME(fecha_escaneo) <= '13:00:00'
        GROUP BY WEEK(fecha_escaneo, 1)
        ORDER BY MIN(fecha_escaneo) ASC
    ";

    // Consulta para estadísticas mensuales
    $queryMensual = "
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
            SUM(CAST(SUBSTRING_INDEX(SUBSTRING_INDEX(qr_code, 'Estudiantes presentes: ', -1), '\n', 1) AS UNSIGNED)) AS total
        FROM qrescaneados
        WHERE TIME(fecha_escaneo) <= '13:00:00'
        GROUP BY MONTH(fecha_escaneo)
        ORDER BY mes ASC
    ";

    // Crear hojas con los datos
    crearHojaEstadisticas($pdo, $spreadsheet, 'Diario', $queryDiario, 
        ['Fecha', 'Desayuno', 'Almuerzo', 'Refrigerio', 'Total Estudiantes']);
    
    crearHojaEstadisticas($pdo, $spreadsheet, 'Semanal', $querySemanal, 
        ['Semana', 'Mes', 'Desayuno', 'Almuerzo', 'Refrigerio', 'Total Estudiantes']);
    
    crearHojaEstadisticas($pdo, $spreadsheet, 'Mensual', $queryMensual, 
        ['Mes (número)', 'Nombre Mes', 'Desayuno', 'Almuerzo', 'Refrigerio', 'Total Estudiantes']);

    // Generar el archivo Excel
    $writer = new Xlsx($spreadsheet);
    
    if (ob_get_length()) {
        ob_end_clean();
    }

    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment; filename="estadisticas_por_horario.xlsx"');
    header('Cache-Control: max-age=0');
    
    $writer->save('php://output');
    exit;

} catch (PDOException $e) {
    error_log('Error en la base de datos: ' . $e->getMessage());
    http_response_code(500);
    echo json_encode(['error' => 'Error en la base de datos: ' . $e->getMessage()]);
    exit;
} catch (Exception $e) {
    error_log('Error general: ' . $e->getMessage());
    http_response_code(500);
    echo json_encode(['error' => 'Error al generar el archivo Excel']);
    exit;
}
?>