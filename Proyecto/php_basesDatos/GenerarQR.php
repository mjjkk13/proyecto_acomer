<?php
include 'conexion.php';

header('Content-Type: application/json');

$estudiantes = json_decode($_POST['estudiantes'], true);

if (!$estudiantes) {
    echo json_encode(array("status" => "error", "message" => "No se recibieron datos de estudiantes"));
    exit();
}

$datosQR = "";
foreach ($estudiantes as $estudiante) {
    $datosQR .= "ID: " . $estudiante['idAlumnos'] . " - Nombre: " . $estudiante['nombreAlumnos'] . " - Apellidos: " . $estudiante['apellidosAlumnos'] . "\n";
}
$datosQR .= "Total de estudiantes: " . count($estudiantes);

include '../phpqrcode-master/qrlib.php';

// Genera un identificador único basado en la marca de tiempo
$uniqueId = time();
$filename = "../qr_codes/qr_all_students_{$uniqueId}.png"; // Se ha eliminado "../" para que la ruta sea relativa desde la raíz del proyecto

// Verifica si la carpeta existe
if (!file_exists(dirname(__DIR__ . '/' . $filename))) {
    echo json_encode(array("status" => "error", "message" => "La carpeta para guardar el QR no existe"));
    exit();
}

// Genera el QR
QRcode::png($datosQR, __DIR__ . '/' . $filename, 'L', 4, 2);

// Guarda la información en la base de datos
$sql = "INSERT INTO qr (CodigoQR) VALUES (:filename)";
try {
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':filename', $filename);
    $stmt->execute();
    $idQR = $pdo->lastInsertId();
    $fechaHora = date('Y-m-d H:i:s');

    echo json_encode(array(
        "status" => "success",
        "message" => "QR generado y guardado correctamente",
        "qr_image" => $filename,
        "idQR" => $idQR
    ));
} catch (PDOException $e) {
    echo json_encode(array("status" => "error", "message" => "Error al guardar en la base de datos: " . $e->getMessage()));
}

$pdo = null;
?>
