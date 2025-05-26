<?php
require 'conexion.php';
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// 🔒 Requisitos de cookies seguras para CORS
ini_set('session.cookie_samesite', 'None');
ini_set('session.cookie_secure', '1'); // Render usa HTTPS

// --- Iniciar sesión
session_start();
error_log("=== INICIO login.php ===");

// --- Validar y registrar ORIGIN
$allowed_origins = [
    'http://localhost:5173',
    'https://acomer.onrender.com'
];

$origin = $_SERVER['HTTP_ORIGIN'] ?? 'NO_ORIGIN';
error_log("Origin recibido: $origin");

if (in_array($origin, $allowed_origins)) {
    header("Access-Control-Allow-Origin: $origin");
    error_log("Access-Control-Allow-Origin seteado: $origin");
} else {
    header("Access-Control-Allow-Origin: https://acomer.onrender.com");
    error_log("Origin no permitido. Forzado a acomer.onrender.com");
}

header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    error_log("Petición OPTIONS recibida. 204 enviado.");
    http_response_code(204);
    exit;
}

function sendJsonResponse($success, $message, $data = []) {
    echo json_encode(array_merge(['success' => $success, 'message' => $message], $data));
    error_log("RESPUESTA: " . json_encode(array_merge(['success' => $success, 'message' => $message], $data)));
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    sendJsonResponse(false, 'Método no permitido');
}

$usuario = $_POST['usuario'] ?? null;
$contrasena = $_POST['inputPassword'] ?? null;
error_log("Credenciales recibidas: usuario=$usuario");

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

    // --- Guardar sesión ---
    $_SESSION['usuario'] = $usuario;
    $_SESSION['rol'] = $result['rol'];
    $_SESSION['idusuarios'] = $result['idusuarios'];
    error_log("Session ID: " . session_id());
    error_log("Sesión guardada: " . print_r($_SESSION, true));

    // --- Actualizar último acceso ---
    $pdo->prepare("UPDATE credenciales SET ultimoacceso = NOW() WHERE user = :user")
        ->execute(['user' => $usuario]);

    // --- Redirección según rol ---
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
