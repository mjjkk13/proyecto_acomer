<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include 'conexion.php';

$sql = "SELECT l.FechaLecturaQR, q.CodigoQR 
        FROM lecturaqr l
        JOIN qr q ON l.idQR = q.idQR 
        ORDER BY l.FechaLecturaQR DESC";

try {
    $stmt = $pdo->query($sql);
    $codigos = array();

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $codigos[] = array(
            'fecha_hora' => $row['FechaLecturaQR'],
            'imagen' => $row['CodigoQR']
        );
    }

    echo json_encode($codigos);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

$pdo = null;
?>
