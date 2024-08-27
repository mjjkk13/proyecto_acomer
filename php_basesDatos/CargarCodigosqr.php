<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include 'conexion.php';

$sql = "SELECT a.idasistencia, a.fecha, a.estado, q.codigoqr, q.fechageneracion
        FROM asistencia a
        JOIN qrgenerados q ON a.qrgenerados_idqrgenerados = q.idqrgenerados
        ORDER BY a.fecha DESC";

try {
    $stmt = $pdo->query($sqlSelect);
    $codigos = array();

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $codigos[] = array(
            'fecha_hora' => $row['fechageneracion'],
            'imagen' => $row['codigoqr']
        );
    }

    echo json_encode($codigos);
} catch (PDOException $e) {
    echo "Error en SELECT: " . $e->getMessage();
}

$sqlDelete = "DELETE a
              FROM asistencia a
              JOIN qrgenerados q ON a.qrgenerados_idqrgenerados = q.idqrgenerados
              WHERE q.codigoqr = 'QR_CODE_EXAMPLE'";

try {
    $stmt = $pdo->prepare($sqlDelete);
    $stmt->execute();
    echo "Registro eliminado con éxito.";
} catch (PDOException $e) {
    echo "Error en DELETE: " . $e->getMessage();
}

$pdo = null;
?>
