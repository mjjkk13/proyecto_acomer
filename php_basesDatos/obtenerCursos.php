<?php
// Incluir el archivo de conexión a la base de datos
include 'conexion.php';

// Verificar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Consulta SQL para obtener los cursos
$sql = "SELECT nombreCurso, Director FROM cursos";
$result = $conn->query($sql);

// Verificar si se obtuvieron resultados
$cursos = array();
if ($result->num_rows > 0) {
    // Almacenar los resultados en un array
    while ($row = $result->fetch_assoc()) {
        $cursos[] = $row;
    }
}

// Cerrar conexión
$conn->close();

// Devolver los resultados en formato JSON
header('Content-Type: application/json');
echo json_encode($cursos);
?>
