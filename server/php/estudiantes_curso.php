<?php
require_once 'conexion.php';

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: http://localhost:5173');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');
header('Access-Control-Allow-Credentials: true');

if (!isset($_GET['curso_id'])) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Falta el id del curso'
    ]);
    exit;
}

$curso_id = $_GET['curso_id'];

try {
    $sql = "
        SELECT 
            idalumno, 
            nombre, 
            apellido 
        FROM alumnos 
        WHERE curso_id = :curso_id
    ";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':curso_id', $curso_id, PDO::PARAM_INT);
    $stmt->execute();

    $estudiantes = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($estudiantes);

} catch (PDOException $e) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Error al obtener los estudiantes: ' . $e->getMessage()
    ]);
}
?>
