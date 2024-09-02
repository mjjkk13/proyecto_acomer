<?php
// Incluir el archivo de conexión a la base de datos
include 'conexion.php';

try {
    $conn = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Consulta SQL con JOIN para obtener los cursos y los nombres de los profesores
    $sql = "
        SELECT cursos.nombrecurso, usuarios.nombre AS nombredocente, usuarios.apellido AS apellidodocente, tipo_usuario.rol
        FROM cursos
        JOIN docente ON cursos.docente_iddocente = docente.iddocente
        JOIN usuarios ON docente.usuarios_idusuarios = usuarios.idusuarios
        JOIN tipo_usuario ON usuarios.tipo_usuario_idtipo_usuario = tipo_usuario.idtipo_usuario
        WHERE tipo_usuario.rol = 'Docente'
    ";

    $stmt = $conn->prepare($sql);
    $stmt->execute();

    // Obtener los resultados
    $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Devolver los resultados en formato JSON
    header('Content-Type: application/json');
    echo json_encode($resultados);
} catch (PDOException $e) {
    echo json_encode(['error' => 'Error en la consulta SQL: ' . $e->getMessage()]);
}

// Cerrar conexión
$conn = null;
?>
