<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include 'conexion.php';

// Selección de códigos QR registrados
$sqlSelect = "SELECT a.idasistencia, a.fecha, a.estado, q.codigoqr, q.fechageneracion
              FROM asistencia a
              JOIN qrgenerados q ON a.qrgenerados_idqrgenerados = q.idqrgenerados
              ORDER BY a.fecha DESC";

try {
    $stmt = $pdo->query($sqlSelect);
    $codigos = array();

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $codigos[] = array(
            'id_asistencia' => $row['idasistencia'],
            'fecha_hora' => $row['fechageneracion'],
            'imagen' => $row['codigoqr']
        );
    }

    echo json_encode($codigos);
} catch (PDOException $e) {
    echo "Error en SELECT: " . $e->getMessage();
    exit(); // Termina el script si ocurre un error en la consulta
}

// Eliminar un registro específico si se recibe una solicitud POST con el ID
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $idAsistencia = $_POST['id_asistencia'];

    $sqlDelete = "DELETE FROM asistencia WHERE idasistencia = :id_asistencia";

    try {
        $stmt = $pdo->prepare($sqlDelete);
        $stmt->bindParam(':id_asistencia', $idAsistencia, PDO::PARAM_INT);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            echo json_encode(['success' => true, 'message' => 'Registro eliminado con éxito.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'No se encontró el registro a eliminar.']);
        }
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Error en DELETE: ' . $e->getMessage()]);
    }
}

$pdo = null;
?>
