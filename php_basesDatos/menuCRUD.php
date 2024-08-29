<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

include('conexion.php');

$action = $_POST['action'] ?? '';
$mealType = $_POST['mealType'] ?? ''; // Obtener el tipo de menú desde POST

try {
    $conn = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($action === 'create') {
        // CREATE - Insertar un nuevo registro
        $descripcion = $_POST['descripcion'] ?? '';
        $fecha = $_POST['fecha'] ?? date('Y-m-d');
        $sql = "INSERT INTO menu (tipomenu, fecha, descripcion) VALUES (:mealType, :fecha, :descripcion)";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':mealType', $mealType, PDO::PARAM_STR);
        $stmt->bindParam(':fecha', $fecha, PDO::PARAM_STR);
        $stmt->bindParam(':descripcion', $descripcion, PDO::PARAM_STR);
        $stmt->execute();
        echo json_encode(['success' => 'Menú creado correctamente']);
    } elseif ($action === 'update') {
        // UPDATE - Actualizar un registro existente
        $id = $_POST['idmenu'] ?? 0;
        $descripcion = $_POST['descripcion'] ?? '';
        $sql = "UPDATE menu SET descripcion = :descripcion WHERE idmenu = :idmenu";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':descripcion', $descripcion, PDO::PARAM_STR);
        $stmt->bindParam(':idmenu', $id, PDO::PARAM_INT);
        $stmt->execute();
        echo json_encode(['success' => 'Menú actualizado correctamente']);
    } elseif ($action === 'delete') {
        // DELETE - Eliminar un registro existente
        $id = $_POST['idmenu'] ?? 0;
        $sql = "DELETE FROM menu WHERE idmenu = :idmenu";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':idmenu', $id, PDO::PARAM_INT);
        $stmt->execute();
        echo json_encode(['success' => 'Menú eliminado correctamente']);
    } elseif ($mealType !== '') {
        // READ - Consultar los registros para un tipo de menú específico
        $sql = "SELECT idmenu, tipomenu, fecha, descripcion FROM menu WHERE tipomenu = :mealType ORDER BY fecha DESC";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':mealType', $mealType, PDO::PARAM_STR);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($result);
    } else {
        echo json_encode(['error' => 'Acción no reconocida o tipo de menú no especificado']);
    }
} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}

?>
