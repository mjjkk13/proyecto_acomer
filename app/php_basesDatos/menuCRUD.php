<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'C:/xampp/htdocs/Proyecto/core/database.php';

$action = $_POST['action'] ?? '';
$mealType = $_POST['mealType'] ?? ''; // Obtener el tipo de menú desde POST

try {
    $database = new Database();
    $pdo = $database->getConnection();

    switch ($action) {
        case 'create':
            $descripcion = $_POST['descripcion'] ?? '';
            $fecha = $_POST['fecha'] ?? date('Y-m-d');
            $query = "INSERT INTO menu (tipomenu, fecha, descripcion) VALUES (:mealType, :fecha, :descripcion)";
            $stmt = $conn->prepare($query);
            $stmt->execute(['mealType' => $mealType, 'fecha' => $fecha, 'descripcion' => $descripcion]);
            echo json_encode(['success' => 'Menú creado exitosamente.']);
            break;

        case 'read':
            $query = "SELECT idmenu, tipomenu, fecha, descripcion FROM menu WHERE tipomenu = :mealType ORDER BY fecha DESC";
            $stmt = $conn->prepare($query);
            $stmt->execute(['mealType' => $mealType]);
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode($data);
            break;

        case 'update':
            $idmenu = $_POST['idmenu'] ?? 0;
            $descripcion = $_POST['descripcion'] ?? '';
            $fecha = $_POST['fecha'] ?? date('Y-m-d');
            $query = "UPDATE menu SET descripcion = :descripcion, fecha = :fecha WHERE idmenu = :idmenu";
            $stmt = $conn->prepare($query);
            $stmt->execute(['descripcion' => $descripcion, 'fecha' => $fecha, 'idmenu' => $idmenu]);
            echo json_encode(['success' => 'Menú actualizado exitosamente.']);
            break;

        case 'delete':
            $idmenu = $_POST['idmenu'] ?? 0;
            $query = "DELETE FROM menu WHERE idmenu = :idmenu";
            $stmt = $conn->prepare($query);
            $stmt->execute(['idmenu' => $idmenu]);
            echo json_encode(['success' => 'Menú eliminado exitosamente.']);
            break;

        default:
            echo json_encode(['error' => 'Acción no válida.']);
            break;
    }
} catch (PDOException $e) {
    echo json_encode(['error' => 'Error de conexión: ' . $e->getMessage()]);
}
?>
