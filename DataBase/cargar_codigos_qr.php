<?php
include 'conexion.php';

// Consulta para obtener los códigos QR registrados
$sql = "SELECT * FROM codigos_qr ORDER BY fecha_hora DESC";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $codigos = array();
    while ($row = $result->fetch_assoc()) {
        $codigos[] = array(
            'fecha_hora' => $row['fecha_hora'],
            'imagen' => $row['imagen']
        );
    }
    echo json_encode($codigos);
} else {
    echo json_encode(array()); // Devuelve un array vacío si no hay códigos registrados
}

$conn->close();
?>
