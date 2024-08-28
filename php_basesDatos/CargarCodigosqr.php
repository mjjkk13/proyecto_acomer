<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include 'conexion.php'; // Asegúrate de que este archivo contiene la conexión PDO correctamente configurada

$sql = "SELECT codigoqr, fechageneracion FROM qrgenerados";

try {
    $stmt = $pdo->query($sql);
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
    exit(); 
}

// Cerrar la conexión
$pdo = null;
?>


