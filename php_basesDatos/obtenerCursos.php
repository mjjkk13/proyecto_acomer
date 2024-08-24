<?php
// Incluir el archivo de conexión a la base de datos
include 'conexion.php';

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Consulta SQL para obtener los cursos
    $sql = "SELECT nombreCurso, Director FROM cursos";
    $stmt = $conn->prepare($sql);
    $stmt->execute();

    // Obtener los resultados
    $cursos = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Devolver los resultados en formato JSON
    header('Content-Type: application/json');
    echo json_encode($cursos);
} catch (PDOException $e) {
    echo json_encode(['error' => 'Error en la consulta SQL: ' . $e->getMessage()]);
}

// Cerrar conexión
$conn = null;
?>
