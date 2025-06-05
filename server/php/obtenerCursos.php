<?php
// ===============================
// CABECERAS PARA RESPUESTAS JSON Y CORS
// ===============================
header('Content-Type: application/json; charset=utf-8');
require_once 'cors.php';

header('Cache-Control: public, max-age=300');

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
// OpenAPI Annotations
// ===============================
/**
 * @OA\Get(
 *     path="/cursos",
 *     summary="Obtener lista de cursos",
 *     description="Devuelve una lista de todos los cursos ordenados por nombre.",
 *     operationId="obtenerCursos",
 *     tags={"Cursos"},
 *     responses={
 *         @OA\Response(
 *             response=200,
 *             description="Lista de cursos obtenida con éxito",
 *             @OA\JsonContent(
 *                 @OA\Property(
 *                     property="success",
 *                     type="boolean",
 *                     description="Indicador de si la operación fue exitosa"
 *                 ),
 *                 @OA\Property(
 *                     property="data",
 *                     type="array",
 *                     @OA\Items(
 *                         type="object",
 *                         @OA\Property(
 *                             property="id",
 *                             type="integer"
 *                         ),
 *                         @OA\Property(
 *                             property="nombre",
 *                             type="string"
 *                         )
 *                     )
 *                 ),
 *                 @OA\Property(
 *                     property="message",
 *                     type="string",
 *                     description="Mensaje adicional"
 *                 ),
 *                 @OA\Property(
 *                     property="count",
 *                     type="integer",
 *                     description="Número de cursos encontrados"
 *                 )
 *             )
 *         ),
 *         @OA\Response(
 *             response=400,
 *             description="Error debido a parámetros de consulta no permitidos",
 *             @OA\JsonContent(
 *                 @OA\Property(
 *                     property="success",
 *                     type="boolean"
 *                 ),
 *                 @OA\Property(
 *                     property="message",
 *                     type="string"
 *                 )
 *             )
 *         ),
 *         @OA\Response(
 *             response=405,
 *             description="El método HTTP no es permitido",
 *             @OA\JsonContent(
 *                 @OA\Property(
 *                     property="success",
 *                     type="boolean"
 *                 ),
 *                 @OA\Property(
 *                     property="message",
 *                     type="string"
 *                 )
 *             )
 *         ),
 *         @OA\Response(
 *             response=500,
 *             description="Error interno del servidor",
 *             @OA\JsonContent(
 *                 @OA\Property(
 *                     property="success",
 *                     type="boolean"
 *                 ),
 *                 @OA\Property(
 *                     property="message",
 *                     type="string"
 *                 )
 *             )
 *         )
 *     }
 * )
 */

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
