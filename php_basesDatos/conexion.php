<?php
// Configuración de la base de datos
$host = 'localhost';
$db = 'acomer';
$user = 'root';
$pass = 'toor';
$charset = 'utf8mb4';

// Configuración de DSN (Data Source Name)
$dsn = "mysql:host=$host;dbname=$db;charset=$charset";

// Opciones de PDO
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    // Crear una instancia de PDO
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
    // Manejar el error de conexión
    throw new PDOException($e->getMessage(), (int)$e->getCode());
}
?>
