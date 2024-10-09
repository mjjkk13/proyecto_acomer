<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL & ~E_DEPRECATED & ~E_NOTICE);

require_once 'C:/xampp/htdocs/Proyecto/core/database.php'; // Asegúrate de que la ruta es correcta

header('Content-Type: application/json');

try {
    // Obtener la conexión a la base de datos
    $database = new Database();
    $pdo = $database->getConnection();

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
    echo "Error en la base de datos: " . $e->getMessage();
    exit();
}

// Cerrar la conexión (PDO se cierra automáticamente al finalizar el script)
?>