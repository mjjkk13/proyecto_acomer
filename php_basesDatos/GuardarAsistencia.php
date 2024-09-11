<?php
include 'conexion.php';

header('Content-Type: application/json');

$idEstudiante = $_POST['idalumnos'];  // Cambiado a 'idalumnos' 
$estado = $_POST['estado'];  // Mantiene el nombre 'estado'
$fechaHora = date('Y-m-d H:i:s');  // Obtiene la fecha y hora actual

try {
    $conn = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Actualizar el estado y la fecha/hora en la tabla asistencia
    $sql = "
        UPDATE asistencia a
        INNER JOIN alumnos al ON al.idalumnos = a.alumnos_idalumnos
        SET a.estado = :estado, a.fecha = :fechaHora
        WHERE al.idalumnos = :idalumnos'
    ";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':estado', $estado);
    $stmt->bindParam(':fechaHora', $fechaHora);
    $stmt->bindParam(':idalumnos', $idEstudiante);  // Cambiado a 'idalumnos'

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
