<?php
require 'conexion.php';
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Requisitos cookies seguras para CORS
ini_set('session.cookie_samesite', 'None');
ini_set('session.cookie_secure', '1'); // HTTPS obligatorio en Render

// CORS
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

// Aquí sí inicias sesión
session_start();
error_log("=== INICIO login.php ===");

// El resto igual...

