<?php
/**
 * @OA\Post(
 *     path="/login",
 *     summary="Iniciar sesión",
 *     description="Este endpoint permite a los usuarios iniciar sesión utilizando su nombre de usuario y contraseña.",
 *     tags={"Autenticación"},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             type="object",
 *             required={"usuario", "inputPassword"},
 *             @OA\Property(property="usuario", type="string", example="johndoe"),
 *             @OA\Property(property="inputPassword", type="string", example="password123")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Login exitoso",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="success", type="boolean", example=true),
 *             @OA\Property(property="message", type="string", example="Login exitoso"),
 *             @OA\Property(property="rol", type="string", example="Docente"),
 *             @OA\Property(property="redirect_url", type="string", example="/docente")
 *         )
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Error de validación de datos o usuario no encontrado",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="success", type="boolean", example=false),
 *             @OA\Property(property="message", type="string", example="Usuario no encontrado")
 *         )
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="Contraseña incorrecta o usuario inactivo",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="success", type="boolean", example=false),
 *             @OA\Property(property="message", type="string", example="Contraseña incorrecta")
 *         )
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Error en la base de datos o en el servidor",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="success", type="boolean", example=false),
 *             @OA\Property(property="message", type="string", example="Error en la base de datos. Por favor, intente más tarde.")
 *         )
 *     )
 * )
 */

require 'conexion.php';
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// 🔒 Importante para que las cookies se guarden bien en navegadores modernos con CORS + HTTPS
ini_set('session.cookie_samesite', 'None');
ini_set('session.cookie_secure', '1'); // Render usa HTTPS

session_start();

// CORS dinámico
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
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}

function sendJsonResponse($success, $message, $data = []) {
    echo json_encode(array_merge(['success' => $success, 'message' => $message], $data));
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    sendJsonResponse(false, 'Método no permitido');
}

$usuario = $_POST['usuario'] ?? null;
$contrasena = $_POST['inputPassword'] ?? null;

if (!$usuario || !$contrasena) {
    sendJsonResponse(false, 'Por favor, complete todos los campos');
}

try {
    $stmt = $pdo->prepare("
        SELECT u.idusuarios, c.user, c.contrasena, c.estado, tu.rol
        FROM credenciales c
        JOIN usuarios u ON c.idcredenciales = u.credenciales
        JOIN tipo_usuario tu ON u.tipo_usuario = tu.idtipo_usuario
        WHERE c.user = :user
    ");
    $stmt->execute(['user' => $usuario]);
    $result = $stmt->fetch();

    if (!$result) {
        sendJsonResponse(false, 'Usuario no encontrado');
    }

    if ((int)$result['estado'] === 0) {
        sendJsonResponse(false, 'Este usuario está inactivo. Contacte al administrador.');
    }

    if (!password_verify($contrasena, $result['contrasena'])) {
        sendJsonResponse(false, 'Contraseña incorrecta');
    }

    // 🔐 Guardar en la sesión
    $_SESSION['usuario'] = $usuario;
    $_SESSION['rol'] = $result['rol'];
    $_SESSION['idusuarios'] = $result['idusuarios'];

    // ⏰ Actualizar último acceso
    $pdo->prepare("UPDATE credenciales SET ultimoacceso = NOW() WHERE user = :user")
        ->execute(['user' => $usuario]);

    // Redirección basada en rol
    $redirect_url = match($result['rol']) {
        'Administrador' => '/admin',
        'Estudiante SS' => '/estudiante',
        'Docente' => '/docente',
        default => '/'
    };

    sendJsonResponse(true, 'Login exitoso', [
        'rol' => $result['rol'],
        'redirect_url' => $redirect_url
    ]);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Error en la base de datos: ' . $e->getMessage()
    ]);
    exit;
}
