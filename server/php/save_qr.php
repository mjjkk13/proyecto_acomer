<?php
session_start();
require_once 'conexion.php';
$pdo = getPDO();

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

    // Verificar hora del sistema
    date_default_timezone_set('America/Bogota');
    $horaActual = date('H:i');

    $hora = strtotime($horaActual);
    $horaDesayunoInicio = strtotime('07:00');
    $horaDesayunoFin = strtotime('09:00');
    $horaAlmuerzoInicio = strtotime('11:30');
    $horaAlmuerzoFin = strtotime('13:00');
    $horaRefrigerioFin = strtotime('16:00');

    $esDesayuno = ($hora >= $horaDesayunoInicio && $hora <= $horaDesayunoFin);
    $esAlmuerzo = ($hora >= $horaAlmuerzoInicio && $hora <= $horaAlmuerzoFin);
    $esRefrigerio = (!$esDesayuno && !$esAlmuerzo && $hora <= $horaRefrigerioFin);

    if (!$esDesayuno && !$esAlmuerzo && !$esRefrigerio) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Escaneo fuera del horario permitido. Solo se puede registrar entre 7:00–9:00am (desayuno), 11:30am–1:00pm (almuerzo) o refrigerio antes de la 4:00pm.'
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
    $sqlInsert = "INSERT INTO qrescaneados (fecha_escaneo, estudiante_ss_id, qr_code) 
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
