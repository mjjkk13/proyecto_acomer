<?php
include 'conexion.php';

// Guardar el código QR en la base de datos
$fechaHora = date('Y-m-d H:i:s');
$imagen = $_POST['imagen'];

$sql = "INSERT INTO codigos_qr (fecha_hora, imagen) VALUES ('$fechaHora', '$imagen')";

if ($conn->query($sql) === TRUE) {
  echo "Código QR guardado correctamente";
} else {
  echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>
