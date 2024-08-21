<?php
include 'conexion.php';

$sql = "SELECT Id_Estudiantes, Nombre, Correo FROM estudiantes";
$result = $conn->query($sql);

$estudiantes = array();

if ($result->num_rows > 0) {
  while ($row = $result->fetch_assoc()) {
    $estudiantes[] = $row;
  }
}

echo json_encode($estudiantes);

$conn->close();
?>
