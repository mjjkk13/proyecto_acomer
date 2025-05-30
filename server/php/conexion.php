<?php
header('Content-Type: application/json');

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

// Carga de variables de entorno (si usas .env y phpdotenv, pero en Render esto no es necesario)
$host = getenv('DB_HOST') ?: 'localhost';
$db = getenv('DB_DATABASE') ?: 'acomer';
$user = getenv('DB_USERNAME') ?: 'root';
$pass = getenv('DB_PASSWORD') ?: '';
$port = getenv('DB_PORT') ?: '3306';
$charset = 'utf8mb4';

// Construcción del DSN
$dsn = "mysql:host=$host;port=$port;dbname=$db;charset=$charset";

$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);

    // Si quieres confirmar que la conexión fue exitosa, puedes dejar este log (solo para pruebas)
    // echo json_encode(["success" => true, "message" => "Conexión establecida correctamente."]);
} catch (PDOException $e) {
    // Mostrar el mensaje exacto del error en la respuesta JSON para depurar
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Error de conexión a la base de datos',
        'error' => $e->getMessage()  // Esto te mostrará el error real en Postman o consola
    ]);
    exit;
}
?>
