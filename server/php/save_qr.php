<?php
session_start();
require_once 'conexion.php';

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: http://localhost:5173');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');
header('Access-Control-Allow-Credentials: true');

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

    // Registrar estadística (formato correcto para campo tipo DATE)
    $fecha_actual = date('Y-m-d');
    $sqlEstadisticas = "INSERT INTO estadisticasqr 
                       (fecha, estudiantes_q_asistieron) 
                       VALUES (:fecha, :cantidad)";
    $stmtEstadisticas = $pdo->prepare($sqlEstadisticas);
    $stmtEstadisticas->execute([
        ':fecha' => $fecha_actual,
        ':cantidad' => $cantidad_estudiantes
    ]);

    /*
    // OPCIONAL: Evitar duplicados por fecha en estadisticasqr
    $checkSql = "SELECT COUNT(*) FROM estadisticasqr WHERE fecha = :fecha";
    $checkStmt = $pdo->prepare($checkSql);
    $checkStmt->execute([':fecha' => $fecha_actual]);
    $existe = $checkStmt->fetchColumn();

    if ($existe == 0) {
        $sqlEstadisticas = "INSERT INTO estadisticasqr 
                            (fecha, estudiantes_q_asistieron) 
                            VALUES (:fecha, :cantidad)";
        $stmtEstadisticas = $pdo->prepare($sqlEstadisticas);
        $stmtEstadisticas->execute([
            ':fecha' => $fecha_actual,
            ':cantidad' => $cantidad_estudiantes
        ]);
    }
    */

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
