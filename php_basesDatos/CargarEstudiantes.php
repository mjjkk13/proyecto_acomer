<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL & ~E_DEPRECATED & ~E_NOTICE);
include 'conexion.php';

header('Content-Type: application/json');

try {
    // Consulta solo con alumnos y cursos
    $sql = "
    SELECT 
        alumnos.idalumnos, 
        alumnos.nombre, 
        alumnos.apellido, 
        cursos.nombrecurso
    FROM 
        alumnos
    INNER JOIN 
        cursos ON alumnos.cursos_idcursos = cursos.idcursos
    ";

    $stmt = $pdo->query($sql);
    $estudiantes = array();

    // Recolecta los resultados en un array
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $estudiantes[] = $row;
    }

    // Devuelve los datos en formato JSON
    echo json_encode($estudiantes);

} catch (PDOException $e) {
    // Si ocurre un error, devuelve el mensaje de error
    echo json_encode(array("status" => "error", "message" => "Error: " . $e->getMessage()));
}

$pdo = null;
?>
