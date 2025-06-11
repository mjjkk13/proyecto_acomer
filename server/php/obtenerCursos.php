<?php
// ===============================
// CABECERAS PARA RESPUESTAS JSON Y CORS
// ===============================
header('Content-Type: application/json; charset=utf-8');
require 'cors.php';

header('Cache-Control: public, max-age=300');

// ===============================
// RESPUESTA A PETICIONES OPTIONS (preflight)
// ===============================


// ===============================
// CONEXIÓN A LA BASE DE DATOS
// ===============================
require_once 'conexion.php';
$pdo = getPDO(); 
// ===============================
// ESTRUCTURA DE RESPUESTA
// ===============================
$response = [
    'success' => false,
    'data' => [],
    'message' => '',
    'count' => 0
];

// ===============================
// LÓGICA PRINCIPAL
// ===============================
try {
    // Validar método HTTP
    if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
        http_response_code(405);
        throw new Exception('Método no permitido. Use GET');
    }

    // No se permiten parámetros en la URL
    if (!empty($_GET)) {
        http_response_code(400);
        throw new Exception('Parámetros de consulta no permitidos');
    }

    // Consulta sin WHERE estado=1 porque ya no se usa
    $sql = "
        SELECT 
            idcursos AS id, 
            nombrecurso AS nombre 
        FROM cursos 
        ORDER BY nombrecurso ASC
    ";

    $stmt = $pdo->prepare($sql);
    $stmt->execute();

    $cursos = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $count = count($cursos);

    $response = [
        'success' => true,
        'data' => $cursos,
        'count' => $count,
        'message' => "$count cursos encontrados"
    ];

    http_response_code(200);

} catch (PDOException $e) {
    error_log('Error en obtenerCursos: ' . $e->getMessage());

    $response['message'] = 'Error al obtener los cursos: ' . $e->getMessage();
    http_response_code(500);

} catch (Exception $e) {
    $response['message'] = $e->getMessage();
    // Si el código HTTP no es válido, usar 400 por defecto
    $code = (int)$e->getCode();
    http_response_code(($code >= 400 && $code < 600) ? $code : 400);
}

// ===============================
// RESPUESTA FINAL
// ===============================
echo json_encode($response, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
?>
