<?php

// --- Configuración de errores
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// --- Configuración de cookies y sesión (antes de session_start)
ini_set('session.cookie_samesite', 'None');
ini_set('session.cookie_secure', '1');


// --- Iniciar sesión
session_start();
error_log("=== INICIO login.php ===");

require_once 'conexion.php';
$pdo = getPDO();

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

// --- Función para respuesta JSON
function sendJsonResponse($success, $message, $data = []) {
    $response = array_merge(['success' => $success, 'message' => $message], $data);
    echo json_encode($response);
    error_log("RESPUESTA: " . json_encode($response));
    exit;
}

// --- Verificar método
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

    if ($result['rol'] === 'Administrador') {
        $_SESSION['admin_id'] = $result['idusuarios'];
    }elseif($result['rol'] === 'Docente') {
        $_SESSION['docente_id'] = $result['idusuarios'];
    }

    error_log("Session ID: " . session_id());
    error_log("Sesión guardada: " . print_r($_SESSION, true));

    // --- Actualizar último acceso
    // Configura la zona horaria de Bogotá
    date_default_timezone_set('America/Bogota');

    // Obtén la fecha y hora actual en Bogotá
    $fechaBogota = date('Y-m-d H:i:s');

    // Prepara y ejecuta la consulta pasando la fecha desde PHP
    $pdo->prepare("UPDATE credenciales SET ultimoacceso = :fecha WHERE user = :user")
        ->execute(['fecha' => $fechaBogota, 'user' => $usuario]);


    // --- Redirección según rol
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
    sendJsonResponse(false, 'Error en la base de datos. Por favor, intente más tarde.');
} catch (Exception $e) {
    http_response_code(500);
    error_log("Error general: " . $e->getMessage());
    sendJsonResponse(false, 'Error en el servidor. Por favor, intente más tarde.');
}
