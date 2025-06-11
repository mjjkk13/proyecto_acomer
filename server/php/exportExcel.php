<?php
require 'vendor/autoload.php';
require 'cors.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

// Obtenemos los datos desde el endpoint remoto
$apiUrl = 'https://backend-acomer.onrender.com/estadisticas.php';

// Puedes usar file_get_contents si allow_url_fopen está habilitado
$apiResponse = file_get_contents($apiUrl);
if ($apiResponse === false) {
    die("Error al obtener los datos desde la API");
}

$data = json_decode($apiResponse, true);

// Validamos que los datos llegaron correctamente
if (!isset($data['diario']) || !isset($data['semanal']) || !isset($data['mensual'])) {
    die("Formato de datos incorrecto desde la API");
}

// Procesamos cada conjunto de datos
$daily = agruparPorFecha($data['diario']);
$weekly = agruparPorFecha($data['semanal']);
$monthly = agruparPorFecha($data['mensual']);

// Función para agrupar los datos como tabla pivot
function agruparPorFecha($dataset) {
    $result = [];
    foreach ($dataset as $item) {
        $fecha = $item['fecha'];
        $tipomenu = $item['tipomenu'];
        $cantidad = (int)$item['cantidad'];

        if (!isset($result[$fecha])) {
            $result[$fecha] = [
                'fecha' => $fecha,
                'desayuno' => 0,
                'almuerzo' => 0,
                'refrigerio' => 0,
                'totalEstudiantes' => 0
            ];
        }

        if ($tipomenu == 'Desayuno') $result[$fecha]['desayuno'] = $cantidad;
        if ($tipomenu == 'Almuerzo')  $result[$fecha]['almuerzo'] = $cantidad;
        if ($tipomenu == 'Refrigerio') $result[$fecha]['refrigerio'] = $cantidad;

        $result[$fecha]['totalEstudiantes'] =
            $result[$fecha]['desayuno'] +
            $result[$fecha]['almuerzo'] +
            $result[$fecha]['refrigerio'];
    }
    return array_values($result);
}

// Creamos el Excel
$spreadsheet = new Spreadsheet();

// Hoja Diario
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

// Hoja Semanal
$weeklySheet = $spreadsheet->createSheet();
$weeklySheet->setTitle('Semanal');
$weeklySheet->fromArray(['Semana', 'Desayuno', 'Almuerzo', 'Refrigerio', 'Total Estudiantes'], null, 'A1');
$row = 2;
foreach ($weekly as $item) {
    $weeklySheet->fromArray([
        $item['fecha'], $item['desayuno'], $item['almuerzo'], $item['refrigerio'], $item['totalEstudiantes']
    ], null, "A{$row}");
    $row++;
}

// Hoja Mensual
$monthlySheet = $spreadsheet->createSheet();
$monthlySheet->setTitle('Mensual');
$monthlySheet->fromArray(['Mes', 'Desayuno', 'Almuerzo', 'Refrigerio', 'Total Estudiantes'], null, 'A1');
$row = 2;
foreach ($monthly as $item) {
    $monthlySheet->fromArray([
        $item['fecha'], $item['desayuno'], $item['almuerzo'], $item['refrigerio'], $item['totalEstudiantes']
    ], null, "A{$row}");
    $row++;
}

// Limpiamos el buffer para evitar que se corrompa el archivo
if (ob_get_length()) ob_end_clean();

// Enviamos el archivo al navegador
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="estadisticas_qr.xlsx"');
header('Cache-Control: max-age=0');

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit;
?>
