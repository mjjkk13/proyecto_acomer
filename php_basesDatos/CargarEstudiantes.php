<?php
include 'conexion.php';

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
    $stmt = $pdo->query($sql);
    $estudiantes = array();

    // Recolecta los resultados en un array
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $estudiantes[] = $row;
    }

    echo json_encode($estudiantes);
} catch (PDOException $e) {
    // Manejo de errores
    echo "Error: " . $e->getMessage();
}

$pdo = null;
?>
