<?php
// ===============================
// CABECERAS PARA RESPUESTAS JSON Y CORS
// ===============================
header('Content-Type: application/json; charset=utf-8');
require_once 'cors.php';
header('Cache-Control: no-cache, no-store, must-revalidate');

// ===============================
// RESPUESTA A PETICIONES OPTIONS (preflight)
// ===============================
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit();
}

// ===============================
// CONEXIÓN A LA BASE DE DATOS
// ===============================
require_once 'conexion.php';

// Verificación de conexión
if (!$pdo) {
    echo json_encode([
        'success' => false,
        'message' => 'Error de conexión a la base de datos'
    ]);
    exit();
}

// ===============================
// ESTRUCTURA DE RESPUESTA
// ===============================
$response = [
    'success' => false,
    'data' => [],
    'message' => '',
    'count' => 0,
    'debug' => [
        'query' => '',
        'execution_time' => ''
    ]
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

    // Consulta adaptada a tu estructura de tabla
    $sql = "SELECT 
                c.idcursos AS id, 
                c.nombrecurso AS nombre,
                c.docente_id
            FROM cursos c
            LEFT JOIN alumnos a ON c.idcursos = a.curso_id
            WHERE a.curso_id IS NULL
            ORDER BY c.nombrecurso ASC";

    $response['debug']['query'] = $sql;
    $response['debug']['execution_time'] = date('Y-m-d H:i:s');

    $stmt = $pdo->prepare($sql);
    $stmt->execute();

    $cursos = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $count = count($cursos);

    $response = [
        'success' => true,
        'data' => $cursos,
        'count' => $count,
        'message' => $count > 0 ? "Se encontraron $count cursos sin alumnos" : "No hay cursos sin alumnos registrados",
        'debug' => $response['debug']
    ];

} catch (PDOException $e) {
    $response['message'] = 'Error en la base de datos: ' . $e->getMessage();
    $response['debug']['error'] = [
        'code' => $e->getCode(),
        'message' => $e->getMessage()
    ];
    http_response_code(500);
} catch (Exception $e) {
    $response['message'] = $e->getMessage();
    http_response_code(400);
}

// ===============================
// RESPUESTA FINAL
// ===============================
echo json_encode($response, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);