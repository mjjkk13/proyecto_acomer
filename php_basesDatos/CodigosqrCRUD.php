<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include 'conexion.php'; 

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $sqlSelect = "SELECT a.idasistencia, a.fecha, q.codigoqr, q.fechageneracion, q.idqrgenerados
                  FROM asistencia a
                  JOIN qrgenerados q ON a.qrgenerados_idqrgenerados = q.idqrgenerados
                  ORDER BY a.fecha DESC";

    try {
        $stmt = $pdo->query($sqlSelect);
        $codigos = array();

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $codigos[] = array(
                'id_asistencia' => $row['idasistencia'],
                'fechageneracion' => $row['fechageneracion'],
                'codigoqr' => $row['codigoqr'],
                'id_qrgenerados' => $row['idqrgenerados']
            );
        }

        echo json_encode($codigos);
    } catch (PDOException $e) {
        echo json_encode(['error' => 'Error en SELECT: ' . $e->getMessage()]);
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['eliminar'])) {
    $idAsistencia = $_POST['id_asistencia'];
    $idQrGenerados = $_POST['id_qrgenerados'];

    try {
        // Primero, eliminar los registros relacionados en `asistencia`
        $sqlDeleteAsistencia = "DELETE FROM asistencia WHERE qrgenerados_idqrgenerados = :id_qrgenerados";
        $stmt = $pdo->prepare($sqlDeleteAsistencia);
        $stmt->bindParam(':id_qrgenerados', $idQrGenerados, PDO::PARAM_INT);
        $stmt->execute();

        // Luego, eliminar el registro en `qrgenerados`
        $sqlDeleteQrGenerados = "DELETE FROM qrgenerados WHERE idqrgenerados = :id_qrgenerados";
        $stmt = $pdo->prepare($sqlDeleteQrGenerados);
        $stmt->bindParam(':id_qrgenerados', $idQrGenerados, PDO::PARAM_INT);
        $stmt->execute();

        echo json_encode(['success' => true, 'message' => 'Registro eliminado con éxito.']);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Error en DELETE: ' . $e->getMessage()]);
    }
}

$pdo = null;
?>
