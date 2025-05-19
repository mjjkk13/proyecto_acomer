<?php
/**
 * @OA\Schema(
 *     schema="DatabaseConnection",
 *     type="object",
 *     @OA\Property(property="host", type="string", example="localhost"),
 *     @OA\Property(property="db", type="string", example="acomer"),
 *     @OA\Property(property="user", type="string", example="root"),
 *     @OA\Property(property="pass", type="string", example=""),
 *     @OA\Property(property="charset", type="string", example="utf8mb4"),
 *     @OA\Property(property="dsn", type="string", example="mysql:host=localhost;dbname=acomer;charset=utf8mb4"),
 *     @OA\Property(
 *         property="options",
 *         type="object",
 *         @OA\Property(property="PDO::ATTR_ERRMODE", type="integer", example=2),
 *         @OA\Property(property="PDO::ATTR_DEFAULT_FETCH_MODE", type="integer", example=2),
 *         @OA\Property(property="PDO::ATTR_EMULATE_PREPARES", type="boolean", example=false)
 *     )
 * )
 */

// 🔁 AHORA obtenemos las variables desde el entorno
$host = getenv('DB_HOST') ?: 'localhost';
$db = getenv('DB_DATABASE') ?: 'acomer';
$user = getenv('DB_USERNAME') ?: 'root';
$pass = getenv('DB_PASSWORD') ?: '';
$port = getenv('DB_PORT') ?: '3306';
$charset = 'utf8mb4';

// 🔧 Creamos el DSN completo (con puerto incluido)
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
    echo json_encode(['message' => 'Error de conexión a la base de datos']);
    exit;
}
?>
