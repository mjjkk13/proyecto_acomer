<?php
include 'conexion.php';

$sql = "SELECT idAlumnos, identificacionAlumnos, tipodocumentoAlumnos, nombreAlumnos, apellidosAlumnos FROM alumnos";
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
