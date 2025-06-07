<?php

// --- Configuración de errores
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// --- Configuración de cookies y sesión (antes de session_start)
ini_set('session.cookie_samesite', 'None');
ini_set('session.cookie_secure', '1');
ini_set('session.cookie_httponly', '1');
ini_set('session.use_strict_mode', '1');

// Configuración mejorada de cookies para producción
session_set_cookie_params([
    'lifetime' => 86400, // 1 día
    'path' => '/',
    'domain' => 'acomer.onrender.com', // Dominio con punto inicial para subdominios
    'secure' => true,
    'httponly' => true,
    'samesite' => 'None'
]);

// --- Iniciar sesión
session_start();
error_log("=== INICIO login.php ===");
error_log("Session ID: " . session_id());
error_log("Session Cookie Params: " . print_r(session_get_cookie_params(), true));

require_once 'conexion.php';
$pdo = getPDO();

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

// --- Configuración CORS mejorada
$allowed_origins = [
    'http://localhost:5173',
    'https://acomer.onrender.com'
];

$origin = $_SERVER['HTTP_ORIGIN'] ?? '';
if (in_array($origin, $allowed_origins)) {
    header("Access-Control-Allow-Origin: $origin");
    header('Access-Control-Allow-Credentials: true');
    header('Access-Control-Expose-Headers: Set-Cookie'); // Importante para cookies
    header('Access-Control-Allow-Headers: Content-Type, Authorization');
    header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
    header('Access-Control-Max-Age: 86400');
}

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}

header('Content-Type: application/json; charset=utf-8');

// --- Función para respuesta JSON
function sendJsonResponse($success, $message, $data = []) {
    $response = array_merge(['success' => $success, 'message' => $message], $data);
    echo json_encode($response);
    error_log("RESPUESTA: " . json_encode($response));
    exit;
}

// --- Verificar método
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    sendJsonResponse(false, 'Método no permitido');
}

// --- Obtener datos del formulario
$usuario = $_POST['usuario'] ?? null;
$contrasena = $_POST['inputPassword'] ?? null;
error_log("Credenciales recibidas: usuario=$usuario");

if (!$usuario || !$contrasena) {
    http_response_code(400);
    sendJsonResponse(false, 'Por favor, complete todos los campos');
}

try {
    // --- Consulta de usuario ORIGINAL
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

    // --- Guardar sesión con información básica
    $_SESSION['usuario'] = $result['user'];
    $_SESSION['rol'] = $result['rol'];
    $_SESSION['idusuarios'] = $result['idusuarios'];

    // --- Obtener IDs específicos según el rol
    if ($result['rol'] === 'Administrador') {
        // Consulta ORIGINAL para obtener ID de administrador
        $adminStmt = $pdo->prepare("SELECT idadmin FROM admin WHERE usuarios_idusuarios = :usuario_id");
        $adminStmt->execute(['usuario_id' => $result['idusuarios']]);
        $adminData = $adminStmt->fetch();
        
        if ($adminData) {
            $_SESSION['admin_id'] = $adminData['idadmin'];
        }
    }
    
    if ($result['rol'] === 'Docente') {
        // Consulta ORIGINAL para obtener ID de docente
        $docenteStmt = $pdo->prepare("SELECT iddocentes FROM docentes WHERE usuarios_idusuarios = :usuario_id");
        $docenteStmt->execute(['usuario_id' => $result['idusuarios']]);
        $docenteData = $docenteStmt->fetch();
        
        if ($docenteData) {
            $_SESSION['docente_id'] = $docenteData['iddocentes'];
        }
    }

    error_log("Session ID: " . session_id());
    error_log("Sesión guardada: " . print_r($_SESSION, true));

    // --- Actualizar último acceso ORIGINAL
    $pdo->prepare("UPDATE credenciales SET ultimoacceso = NOW() WHERE user = :user")
        ->execute(['user' => $usuario]);

    // --- Redirección según rol ORIGINAL
    $redirect_url = match($result['rol']) {
        'Administrador' => '/admin',
        'Estudiante SS' => '/estudiante',
        'Docente' => '/docente',
        default => '/'
    };

    // Preparar datos de respuesta ORIGINAL más IDs específicos si existen
    $responseData = [
        'rol' => $result['rol'],
        'redirect_url' => $redirect_url
    ];

    // Añadir IDs específicos si están disponibles
    if ($result['rol'] === 'Administrador' && isset($_SESSION['admin_id'])) {
        $responseData['admin_id'] = $_SESSION['admin_id'];
    } elseif ($result['rol'] === 'Docente' && isset($_SESSION['docente_id'])) {
        $responseData['docente_id'] = $_SESSION['docente_id'];
    }

    sendJsonResponse(true, 'Login exitoso', $responseData);

} catch (PDOException $e) {
    http_response_code(500);
    error_log("Error de base de datos: " . $e->getMessage());
    sendJsonResponse(false, 'Error en la base de datos. Por favor, intente más tarde.');
} catch (Exception $e) {
    http_response_code(500);
    error_log("Error general: " . $e->getMessage());
    sendJsonResponse(false, 'Error en el servidor. Por favor, intente más tarde.');
}