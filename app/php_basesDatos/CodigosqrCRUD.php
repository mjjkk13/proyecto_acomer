<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include 'conexion.php'; 

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    
    $sqlSelect = "SELECT q.codigoqr, q.fechageneracion, q.idqrgenerados
                  FROM qrgenerados q
                  ORDER BY q.fechageneracion DESC";

    try {
        $stmt = $pdo->query($sqlSelect);
        $codigos = array();

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $codigos[] = array(
                'fechageneracion' => $row['fechageneracion'],
                'codigoqr' => $row['codigoqr'],
                'idqrgenerados' => $row['idqrgenerados'],
            );
        }

        echo json_encode($codigos);
    } catch (PDOException $e) {
        echo json_encode(['error' => 'Error en SELECT: ' . $e->getMessage()]);
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['eliminar'])) {
    $idQrGenerados = $_POST['idqrgenerados'];

    try {
        // Eliminar el registro en `qrgenerados`
        $sqlDeleteQrGenerados = "DELETE FROM qrgenerados WHERE idqrgenerados = :idqrgenerados";
        $stmt = $pdo->prepare($sqlDeleteQrGenerados);
        $stmt->bindParam(':idqrgenerados', $idQrGenerados, PDO::PARAM_INT);
        $stmt->execute();

        echo json_encode(['success' => true, 'message' => 'Registro eliminado con éxito.']);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Error en DELETE: ' . $e->getMessage()]);
    }
}

$pdo = null;
?>
