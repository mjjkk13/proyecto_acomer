<?php
<<<<<<< HEAD

// --- Encabezados CORS obligatorios
require 'cors.php';

// --- Manejo de preflight OPTIONS
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204); // No Content
    exit;
}

// --- Requiere conexión
require 'conexion.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// 🔒 Cookies seguras con sesiones y CORS
ini_set('session.cookie_samesite', 'None');
ini_set('session.cookie_secure', '1');

// --- Iniciar sesión
session_start();
error_log("=== INICIO login.php ===");

header('Content-Type: application/json; charset=utf-8');

// --- Función para responder con JSON
function sendJsonResponse($success, $message, $data = []) {
    $response = array_merge(['success' => $success, 'message' => $message], $data);
    echo json_encode($response);
    error_log("RESPUESTA: " . json_encode($response));
    exit;
}

// --- Validar método
=======
require_once 'conexion.php';
$pdo = getPDO(); // ← Aquí se crea la conexión con PDO

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

// --- Configuración CORS
$allowed_origins = [
    'http://localhost:5173',
    'https://acomer.onrender.com'
];

$origin = $_SERVER['HTTP_ORIGIN'] ?? '';
if (in_array($origin, $allowed_origins)) {
    header("Access-Control-Allow-Origin: $origin");
    header('Access-Control-Allow-Credentials: true');
    header('Access-Control-Allow-Headers: Content-Type, Authorization');
    header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
    header('Access-Control-Max-Age: 86400');
}

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}

header('Content-Type: application/json; charset=utf-8');

// --- Configuración de errores
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// --- Cookies seguras
ini_set('session.cookie_samesite', 'None');
ini_set('session.cookie_secure', '1');

// Aseguramos la configuración adecuada de la cookie de sesión
session_set_cookie_params([
    'lifetime' => 0,
    'path' => '/',
    'domain' => 'acomer.onrender.com', 
    'secure' => true,
    'httponly' => true,
    'samesite' => 'None',
]);
// --- Iniciar sesión
session_start();
error_log("=== INICIO login.php ===");

// --- Función para respuesta JSON
function sendJsonResponse($success, $message, $data = []) {
    $response = array_merge(['success' => $success, 'message' => $message], $data);
    echo json_encode($response);
    error_log("RESPUESTA: " . json_encode($response));
    exit;
}

// --- Verificar método
>>>>>>> main
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    sendJsonResponse(false, 'Método no permitido');
}

// --- Obtener datos del formulario
$usuario = $_POST['usuario'] ?? null;
$contrasena = $_POST['inputPassword'] ?? null;
error_log("Credenciales recibidas: usuario=$usuario");

if (!$usuario || !$contrasena) {
    sendJsonResponse(false, 'Por favor, complete todos los campos');
}

try {
    // --- Consulta de usuario
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
        http_response_code(400);
        sendJsonResponse(false, 'Usuario no encontrado');
    }

    if ((int)$result['estado'] === 0) {
        http_response_code(401);
        sendJsonResponse(false, 'Este usuario está inactivo. Contacte al administrador.');
    }

    if (!password_verify($contrasena, $result['contrasena'])) {
        http_response_code(401);
        sendJsonResponse(false, 'Contraseña incorrecta');
    }

    // --- Guardar sesión
    $_SESSION['usuario'] = $usuario;
    $_SESSION['rol'] = $result['rol'];
    $_SESSION['idusuarios'] = $result['idusuarios'];
<<<<<<< HEAD
    error_log("Session ID: " . session_id());
    error_log("Sesión guardada: " . print_r($_SESSION, true));

    // --- Actualizar último acceso
    $pdo->prepare("UPDATE credenciales SET ultimoacceso = NOW() WHERE user = :user")
        ->execute(['user' => $usuario]);

    // --- Redirección por rol
=======

    if ($result['rol'] === 'Docente') {
        $_SESSION['docente_id'] = $result['idusuarios'];
    }

    error_log("Session ID: " . session_id());
    error_log("Sesión guardada: " . print_r($_SESSION, true));

    // --- Actualizar último acceso
    $pdo->prepare("UPDATE credenciales SET ultimoacceso = NOW() WHERE user = :user")
        ->execute(['user' => $usuario]);

    // --- Redirección según rol
>>>>>>> main
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
    error_log("Error de base de datos: " . $e->getMessage());
<<<<<<< HEAD
    echo json_encode([
        'success' => false,
        'message' => 'Error en la base de datos: ' . $e->getMessage()
    ]);
    exit;
=======
    sendJsonResponse(false, 'Error en la base de datos. Por favor, intente más tarde.');
} catch (Exception $e) {
    http_response_code(500);
    error_log("Error general: " . $e->getMessage());
    sendJsonResponse(false, 'Error en el servidor. Por favor, intente más tarde.');
>>>>>>> main
}
