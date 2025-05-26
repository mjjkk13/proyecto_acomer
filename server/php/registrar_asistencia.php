<?php 
/**
 * @OA\Post(
 *     path="/api/asistencia",
 *     summary="Registra las asistencias de los estudiantes y genera un código QR.",
 *     description="Este endpoint registra la asistencia de los estudiantes en un curso y genera un código QR con la información de las asistencias.",
 *     operationId="registrarAsistencia",
 *     tags={"Asistencia"},
 *     requestBody={
 *         @OA\RequestBody(
 *             required=true,
 *             @OA\Content(
 *                 mediaType="application/json",
 *                 @OA\Schema(
 *                     type="object",
 *                     required={"idcursos", "asistencias"},
 *                     @OA\Property(
 *                         property="idcursos",
 *                         type="integer",
 *                         description="ID del curso al cual se está registrando la asistencia."
 *                     ),
 *                     @OA\Property(
 *                         property="asistencias",
 *                         type="array",
 *                         @OA\Items(
 *                             type="object",
 *                             required={"alumno_id", "estado"},
 *                             @OA\Property(
 *                                 property="alumno_id",
 *                                 type="integer",
 *                                 description="ID del alumno cuya asistencia se registra."
 *                             ),
 *                             @OA\Property(
 *                                 property="estado",
 *                                 type="integer",
 *                                 description="Estado de la asistencia (1 para presente, 0 para ausente)."
 *                             )
 *                         )
 *                     )
 *                 )
 *             )
 *         )
 *     },
 *     responses={
 *         @OA\Response(
 *             response=200,
 *             description="Asistencia registrada correctamente y QR generado.",
 *             @OA\JsonContent(
 *                 @OA\Property(
 *                     property="status",
 *                     type="string",
 *                     example="success"
 *                 ),
 *                 @OA\Property(
 *                     property="message",
 *                     type="string",
 *                     example="Asistencia registrada correctamente"
 *                 ),
 *                 @OA\Property(
 *                     property="qr_image",
 *                     type="string",
 *                     example="http://localhost/proyecto_acomer/server/php/qrcodes/qr_asistencia_1631845918.png"
 *                 )
 *             )
 *         ),
 *         @OA\Response(
 *             response=400,
 *             description="Datos incompletos o inválidos.",
 *             @OA\JsonContent(
 *                 @OA\Property(
 *                     property="status",
 *                     type="string",
 *                     example="error"
 *                 ),
 *                 @OA\Property(
 *                     property="message",
 *                     type="string",
 *                     example="Datos incompletos o inválidos."
 *                 )
 *             )
 *         ),
 *         @OA\Response(
 *             response=401,
 *             description="El usuario no ha iniciado sesión.",
 *             @OA\JsonContent(
 *                 @OA\Property(
 *                     property="status",
 *                     type="string",
 *                     example="error"
 *                 ),
 *                 @OA\Property(
 *                     property="message",
 *                     type="string",
 *                     example="No ha iniciado sesión"
 *                 )
 *             )
 *         ),
 *         @OA\Response(
 *             response=404,
 *             description="No se encontró un docente relacionado con este usuario o el curso no está asignado al docente.",
 *             @OA\JsonContent(
 *                 @OA\Property(
 *                     property="status",
 *                     type="string",
 *                     example="error"
 *                 ),
 *                 @OA\Property(
 *                     property="message",
 *                     type="string",
 *                     example="No se encontró un docente relacionado con este usuario."
 *                 )
 *             )
 *         ),
 *         @OA\Response(
 *             response=500,
 *             description="Error interno del servidor.",
 *             @OA\JsonContent(
 *                 @OA\Property(
 *                     property="status",
 *                     type="string",
 *                     example="error"
 *                 ),
 *                 @OA\Property(
 *                     property="message",
 *                     type="string",
 *                     example="Ocurrió un error al procesar la solicitud."
 *                 )
 *             )
 *         )
 *     }
 * )
 */

header('Content-Type: application/json');
header('Cache-Control: no-cache, must-revalidate');

require_once 'conexion.php';
require_once __DIR__ . '/phpqrcode/qrlib.php';
require 'cors.php';

session_start();

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
