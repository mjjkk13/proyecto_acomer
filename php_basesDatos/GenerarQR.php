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
$filename = "../qr_codes/qr_all_students_{$uniqueId}.png";

// Verifica si la carpeta existe
if (!file_exists(dirname($filename))) {
    echo json_encode(array("status" => "error", "message" => "La carpeta para guardar el QR no existe"));
    exit();
}

// Genera el QR
QRcode::png($datosQR, $filename, 'L', 4, 2);

// Guarda la información en la base de datos
$sql = "INSERT INTO qr (CodigoQR) VALUES ('$filename')";
if ($conn->query($sql) === TRUE) {
    $idQR = $conn->insert_id;
    $fechaHora = date('Y-m-d H:i:s');

    $sql = "INSERT INTO lecturaqr (FechaLecturaQR, idQR) VALUES ('$fechaHora', '$idQR')";
    if ($conn->query($sql) === TRUE) {
        echo json_encode(array("status" => "success", "message" => "Código QR generado correctamente", "qr_image" => 'qr_all_students_' . $uniqueId . '.png'));
    } else {
        echo json_encode(array("status" => "error", "message" => "Error al guardar lectura de QR: " . $conn->error));
    }
} else {
    echo json_encode(array("status" => "error", "message" => "Error al guardar código QR: " . $conn->error));
}

$conn->close();
?>
