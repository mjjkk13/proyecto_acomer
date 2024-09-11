<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include 'conexion.php'; // Asegúrate de que este archivo contiene la conexión PDO correctamente configurada

$sql =  "SELECT q.codigoqr, q.fechageneracion, q.idqrgenerados, c.nombrecurso
FROM qrgenerados q
JOIN cursos c ON q.idqrgenerados = c.qrgenerados_idqrgenerados
ORDER BY q.fechageneracion DESC";

try {
    $stmt = $pdo->query($sql);
    $codigos = array();

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $codigos[] = array(
            'fecha_hora' => $row['fechageneracion'],
            'imagen' => $row['codigoqr'],
            'nombrecurso' => $row['nombrecurso'] 
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


