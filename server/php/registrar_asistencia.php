<?php 
header('Content-Type: application/json');
header('Cache-Control: no-cache, must-revalidate');

require_once 'conexion.php';
require_once __DIR__ . '/phpqrcode/qrlib.php';

session_start();

// Manejo de CORS
$origin = $_SERVER['HTTP_ORIGIN'] ?? '';
if ($origin === 'http://localhost:5173') {
    header("Access-Control-Allow-Origin: $origin");
    header('Access-Control-Allow-Credentials: true');
}
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');
header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Validación de sesión
if (!isset($_SESSION['idusuarios'])) {
    echo json_encode(['status' => 'error', 'message' => 'No ha iniciado sesión']);
    exit;
}

$usuario_id = $_SESSION['idusuarios'];

// Obtener iddocente desde usuario_id
try {
    $stmtDocente = $pdo->prepare("SELECT iddocente FROM docente WHERE usuario_id = :usuario_id");
    $stmtDocente->bindParam(':usuario_id', $usuario_id, PDO::PARAM_INT);
    $stmtDocente->execute();
    $docente = $stmtDocente->fetch();

    if (!$docente) {
        echo json_encode(['status' => 'error', 'message' => 'No se encontró un docente relacionado con este usuario.']);
        exit;
    }

    $docente_id = $docente['iddocente'];
} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => 'Error al obtener el docente.']);
    exit;
}

// Obtener y decodificar el JSON
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

try {
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

    // Filtrar solo asistencias con estado = 1 (presentes)
    $asistenciasPresentes = array_filter($asistencias, function ($a) {
        return isset($a['estado']) && $a['estado'] == 1;
    });

    $cantidadPresentes = count($asistenciasPresentes);

    // Generar QR solo con alumnos presentes
    $qrData = "Curso: $nombreCurso\nEstudiantes presentes: $cantidadPresentes\nFecha: $fecha";
    $qrFileName = 'qr_asistencia_' . time() . '.png';
    $qrDir = 'qrcodes/';
    $qrFilePath = $qrDir . $qrFileName;

    if (!file_exists($qrDir)) {
        mkdir($qrDir, 0777, true);
    }

    QRcode::png($qrData, $qrFilePath);

    // Guardar QR en la tabla qrgenerados
    $stmtQR = $pdo->prepare("
        INSERT INTO qrgenerados (codigoqr, fechageneracion) 
        VALUES (:filename, :fechageneracion)
    ");
    $stmtQR->execute([
        ':filename' => $qrFileName,
        ':fechageneracion' => date('Y-m-d H:i:s')
    ]);
    $qrgenerados_id = $pdo->lastInsertId();

    // Registrar asistencias
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
        'qr_image' => "http://localhost/proyecto_acomer/server/php/$qrFilePath"
    ]);
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode([
        'status' => 'error',
        'message' => 'Ocurrió un error al procesar la solicitud.'
    ]);
    exit;
}
?>
