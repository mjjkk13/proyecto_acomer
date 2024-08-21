<?php
include 'conexion.php';
include '../phpqrcode-master/qrlib.php';

// Obtener los estudiantes que asistieron
$sql = "SELECT * FROM estudiantes WHERE asistio = '1'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Generar el contenido del QR con los datos de todos los estudiantes que asistieron
    $qr_content = '';
    while ($row = $result->fetch_assoc()) {
        $nombre = $row['Nombre'];
        $correo = $row['Correo'];
        $qr_content .= "Nombre: $nombre, Correo: $correo\n";
    }

    // Especificar el archivo y la ruta donde se guardará el QR con un nombre único
    $timestamp = time();
    $file_path = "../qr_codes/qr_all_students_$timestamp.png";

    // Generar el QR y guardarlo en el servidor
    QRcode::png($qr_content, $file_path, QR_ECLEVEL_L, 10);

    // Guardar en la base de datos
    $fechaHora = date('Y-m-d H:i:s');
    $sqlInsert = "INSERT INTO codigos_qr (fecha_hora, imagen) VALUES ('$fechaHora', '$file_path')";

    if ($conn->query($sqlInsert) === TRUE) {
        // Preparar la respuesta para SweetAlert
        $response = array(
            'status' => 'success',
            'message' => 'Código QR generado y guardado correctamente.',
            'qr_image' => $file_path,
        );

        // Establecer el valor de asistencia a 0 para todos los estudiantes
        $sqlUpdate = "UPDATE estudiantes SET asistio = '0'";
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
        'message' => 'No hay estudiantes que hayan asistido.',
    );
}

// Devolver respuesta en formato JSON
header('Content-Type: application/json');
echo json_encode($response);

$conn->close();
?>
