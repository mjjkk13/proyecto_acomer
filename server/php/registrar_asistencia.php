<?php
require 'cors.php';

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-cache, must-revalidate');

require_once 'conexion.php';
require_once __DIR__ . '/phpqrcode/qrlib.php';

session_start();

$pdo = getPDO(); 
date_default_timezone_set('America/Bogota');

if (!isset($_SESSION['idusuarios'])) {
    echo json_encode(['status' => 'error', 'message' => 'No ha iniciado sesión']);
    exit;
}

$usuario_id = $_SESSION['idusuarios'];

try {
    // Obtener ID del docente
    $stmtDocente = $pdo->prepare("SELECT iddocente FROM docente WHERE usuario_id = :usuario_id");
    $stmtDocente->bindParam(':usuario_id', $usuario_id, PDO::PARAM_INT);
    $stmtDocente->execute();
    $docente = $stmtDocente->fetch();

    if (!$docente) {
        echo json_encode(['status' => 'error', 'message' => 'No se encontró un docente relacionado con este usuario.']);
        exit;
    }

    $docente_id = $docente['iddocente'];

    // Obtener y validar los datos JSON
    $data = json_decode(file_get_contents('php://input'), true);

    if (!$data || !isset($data['idcursos']) || !isset($data['asistencias'])) {
        echo json_encode(['status' => 'error', 'message' => 'Datos incompletos o inválidos.']);
        exit;
    }

    $fecha = date('Y-m-d');
    $curso_id = $data['idcursos'];
    $asistencias = $data['asistencias'];

    if (!is_array($asistencias) || empty($asistencias)) {
        echo json_encode(['status' => 'error', 'message' => 'No hay asistencias para registrar.']);
        exit;
    }

    // Verificar que el curso pertenece al docente
    $stmtVerificar = $pdo->prepare("
        SELECT nombrecurso FROM cursos 
        WHERE docente_id = :docente_id AND idcursos = :idcursos LIMIT 1
    ");
    $stmtVerificar->execute([
        ':docente_id' => $docente_id,
        ':idcursos' => $curso_id
    ]);
    $curso = $stmtVerificar->fetch();

    if (!$curso) {
        echo json_encode(['status' => 'error', 'message' => 'El curso no está asignado al docente.']);
        exit;
    }

    $nombreCurso = $curso['nombrecurso'];

    // Generar QR
    $asistenciasPresentes = array_filter($asistencias, fn($a) => isset($a['estado']) && $a['estado'] == 1);
    $cantidadPresentes = count($asistenciasPresentes);

    $qrData = "Curso: $nombreCurso\nEstudiantes presentes: $cantidadPresentes\nFecha: $fecha";

    // Generar imagen QR en memoria y convertir a base64
    ob_start();
    QRcode::png($qrData, null);
    $qrImageData = ob_get_clean();
    $base64QR = base64_encode($qrImageData);
    $dataURL = 'data:image/png;base64,' . $base64QR;

    // Registrar QR en base de datos
    $stmtQR = $pdo->prepare("INSERT INTO qrgenerados (codigoqr, fechageneracion) VALUES (:codigoqr, :fechageneracion)");
    $stmtQR->execute([
        ':codigoqr' => $dataURL,
        ':fechageneracion' => date('Y-m-d H:i:s')
    ]);
    $qrgenerados_id = $pdo->lastInsertId();

    // Insertar asistencias
    $stmtAsistencia = $pdo->prepare("
        INSERT INTO asistencia (fecha, estado, qrgenerados_id, docente_id, alumno_id) 
        VALUES (:fecha, :estado, :qrgenerados_id, :docente_id, :alumno_id)
    ");

    foreach ($asistencias as $asistencia) {
        $alumno_id = $asistencia['alumno_id'] ?? null;
        $estado = $asistencia['estado'] ?? false;

        if ($alumno_id !== null) {
            $stmtAsistencia->execute([
                ':fecha' => $fecha,
                ':estado' => $estado ? 1 : 0,
                ':qrgenerados_id' => $qrgenerados_id,
                ':docente_id' => $docente_id,
                ':alumno_id' => $alumno_id
            ]);
        }
    }

    echo json_encode([
        'status' => 'success',
        'message' => 'Asistencia registrada correctamente',
        'qr_base64' => $dataURL
    ]);

} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode([
        'status' => 'error',
        'message' => 'Ocurrió un error al procesar la solicitud.',
        'error_detail' => $e->getMessage()
    ]);
    exit;
}
?>
