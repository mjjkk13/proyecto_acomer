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
    'domain' => '.acomer.onrender.com', // Dominio con punto inicial para subdominios
    'secure' => true,
    'httponly' => true,
    'samesite' => 'None'
]);

// --- Logging para depuración ---
error_log("=== INICIO check_session.php ===");

// --- Incluir configuración CORS
require 'cors.php';

// --- Headers adicionales para CORS ---
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json; charset=utf-8');

// --- OPTIONS ---
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    error_log("Petición OPTIONS recibida. Finalizando con 204.");
    http_response_code(204);
    exit;
}

// --- Iniciar sesión ---
session_start();
error_log("Session ID: " . session_id());
error_log("Session data: " . print_r($_SESSION, true));

// --- Validar sesión ---
if (isset($_SESSION['usuario']) && isset($_SESSION['rol']) && isset($_SESSION['loggedin'])) {
    $response = [
        'success' => true,
        'usuario' => $_SESSION['usuario'],
        'rol' => $_SESSION['rol'],
        'message' => 'Sesión activa'
    ];
    
    // Añadir admin_id si está presente
    if (isset($_SESSION['admin_id'])) {
        $response['admin_id'] = $_SESSION['admin_id'];
    }
    
    // Añadir docente_id si está presente
    if (isset($_SESSION['docente_id'])) {
        $response['docente_id'] = $_SESSION['docente_id'];
    }
    
    // Añadir idusuarios si está presente
    if (isset($_SESSION['idusuarios'])) {
        $response['idusuarios'] = $_SESSION['idusuarios'];
    }
    
    echo json_encode($response);
    error_log("Sesión activa: " . json_encode($response));
} else {
    $response = [
        'success' => false,
        'message' => 'No hay sesión activa'
    ];
    http_response_code(401); // Código de estado no autorizado
    echo json_encode($response);
    error_log("No hay sesión activa.");
}

error_log("=== FIN check_session.php ===");
?>