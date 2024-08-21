<?php
header('Access-Control-Allow-Origin: *');

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "control_estudiante";

// Crear la conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar la conexión
if ($conn->connect_error) {
  die("Conexión fallida: " . $conn->connect_error);
}

// Obtener los datos de la tabla refrigerio
$sql = "SELECT * FROM refrigerio";
$result = $conn->query($sql);

$refrigerio = array();
if ($result->num_rows > 0) {
  while($row = $result->fetch_assoc()) {
    $refrigerio[] = $row;
  }
} else {
  echo "0 resultados";
}
$conn->close();

// Devolver los datos en formato JSON
header('Content-Type: application/json');
echo json_encode($refrigerio);
?>
