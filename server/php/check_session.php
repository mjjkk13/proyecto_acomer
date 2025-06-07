<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// --- Logging para depuración ---
error_log("=== INICIO check_session.php ===");

$allowed_origins = [
    'http://localhost:5173',
    'https://acomer.onrender.com'
];

// --- Validación del Origin ---
$origin = $_SERVER['HTTP_ORIGIN'] ?? 'NO_ORIGIN';
error_log("Origin recibido: $origin");

if (in_array($origin, $allowed_origins)) {
    header("Access-Control-Allow-Origin: $origin");
    error_log("Access-Control-Allow-Origin seteado: $origin");
} else {
    // Para detectar si no coincide, igual seteamos uno seguro para ver si cambia algo
    header("Access-Control-Allow-Origin: https://acomer.onrender.com");
    error_log("Origin NO permitido. Forzando header a acomer.onrender.com");
}

// --- Headers CORS ---
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json; charset=utf-8');
require 'cors.php';

// --- Iniciar sesión ---
session_start();
error_log("Session ID: " . session_id());
error_log("Session data: " . print_r($_SESSION, true));

// --- OPTIONS ---
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    error_log("Petición OPTIONS recibida. Finalizando con 204.");
    http_response_code(204);
    exit;
}

// --- Validar sesión ---
if (isset($_SESSION['usuario']) && isset($_SESSION['rol'])) {
    $response = [
        'success' => true,
        'usuario' => $_SESSION['usuario'],
        'rol' => $_SESSION['rol'],
        'message' => 'Inicio de sesión exitoso'
    ];
    echo json_encode($response);
    error_log("Sesión activa: " . json_encode($response));
} else {
    $response = [
        'success' => false,
        'message' => 'No hay sesión activa'
    ];
    echo json_encode($response);
    error_log("No hay sesión activa.");
}

error_log("=== FIN check_session.php ===");
?>
