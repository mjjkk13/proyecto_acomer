<?php
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: http://localhost:5173');
header('Access-Control-Allow-Methods: GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');
header('Access-Control-Allow-Credentials: true');
header('Cache-Control: public, max-age=300');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit();
}

require_once 'conexion.php';

$response = [
    'success' => false,
    'data' => [],
    'message' => '',
    'count' => 0
];

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
        http_response_code(405);
        throw new Exception('Método no permitido. Use GET');
    }

    if (!empty($_GET)) {
        http_response_code(400);
        throw new Exception('Parámetros de consulta no permitidos');
    }

    // Consulta modificada según tu estructura real
    $sql = "SELECT 
                idcursos as id, 
                nombrecurso as nombre 
            FROM cursos 
            ORDER BY nombrecurso ASC";  // Eliminamos WHERE estado=1

    $stmt = $pdo->prepare($sql);
    $stmt->execute();

    $cursos = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $count = count($cursos);

    $response = [
        'success' => true,
        'data' => $cursos,
        'count' => $count,
        'message' => "{$count} cursos encontrados"
    ];
    http_response_code(200);

} catch (PDOException $e) {
    error_log('Error en obtenerCursos: ' . $e->getMessage());
    $response['message'] = 'Error al obtener los cursos: ' . $e->getMessage();
    http_response_code(500);
} catch (Exception $e) {
    $response['message'] = $e->getMessage();
    http_response_code($e->getCode());
}

echo json_encode($response, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
?>