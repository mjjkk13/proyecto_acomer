<?php
/**
 * @OA\Post(
 *     path="/importar-estudiantes",
 *     tags={"Estudiantes"},
 *     summary="Importar estudiantes desde un archivo Excel",
 *     description="Carga un archivo Excel con nombres y apellidos de estudiantes para registrarlos en un curso específico.",
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\MediaType(
 *             mediaType="multipart/form-data",
 *             @OA\Schema(
 *                 type="object",
 *                 required={"file", "courseId"},
 *                 @OA\Property(
 *                     property="file",
 *                     type="string",
 *                     format="binary",
 *                     description="Archivo Excel (.xlsx) que contiene los estudiantes a importar."
 *                 ),
 *                 @OA\Property(
 *                     property="courseId",
 *                     type="integer",
 *                     description="ID del curso al cual se asignarán los estudiantes."
 *                 )
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Estudiantes importados correctamente",
 *         @OA\JsonContent(
 *             @OA\Property(
 *                 property="success",
 *                 type="string",
 *                 example="Estudiantes importados correctamente"
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Faltan parámetros o archivo",
 *         @OA\JsonContent(
 *             @OA\Property(
 *                 property="error",
 *                 type="string",
 *                 example="Faltan parámetros o archivo"
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Error al procesar el archivo",
 *         @OA\JsonContent(
 *             @OA\Property(
 *                 property="error",
 *                 type="string",
 *                 example="Error al procesar el archivo: mensaje de error"
 *             )
 *         )
 *     )
 * )
 */

require 'cors.php';
require 'vendor/autoload.php';
require 'conexion.php';

use PhpOffice\PhpSpreadsheet\IOFactory;

$pdo = getPDO();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['file']) && isset($_POST['courseId'])) {
    $courseId = intval($_POST['courseId']);
    $file = $_FILES['file']['tmp_name'];

    try {
        $spreadsheet = IOFactory::load($file);
        $worksheet = $spreadsheet->getActiveSheet();
        $rows = $worksheet->toArray();

        // Verificar si hay al menos una fila con datos válidos (sin contar encabezado)
        $hasData = false;
        foreach ($rows as $index => $row) {
            if ($index === 0) continue; // Saltar encabezados
            $nombre = isset($row[0]) ? trim($row[0]) : null;
            $apellido = isset($row[1]) ? trim($row[1]) : null;
            if (!empty($nombre) && !empty($apellido)) {
                $hasData = true;
                break;
            }
        }

        if (!$hasData) {
            echo json_encode(["error" => "El archivo Excel está vacío o no contiene datos válidos."]);
            exit;
        }

        $stmt = $pdo->prepare("INSERT INTO alumnos (nombre, apellido, curso_id) VALUES (?, ?, ?)");

        foreach ($rows as $index => $row) {
            if ($index === 0) continue;

            $nombre = isset($row[0]) ? trim($row[0]) : null;
            $apellido = isset($row[1]) ? trim($row[1]) : null;

            if (empty($nombre) || empty($apellido)) {
                error_log("Fila $index con datos vacíos: " . json_encode($row));
                continue;
            }

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
