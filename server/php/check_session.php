<?php
session_start();
header('Content-Type: application/json');
header('Access-Control-Allow-Credentials: true');
header('Access-Control-Allow-Origin: http://localhost:5173'); // Ajusta según tu dominio

if (isset($_SESSION['usuario']) && isset($_SESSION['rol'])) {
    echo json_encode([
        'success' => true,
        'usuario' => $_SESSION['usuario'],
        'rol' => $_SESSION['rol'],
        'message' => 'Inicio de sesión exitoso'
    ]);
} else {
    echo json_encode([
        'success' => false,
        'message' => 'No hay sesión activa'
    ]);
}
?>