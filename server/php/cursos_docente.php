<?php 
session_start();
require_once 'conexion.php';
$pdo = getPDO(); // <- Se obtiene la conexión aquí

header('Content-Type: application/json; charset=utf-8');
require 'cors.php';

// Verificar si el usuario está autenticado
if (!isset($_SESSION['idusuarios'])) {
    echo json_encode([
        'status' => 'error',
        'message' => 'No se ha iniciado sesión.'
    ]);
    exit;
}

$usuario_id = $_SESSION['idusuarios'];

try {
    // Obtener iddocente
    $sqlDocente = "SELECT iddocente FROM docente WHERE usuario_id = :usuario_id";
    $stmtDocente = $pdo->prepare($sqlDocente);
    $stmtDocente->bindParam(':usuario_id', $usuario_id, PDO::PARAM_INT);
    $stmtDocente->execute();
    $docente = $stmtDocente->fetch(PDO::FETCH_ASSOC);

    if (!$docente) {
        echo json_encode([
            'status' => 'error',
            'message' => 'No se encontró un docente relacionado con este usuario.'
        ]);
        exit;
    }

    $iddocente = $docente['iddocente'];

    // Obtener cursos
    $sql = "SELECT idcursos, nombrecurso FROM cursos WHERE docente_id = :docente_id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':docente_id', $iddocente, PDO::PARAM_INT);
    $stmt->execute();
    $cursos = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($cursos);
} catch (PDOException $e) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Error al obtener los cursos: ' . $e->getMessage()
    ]);
}
?>
