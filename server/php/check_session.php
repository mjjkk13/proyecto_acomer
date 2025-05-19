<?php
/**
 * @OA\Get(
 *     path="/session",
 *     tags={"Sesión"},
 *     summary="Verificar sesión activa",
 *     description="Verifica si el usuario tiene una sesión activa. Si es así, devuelve los detalles del usuario y su rol.",
 *     @OA\Response(
 *         response=200,
 *         description="Sesión activa, detalles del usuario y rol",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(
 *                 property="success",
 *                 type="boolean",
 *                 example=true
 *             ),
 *             @OA\Property(
 *                 property="usuario",
 *                 type="string",
 *                 example="juan.perez"
 *             ),
 *             @OA\Property(
 *                 property="rol",
 *                 type="string",
 *                 example="Administrador"
 *             ),
 *             @OA\Property(
 *                 property="message",
 *                 type="string",
 *                 example="Inicio de sesión exitoso"
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="No hay sesión activa",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(
 *                 property="success",
 *                 type="boolean",
 *                 example=false
 *             ),
 *             @OA\Property(
 *                 property="message",
 *                 type="string",
 *                 example="No hay sesión activa"
 *             )
 *         )
 *     )
 * )
 */
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$allowed_origins = [
    'http://localhost:5173',
    'https://acomer.onrender.com'
];

$origin = $_SERVER['HTTP_ORIGIN'] ?? '';
if (in_array($origin, $allowed_origins)) {
    header("Access-Control-Allow-Origin: $origin");
    header("Access-Control-Allow-Credentials: true");
}

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Methods: GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

session_start();

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}

if (isset($_SESSION['usuario']) && isset($_SESSION['rol'])) {
    echo json_encode([
        'success' => true,
        'usuario' => $_SESSION['usuario'],
        'rol' => $_SESSION['rol'],
        'message' => 'Inicio de sesión exitoso'
    ]);
} else {
    echo json_encode([
        'success' => false,
        'message' => 'No hay sesión activa'
    ]);
}
?>
