<?php
include 'conexion.php';

// Obtener los datos de la solicitud POST
$idEstudiante = $_POST['idEstudiante'];
$asistio = $_POST['asistio'];

// Actualizar la asistencia en la base de datos
$sql = "UPDATE estudiantes SET Asistio = '$asistio' WHERE Id_Estudiantes = '$idEstudiante'";

if ($conn->query($sql) === TRUE) {
  echo "Asistencia actualizada correctamente";
} else {
  echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>
