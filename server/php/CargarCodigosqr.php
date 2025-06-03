<?php
// Aseguramos la configuración adecuada de la cookie de sesión
session_set_cookie_params([
    'lifetime' => 0,
    'path' => '/',
    'domain' => 'acomer.onrender.com', 
    'secure' => true,
    'httponly' => true,
    'samesite' => 'None',
]);

session_start();

// CORS headers DEBEN ir antes de cualquier salida
require 'cors.php';
require_once 'conexion.php';

$pdo = getPDO(); 

// Manejo de preflight (OPTIONS)
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// DEBUG opcional para ver sesión
// file_put_contents('debug.log', "SESSION: " . print_r($_SESSION, true), FILE_APPEND);

try {
    if (!isset($_SESSION['idusuarios']) || !isset($_SESSION['user']) || !isset($_SESSION['rol'])) {
        throw new Exception("Sesión no iniciada correctamente.");
    }

    $userId = $_SESSION['idusuarios'];
    $usuario = $_SESSION['user'];
    $role = $_SESSION['rol'];

    $whereSql = '';
    $params = [];

    if ($role === 'Docente') {
        // Obtener id docente
        $stmtDocente = $pdo->prepare("SELECT iddocente FROM docente WHERE usuario_id = :uid");
        $stmtDocente->execute([':uid' => $userId]);
        $docente = $stmtDocente->fetch(PDO::FETCH_ASSOC);

        if (!$docente) {
            throw new Exception("Docente no encontrado para el usuario.");
        }

        $whereSql = "WHERE a.docente_id = :docente_id";
        $params[':docente_id'] = $docente['iddocente'];
    }

    $sql = "
        SELECT 
            q.idqrgenerados,
            q.codigoqr,
            q.fechageneracion,
            MAX(c.nombrecurso) AS nombrecurso,
            MAX(a.fecha) AS fecha_uso,
            a.docente_id AS iddocente
        FROM qrgenerados q
        LEFT JOIN asistencia a ON q.idqrgenerados = a.qrgenerados_id
        LEFT JOIN alumnos al ON a.alumno_id = al.idalumno
        LEFT JOIN cursos c ON al.curso_id = c.idcursos
        $whereSql
        GROUP BY q.idqrgenerados, q.codigoqr, q.fechageneracion, a.docente_id
        ORDER BY q.fechageneracion DESC
    ";

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);

    $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        'status' => 'success',
        'data' => $resultados
    ]);
} catch (Exception $e) {
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage()
    ]);
}
