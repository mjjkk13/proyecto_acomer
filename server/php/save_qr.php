<?php
/**
 * @OA\Info(title="API para registro de escaneo de QR", version="1.0")
 */

/**
 * @OA\Post(
 *     path="/save_qr.php",
 *     summary="Registrar un escaneo de código QR de un estudiante de servicio social",
 *     description="Este endpoint permite registrar el escaneo de un código QR y actualizar las estadísticas de asistencia.",
 *     operationId="saveQrScan",
 *     tags={"QR"},
 *     security={{"bearerAuth": {}}},
 *     @OA\Parameter(
 *         name="Authorization",
 *         in="header",
 *         required=true,
 *         description="Token Bearer de autorización en el encabezado.",
 *         @OA\Schema(type="string")
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         content={
 *             @OA\MediaType(
 *                 mediaType="application/json",
 *                 @OA\Schema(
 *                     type="object",
 *                     @OA\Property(property="qr_code", type="string", description="Código QR escaneado")
 *                 )
 *             )
 *         }
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Código QR registrado correctamente",
 *         @OA\JsonContent(
 *             @OA\Property(property="status", type="string", example="success"),
 *             @OA\Property(property="message", type="string", example="QR registrado correctamente"),
 *             @OA\Property(property="data", type="object",
 *                 @OA\Property(property="estudiantes_registrados", type="integer", example=20),
 *                 @OA\Property(property="qr_escaneado", type="string", example="QR123456789"),
 *                 @OA\Property(property="estudiante_id", type="integer", example=1)
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Solicitud incorrecta",
 *         @OA\JsonContent(
 *             @OA\Property(property="status", type="string", example="error"),
 *             @OA\Property(property="message", type="string", example="Código QR no recibido.")
 *         )
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="No autenticado",
 *         @OA\JsonContent(
 *             @OA\Property(property="status", type="string", example="error"),
 *             @OA\Property(property="message", type="string", example="No se ha iniciado sesión.")
 *         )
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Error interno del servidor",
 *         @OA\JsonContent(
 *             @OA\Property(property="status", type="string", example="error"),
 *             @OA\Property(property="message", type="string", example="Error de base de datos: SQLSTATE[42000]: Syntax error or access violation: 1064")
 *         )
 *     )
 * )
 */

session_start();
require_once 'conexion.php';

header('Content-Type: application/json; charset=utf-8');
require 'cors.php';


if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

if (!isset($_SESSION['idusuarios'])) {
    echo json_encode([
        'status' => 'error',
        'message' => 'No se ha iniciado sesión.'
    ]);
    exit;
}

$usuario_id = $_SESSION['idusuarios'];

try {
    $data = json_decode(file_get_contents('php://input'), true);
    $qr_code = trim($data['qr_code'] ?? '');

    if (empty($qr_code)) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Código QR no recibido.'
        ]);
        exit;
    }

    // Extraer cantidad de estudiantes del QR
    preg_match('/Estudiantes presentes: (\d+)/', $qr_code, $matches);
    $cantidad_estudiantes = $matches[1] ?? 0;

    // Obtener estudiante asociado
    $sqlEstudiante = "SELECT idestudiante_ss FROM estudiante_ss WHERE usuario_id = :usuario_id";
    $stmtEstudiante = $pdo->prepare($sqlEstudiante);
    $stmtEstudiante->execute([':usuario_id' => $usuario_id]);
    $estudiante = $stmtEstudiante->fetch(PDO::FETCH_ASSOC);

    if (!$estudiante) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Estudiante no encontrado.'
        ]);
        exit;
    }

    $idestudiante_ss = $estudiante['idestudiante_ss'];

    // Registrar escaneo
    $sqlInsert = "INSERT INTO qrescaneados 
                 (fecha_escaneo, estudiante_ss_id, qr_code) 
                 VALUES (NOW(), :idestudiante_ss, :qr_code)";
    $stmtInsert = $pdo->prepare($sqlInsert);
    $stmtInsert->execute([
        ':idestudiante_ss' => $idestudiante_ss,
        ':qr_code' => $qr_code
    ]);

    // Actualizar contador de QRs del estudiante
    $sqlUpdate = "UPDATE estudiante_ss 
                 SET qr_registrados = qr_registrados + 1 
                 WHERE idestudiante_ss = :idestudiante_ss";
    $stmtUpdate = $pdo->prepare($sqlUpdate);
    $stmtUpdate->execute([':idestudiante_ss' => $idestudiante_ss]);


    echo json_encode([
        'status' => 'success',
        'message' => 'QR registrado correctamente',
        'data' => [
            'estudiantes_registrados' => (int)$cantidad_estudiantes,
            'qr_escaneado' => $qr_code,
            'estudiante_id' => $idestudiante_ss
        ]
    ]);

} catch (PDOException $e) {
    error_log("Error en save_qr.php: " . $e->getMessage());
    echo json_encode([
        'status' => 'error',
        'message' => 'Error de base de datos: ' . $e->getMessage()
    ]);
} catch (Exception $e) {
    error_log("Error general en save_qr.php: " . $e->getMessage());
    echo json_encode([
        'status' => 'error',
        'message' => 'Error inesperado: ' . $e->getMessage()
    ]);
}
