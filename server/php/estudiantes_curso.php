<?php
require_once 'conexion.php'; // Función getPDO() debe estar definida aquí
require 'cors.php';

header('Content-Type: application/json; charset=utf-8');

// Validar parámetro requerido
if (!isset($_GET['curso_id'])) {
    http_response_code(400);
    echo json_encode([
        'status' => 'error',
        'message' => 'Falta el id del curso'
    ]);
    exit;
}

$curso_id = $_GET['curso_id'];

try {
    $pdo = getPDO(); // Obtener conexión
    $sql = "SELECT idalumno, nombre, apellido FROM alumnos WHERE curso_id = :curso_id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':curso_id', $curso_id, PDO::PARAM_INT);
    $stmt->execute();

    $estudiantes = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        'status' => 'success',
        'data' => $estudiantes
    ]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'status' => 'error',
        'message' => 'Error al obtener los estudiantes: ' . $e->getMessage()
    ]);
}
?>
