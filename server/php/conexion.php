<?php
header('Content-Type: application/json');

function getPDO() {
    $host = getenv('DB_HOST') ?: 'localhost';
    $db = getenv('DB_DATABASE') ?: 'acomer';
    $user = getenv('DB_USERNAME') ?: 'root';
    $pass = getenv('DB_PASSWORD') ?: '';
    $port = getenv('DB_PORT') ?: '3306';
    $charset = 'utf8mb4';

    $dsn = "mysql:host=$host;port=$port;dbname=$db;charset=$charset";

    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ];

    try {
        return new PDO($dsn, $user, $pass, $options);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode([
            'success' => false,
            'message' => 'Error de conexión a la base de datos',
            'error' => $e->getMessage()
        ]);
        exit;
    }
}
?>
