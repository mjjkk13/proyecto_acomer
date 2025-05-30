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

$host = 'localhost';
$db = 'acomer';
$user = 'root';
$pass = ''; 
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
    error_log("Database error: " . $e->getMessage());
    die("Error de conexión a la base de datos");
}
?>
