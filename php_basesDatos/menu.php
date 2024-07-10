<?php
include 'conexion.php';
header('Access-Control-Allow-Origin: *');



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
