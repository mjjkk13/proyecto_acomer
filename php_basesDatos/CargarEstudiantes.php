<?php
include 'conexion.php';

// Consulta SQL sin alias
$sql = "
SELECT 
    alumnos.idalumnos, 
    alumnos.nombre, 
    alumnos.apellido, 
    cursos.nombrecurso, 
    usuarios.nombre AS nombreDocente
FROM 
    alumnos
INNER JOIN 
    cursos ON alumnos.cursos_idcursos = cursos.idcursos
INNER JOIN 
    docentealumnos ON alumnos.docentealumnos_iddocentealumnos = docentealumnos.iddocentealumnos
INNER JOIN 
    usuarios ON docentealumnos.docente_iddocente = usuarios.idusuarios;
";

try {
    // Ejecuta la consulta SQL
    $stmt = $pdo->query($sql);
    $estudiantes = array();

    // Recolecta los resultados en un array
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $estudiantes[] = $row;
    }

    // Envia la respuesta en formato JSON
    echo json_encode($estudiantes);
} catch (PDOException $e) {
    // Manejo de errores
    echo "Error: " . $e->getMessage();
}

// Cierra la conexión a la base de datos
$pdo = null;
?>
