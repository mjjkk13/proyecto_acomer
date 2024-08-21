<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "acomer";

// Crear la conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

$mealType = $_GET['mealType'] ?? '';
$mealType = $conn->real_escape_string($mealType); 

// Verifica si el tipo de comida es 'desayuno', 'almuerzo' o 'refrigerio'
$sql = "SELECT nombreMenu, diaMenu, caracteristicasMenu FROM menu WHERE nombreMenu = '$mealType'";

$result = $conn->query($sql);

if ($result === false) {
    echo json_encode(['error' => 'Error en la consulta SQL: ' . $conn->error]);
    exit();
}

if ($result->num_rows > 0) {
    $menu = array();
    while($row = $result->fetch_assoc()) {
        $menu[] = $row;
    }
    echo json_encode($menu);
} else {
    echo json_encode([]); // Devuelve un array vacío si no hay resultados
}

$conn->close();
?>
