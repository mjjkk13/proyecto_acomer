<?php
$servername = "localhost";
$username = "root";
$password = "toor";
$dbname = "acomer";

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
  die("Conexión fallida: " . $conn->connect_error);
}
?>
