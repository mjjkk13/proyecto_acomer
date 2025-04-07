<?php
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: http://localhost:5173'); 
header('Access-Control-Allow-Methods: POST, GET, OPTIONS'); 
header('Access-Control-Allow-Headers: Content-Type, Authorization'); 
header('Access-Control-Allow-Credentials: true'); 

require 'vendor/autoload.php';
require 'conexion.php'; // Usa la variable $pdo para la conexión

use PhpOffice\PhpSpreadsheet\IOFactory;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['file']) && isset($_POST['courseId'])) {
    $courseId = intval($_POST['courseId']);
    $file = $_FILES['file']['tmp_name'];

    try {
        $spreadsheet = IOFactory::load($file);
        $worksheet = $spreadsheet->getActiveSheet();
        $rows = $worksheet->toArray();

        // Preparar consulta fuera del bucle para mejorar rendimiento
        $stmt = $pdo->prepare("INSERT INTO alumnos (nombre, apellido, curso_id) VALUES (?, ?, ?)");

        foreach ($rows as $index => $row) {
            if ($index === 0) continue; // Saltar encabezados
            
            $nombre = isset($row[0]) ? trim($row[0]) : null;
            $apellido = isset($row[1]) ? trim($row[1]) : null;

            // Validación de datos
            if (empty($nombre) || empty($apellido)) {
                error_log("Fila $index con datos vacíos: " . json_encode($row));
                continue;
            }

            // Ejecutar la consulta con valores correctos
            $stmt->execute([$nombre, $apellido, $courseId]);
        }

        echo json_encode(["success" => "Estudiantes importados correctamente"]);
    } catch (Exception $e) {
        echo json_encode(["error" => "Error al procesar el archivo: " . $e->getMessage()]);
    }
} else {
    echo json_encode(["error" => "Faltan parámetros o archivo"]);
}
?>
