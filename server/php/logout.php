<?php
// Asegurar que la respuesta sea siempre JSON
header('Content-Type: application/json; charset=utf-8');
require 'cors.php';


session_start();
session_destroy();

header('Content-Type: application/json');
echo json_encode(['success' => true]);
?>
