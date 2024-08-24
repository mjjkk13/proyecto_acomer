<?php
include 'conexion.php';

header('Content-Type: application/json');

$idEstudiante = $_POST['idAlumnos'];
$nombreEstudiante = $_POST['nombreAlumnos'];
$apellidosEstudiante = $_POST['apellidosAlumnos'];
$asistio = $_POST['asistio'];

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = "UPDATE alumnos_asistencia SET asistio = :asistio WHERE idAlumnos = :idAlumnos";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':asistio', $asistio);
    $stmt->bindParam(':idAlumnos', $idEstudiante);

    if ($stmt->execute()) {
        echo json_encode(array("status" => "success", "message" => "Asistencia actualizada correctamente"));
    } else {
        echo json_encode(array("status" => "error", "message" => "Error al actualizar asistencia"));
    }
} catch (PDOException $e) {
    echo json_encode(array("status" => "error", "message" => "Error al actualizar asistencia: " . $e->getMessage()));
}

$conn = null;
?>
