<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include 'conexion.php';

$sql = "SELECT l.FechaLecturaQR, q.CodigoQR 
        FROM lecturaqr l
        JOIN qr q ON l.idQR = q.idQR 
        ORDER BY l.FechaLecturaQR DESC";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $codigos = array();
    while ($row = $result->fetch_assoc()) {
        $codigos[] = array(
            'fecha_hora' => $row['FechaLecturaQR'],
            'imagen' => $row['CodigoQR']
        );
    }
    echo json_encode($codigos);
} else {
    echo json_encode(array()); // Devuelve un array vacío si no hay códigos registrados
}

$conn->close();
?>
