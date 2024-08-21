<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include 'conexion.php';
include '../phpqrcode-master/qrlib.php';

// Obtener los estudiantes que asistieron
$sql = "SELECT l.FechaLecturaQR, q.CodigoQR 
        FROM lecturaqr l
        JOIN qr q ON l.idQR = q.idQR 
        WHERE l.FechaLecturaQR IS NOT NULL";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Generar el contenido del QR con los datos de todos los estudiantes que asistieron
    $qr_content = '';
    while ($row = $result->fetch_assoc()) {
        $fecha_hora = $row['FechaLecturaQR'];
        $codigoQR = $row['CodigoQR'];
        $qr_content .= "Fecha y Hora: $fecha_hora, Código QR: $codigoQR\n";
    }

    // Especificar el archivo y la ruta donde se guardará el QR con un nombre único
    $timestamp = time();
    $file_path = "../qr_codes/qr_all_students_$timestamp.png";

    // Generar el QR y guardarlo en el servidor
    QRcode::png($qr_content, $file_path, QR_ECLEVEL_L, 10);

    // Guardar en la base de datos
    $sqlInsert = "INSERT INTO qr (CodigoQR) VALUES ('$file_path')";

    if ($conn->query($sqlInsert) === TRUE) {
        // Preparar la respuesta para SweetAlert
        $response = array(
            'status' => 'success',
            'message' => 'Código QR generado y guardado correctamente.',
            'qr_image' => $file_path,
        );

        // Establecer el valor de asistencia a 0 para todos los estudiantes
        $sqlUpdate = "UPDATE lecturaqr SET FechaLecturaQR = NULL WHERE FechaLecturaQR IS NOT NULL";
        if ($conn->query($sqlUpdate) !== TRUE) {
            $response['message'] .= ' Sin embargo, hubo un error al actualizar la asistencia: ' . $conn->error;
        }
    } else {
        // Preparar la respuesta para SweetAlert
        $response = array(
            'status' => 'error',
            'message' => 'Error al guardar el código QR: ' . $conn->error,
        );
    }
} else {
    $response = array(
        'status' => 'error',
        'message' => 'No hay registros de lectura QR.',
    );
}

// Devolver respuesta en formato JSON
header('Content-Type: application/json');
echo json_encode($response);

$conn->close();
?>
