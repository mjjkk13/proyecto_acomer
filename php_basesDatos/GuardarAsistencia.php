<?php
include 'conexion.php';

header('Content-Type: application/json');

$idEstudiante = $_POST['idAlumnos'];
$nombreEstudiante = $_POST['nombreAlumnos'];
$apellidosEstudiante = $_POST['apellidosAlumnos'];
$asistio = $_POST['asistio'];

$sql = "UPDATE alumnos_asistencia SET asistio = '$asistio' WHERE idAlumnos = '$idEstudiante'";

if ($conn->query($sql) === TRUE) {
    echo json_encode(array("status" => "success", "message" => "Asistencia actualizada correctamente"));
} else {
    echo json_encode(array("status" => "error", "message" => "Error al actualizar asistencia: " . $conn->error));
}

$conn->close();
?>
