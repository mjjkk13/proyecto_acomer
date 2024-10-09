<?php
require_once 'C:/xampp/htdocs/Proyecto/core/database.php';  // Incluye la conexión a la base de datos
error_reporting(E_ALL & ~E_DEPRECATED & ~E_NOTICE);
header('Content-Type: application/json');

$idEstudiante = $_POST['idalumnos'];  // Cambiado a 'idalumnos'
$estado = $_POST['estado'];  // Mantiene el nombre 'estado'
$fechaHora = date('Y-m-d H:i:s');  // Obtiene la fecha y hora actual

try {
    $database = new Database();
    $pdo = $database->getConnection();

    // Actualizar el estado y la fecha/hora en la tabla asistencia
    $sql = "
        UPDATE asistencia a
        INNER JOIN alumnos al ON al.idalumnos = a.alumnos_idalumnos
        SET a.estado = :estado, a.fecha = :fechaHora
        WHERE al.idalumnos = :idalumnos
    ";  // Elimina el apóstrofe extra al final de esta línea

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

?>
