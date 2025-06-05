<?php
/**
 * @OA\Schema(
 *     schema="DatabaseConnection",
 *     type="object",
 *     @OA\Property(property="host", type="string", example="shuttle.proxy.rlwy.net"),
 *     @OA\Property(property="db", type="string", example="railway"),
 *     @OA\Property(property="user", type="string", example="root"),
 *     @OA\Property(property="pass", type="string", example="XbCktZKUDyJBPvNQmJwUvdxyyWuvFDjm"),
 *     @OA\Property(property="charset", type="string", example="utf8mb4"),
 *     @OA\Property(property="dsn", type="string", example="mysql:host=shuttle.proxy.rlwy.net;port=45701;dbname=railway;charset=utf8mb4"),
 *     @OA\Property(
 *         property="options",
 *         type="object",
 *         @OA\Property(property="PDO::ATTR_ERRMODE", type="integer", example=2),
 *         @OA\Property(property="PDO::ATTR_DEFAULT_FETCH_MODE", type="integer", example=2),
 *         @OA\Property(property="PDO::ATTR_EMULATE_PREPARES", type="boolean", example=false)
 *     )
 * )
 */

/**
 * @OA\Schema(
 *     schema="ErrorResponse",
 *     type="object",
 *     @OA\Property(property="message", type="string", example="Error de conexión a la base de datos")
 * )
 */

/**
 * @OA\Response(
 *     response="DatabaseConnectionError",
 *     description="Error de conexión a la base de datos",
 *     @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
 * )
 */

/**
 * @OA\Info(
 *     title="API de Conexión a Base de Datos",
 *     version="1.0.0",
 *     description="Documentación de la API que maneja la conexión a la base de datos."
 * )
 */

/**
 * @OA\PathItem(
 *     path="/conexion"
 * )
 */

/**
 * @OA\Get(
 *     path="/conexion",
 *     summary="Verificar conexión a la base de datos",
 *     @OA\Response(
 *         response=200,
 *         description="Conexión exitosa"
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Error de conexión a la base de datos",
 *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
 *     )
 * )
 */

// Si este archivo se accede directamente, configurar CORS (para pruebas)
if (basename(__FILE__) === basename($_SERVER['SCRIPT_FILENAME'])) {
    header("Access-Control-Allow-Origin: https://acomer.onrender.com");
    header("Access-Control-Allow-Credentials: true");
    header("Access-Control-Allow-Headers: Content-Type");
    header("Access-Control-Allow-Methods: GET, POST, OPTIONS");

    if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
        http_response_code(204);
        exit;
    }
}

// Datos de conexión Railway
$host = 'shuttle.proxy.rlwy.net';
$port = 45701;
$db = 'railway';
$user = 'root';
$pass = 'XbCktZKUDyJBPvNQmJwUvdxyyWuvFDjm';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;port=$port;dbname=$db;charset=$charset";

$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
    error_log("Database error: " . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        "success" => false,
        "message" => "Error de conexión a la base de datos",
        "error" => $e->getMessage()
    ]);
    exit;
}
?>
