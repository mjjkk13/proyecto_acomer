<?php
<<<<<<< HEAD
// --- Lista de orígenes permitidos
=======
// --- Configuración CORS centralizada ---
>>>>>>> main
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
<<<<<<< HEAD
    header('Access-Control-Max-Age: 86400'); // cache 1 día
}

// --- Manejo de preflight OPTIONS
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204); // No Content
    exit;
}

// 🔒 Cookies seguras con sesiones y CORS
ini_set('session.cookie_samesite', 'None');
ini_set('session.cookie_secure', '1');
=======
    header('Access-Control-Max-Age: 86400');
    header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With, Cache-Control");

}

// --- Preflight
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}
>>>>>>> main
