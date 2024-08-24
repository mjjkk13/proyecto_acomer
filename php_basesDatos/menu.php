<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

include('conexion.php');

$mealType = $_GET['mealType'] ?? '';

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Preparar la consulta SQL para evitar inyecciones SQL
    $sql = "SELECT nombreMenu, diaMenu, caracteristicasMenu FROM menu WHERE nombreMenu = :mealType";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':mealType', $mealType, PDO::PARAM_STR);
    $stmt->execute();

    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if ($result) {
        echo json_encode($result);
    } else {
        echo json_encode([]); // Devuelve un array vacío si no hay resultados
    }
} catch (PDOException $e) {
    echo json_encode(['error' => 'Error en la consulta SQL: ' . $e->getMessage()]);
}

$conn = null;
?>
