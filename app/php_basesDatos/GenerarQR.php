<?php
include 'conexion.php';  // Incluye la conexión a la base de datosax
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL & ~E_DEPRECATED & ~E_NOTICE);

header('Content-Type: application/json');

// Recibe los datos de los estudiantes seleccionados desde la petición POST
$estudiantesSeleccionados = json_decode($_POST['estudiantes'], true);

// Verifica que haya estudiantes seleccionados
if (empty($estudiantesSeleccionados)) {
    echo json_encode(array("status" => "error", "message" => "No hay estudiantes seleccionados"));
    exit();
}

$datosQR = "";
$cursosNombres = [];
$docentes = [];

// Recopilar datos para generar el QR
foreach ($estudiantesSeleccionados as $estudiante) {
    $datosQR .= "ID: " . $estudiante['idalumnos'] . " - Nombre: " . $estudiante['nombreAlumnos'] . " - Apellidos: " . $estudiante['apellidosAlumnos'] . "\n";
    $datosQR .= "Curso: " . $estudiante['nombreCurso'] . "\n";
    if (!in_array($estudiante['nombreCurso'], $cursosNombres)) {
        $cursosNombres[] = $estudiante['nombreCurso'];
    }
    if (!in_array($estudiante['nombreDocente'], $docentes)) {
        $docentes[] = $estudiante['nombreDocente'];
    }
}
$datosQR .= "Total de estudiantes: " . count($estudiantesSeleccionados);

// Incluye la librería para generación de códigos QR
include '../phpqrcode-master/qrlib.php';

// Genera un identificador único basado en la marca de tiempo
$uniqueId = time();
$filename = "../qr_codes/qr_all_students_{$uniqueId}.png";

// Verifica si la carpeta existe, si no, la crea
$directory = dirname(__DIR__) . '/qr_codes';
if (!file_exists($directory)) {
    mkdir($directory, 0777, true);
}

// Genera el código QR
QRcode::png($datosQR, $filename, 'L', 4, 2);

try {
    // Guarda la información en la tabla `qrgenerados`
    $sqlInsertQR = "INSERT INTO qrgenerados (codigoqr, fechageneracion) VALUES (:filename, :fechageneracion)";
    $stmtQR = $pdo->prepare($sqlInsertQR);
    $stmtQR->bindValue(':filename', $filename, PDO::PARAM_STR);
    $fechageneracion = date('Y-m-d H:i:s');
    $stmtQR->bindValue(':fechageneracion', $fechageneracion, PDO::PARAM_STR);
    $stmtQR->execute();

    // Obtener el ID del código QR recién insertado
    $idQrGenerados = $pdo->lastInsertId();

    // Actualizar la tabla de asistencia con el ID del QR generado basado en el nombre del docente usando JOIN
    $sqlUpdateAsistencia = "
        UPDATE asistencia a
        JOIN usuarios u ON a.docente_iddocente = u.idusuarios
        JOIN tipo_usuario t ON u.tipo_usuario_idtipo_usuario = t.idtipo_usuario
        SET a.qrgenerados_idqrgenerados = :idqrgenerados
        WHERE t.rol = 'Docente'
        AND u.nombre = :nombreDocente";
    $stmtAsistencia = $pdo->prepare($sqlUpdateAsistencia);
    
    // Ejecutar la actualización para cada docente
    foreach ($docentes as $nombreDocente) {
        $stmtAsistencia->bindValue(':idqrgenerados', $idQrGenerados, PDO::PARAM_INT);
        $stmtAsistencia->bindValue(':nombreDocente', $nombreDocente, PDO::PARAM_STR);
        $stmtAsistencia->execute();
    }
    // Actualizar la tabla de cursos con el ID del QR generado basado en el nombre del curso
    $sqlUpdateCursos = "UPDATE cursos SET qrgenerados_idqrgenerados = :idqrgenerados WHERE nombrecurso = :nombrecurso";
    $stmtCursos = $pdo->prepare($sqlUpdateCursos);

    // Ejecutar la actualización para cada curso
    foreach ($cursosNombres as $cursoNombre) {
        $stmtCursos->bindValue(':idqrgenerados', $idQrGenerados, PDO::PARAM_INT);
        $stmtCursos->bindValue(':nombrecurso', $cursoNombre, PDO::PARAM_STR);
        $stmtCursos->execute();
    }

    // Guarda el total de estudiantes que asistieron en la tabla `estadisticasqr`
    $sqlInsertEstadisticas = "INSERT INTO estadisticasqr (fecha, estudiantesqasistieron) VALUES (:fecha, :estudiantesqasistieron)";
    $stmtEstadisticas = $pdo->prepare($sqlInsertEstadisticas);
    $stmtEstadisticas->bindValue(':fecha', $fechageneracion, PDO::PARAM_STR);
    $stmtEstadisticas->bindValue(':estudiantesqasistieron', count($estudiantesSeleccionados), PDO::PARAM_INT);
    $stmtEstadisticas->execute();

    echo json_encode(array("status" => "success", "message" => "Código QR generado correctamente", "qr_image" => $filename));
} catch (PDOException $e) {
    echo json_encode(array("status" => "error", "message" => "Error al generar el código QR: " . $e->getMessage()));
}

$pdo = null;
?>
