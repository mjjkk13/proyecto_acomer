<?php
include 'conexion.php';

$sql = "SELECT idAlumnos, identificacionAlumnos, tipodocumentoAlumnos, nombreAlumnos, apellidosAlumnos FROM alumnos";

try {
    $stmt = $pdo->query($sql);
    $estudiantes = array();

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $estudiantes[] = $row;
    }

    echo json_encode($estudiantes);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

$pdo = null;
?>
