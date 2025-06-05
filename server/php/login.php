<?php

// --- Lista de orígenes permitidos
require_once 'cors.php';


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
        sendJsonResponse(false, 'Usuario no encontrado');
    }

    if ((int)$result['estado'] === 0) {
        sendJsonResponse(false, 'Este usuario está inactivo. Contacte al administrador.');
    }

    if (!password_verify($contrasena, $result['contrasena'])) {
        sendJsonResponse(false, 'Contraseña incorrecta');
    }

    // --- Guardar sesión
    $_SESSION['usuario'] = $usuario;
    $_SESSION['rol'] = $result['rol'];
    $_SESSION['idusuarios'] = $result['idusuarios'];
    error_log("Session ID: " . session_id());
    error_log("Sesión guardada: " . print_r($_SESSION, true));

    // --- Actualizar último acceso
    $pdo->prepare("UPDATE credenciales SET ultimoacceso = NOW() WHERE user = :user")
        ->execute(['user' => $usuario]);

    // --- Redirección por rol
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
    echo json_encode([
        'success' => false,
        'message' => 'Error en la base de datos: ' . $e->getMessage()
    ]);
    exit;
}
