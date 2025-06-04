<?php
require 'conexion.php';
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$pdo = getPDO();

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}

// Evita que se envíe contenido antes de los headers
ob_clean();
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
header("Content-Disposition: attachment;filename=\"estadisticas.xlsx\"");
header("Cache-Control: max-age=0");

try {
    $spreadsheet = new Spreadsheet();
    $spreadsheet->removeSheetByIndex(0); // Eliminar hoja por defecto

    function crearHojaEstadisticas($pdo, $spreadsheet, $nombreHoja, $query, $headers) {
        $stmt = $pdo->prepare($query);
        $stmt->execute();
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $sheet = $spreadsheet->createSheet();
        $sheet->setTitle(substr($nombreHoja, 0, 31)); // Máximo 31 caracteres

        // Encabezados
        $col = 'A';
        foreach ($headers as $header) {
            $sheet->setCellValue($col . '1', $header);
            $col++;
        }

        // Datos
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

    // Estadísticas diarias
    $queryDiario = "
        SELECT 
            DATE(fecha_escaneo) AS fecha,
            SUM(CASE WHEN TIME(fecha_escaneo) BETWEEN '07:00:00' AND '10:00:00' THEN 1 ELSE 0 END) AS desayuno,
            SUM(CASE WHEN TIME(fecha_escaneo) BETWEEN '12:00:00' AND '15:00:00' THEN 1 ELSE 0 END) AS almuerzo,
            SUM(CASE WHEN TIME(fecha_escaneo) BETWEEN '18:00:00' AND '20:00:00' THEN 1 ELSE 0 END) AS cena
        FROM escaneos
        GROUP BY DATE(fecha_escaneo)
        ORDER BY fecha ASC;
    ";

    crearHojaEstadisticas($pdo, $spreadsheet, 'Estadísticas Diarias', $queryDiario, ['Fecha', 'Desayuno', 'Almuerzo', 'Cena']);

    // Otra hoja de ejemplo (puedes agregar más con otras consultas)
    $queryTotales = "
        SELECT 
            tipo_comida,
            COUNT(*) AS total_servicios
        FROM escaneos
        GROUP BY tipo_comida;
    ";
    crearHojaEstadisticas($pdo, $spreadsheet, 'Totales por Comida', $queryTotales, ['Tipo de comida', 'Total servicios']);

    // Eliminar hoja vacía inicial si queda
    if (count($spreadsheet->getAllSheets()) > 1) {
        $spreadsheet->removeSheetByIndex(0);
    }

    // Enviar el archivo al navegador
    $writer = new Xlsx($spreadsheet);
    $writer->save('php://output');
    exit;

} catch (Exception $e) {
    http_response_code(500);
    echo "Error al generar el archivo: " . $e->getMessage();
    exit;
}
