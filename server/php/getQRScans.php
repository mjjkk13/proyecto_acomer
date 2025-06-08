<?php
/**
 * @OA\Get(
 *     path="/obtener-qr-codes",
 *     summary="Obtener los códigos QR escaneados",
 *     description="Este endpoint devuelve los códigos QR escaneados con la información del curso y la cantidad de estudiantes presentes.",
 *     tags={"QR Codes"},
 *     @OA\Response(
 *         response=200,
 *         description="Códigos QR obtenidos correctamente",
 *         @OA\JsonContent(
 *             type="array",
 *             @OA\Items(
 *                 type="object",
 *                 @OA\Property(property="curso", type="string", example="Curso 101"),
 *                 @OA\Property(property="cantidad", type="integer", example=30),
 *                 @OA\Property(property="fecha", type="string", format="date-time", example="2025-04-24 12:34:56")
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Error en la consulta a la base de datos",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="error", type="string", example="Error en la consulta: [mensaje de error]")
 *         )
 *     )
 * )
 */
require_once 'conexion.php';
require 'cors.php';

// Establecer zona horaria de Bogotá
date_default_timezone_set('America/Bogota');

$pdo = getPDO(); 
try {
    $sql = "SELECT fecha_escaneo, qr_code FROM qrescaneados ORDER BY fecha_escaneo DESC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();

    $resultados = [];

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $qr = $row['qr_code'];
        $fecha = $row['fecha_escaneo'];

        if (empty($qr)) continue;

        // Extraer el nombre del curso
        preg_match('/Curso:\s*(Curso\s*\d+)/i', $qr, $matchCurso);
        // Extraer la cantidad de estudiantes presentes
        preg_match('/Estudiantes presentes:\s*(\d+)/i', $qr, $matchCantidad);

        $curso = isset($matchCurso[1]) ? trim($matchCurso[1]) : 'Curso desconocido';
        $cantidad = isset($matchCantidad[1]) ? (int)$matchCantidad[1] : 0;

        $resultados[] = [
            'curso' => $curso,
            'cantidad' => $cantidad,
            'fecha' => date("Y-m-d H:i:s", strtotime($fecha)) // Formateado con zona horaria de Bogotá
        ];
    }

    echo json_encode($resultados);
} catch (PDOException $e) {
    echo json_encode(['error' => 'Error en la consulta: ' . $e->getMessage()]);
}
?>
