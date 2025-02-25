<?php
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: http://localhost:5173'); // Ajusta el dominio si es necesario
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');
header('Access-Control-Allow-Credentials: true');

require 'conexion.php';  // Asegúrate de que este archivo contiene la conexión PDO correcta

// Verificar la conexión a la base de datos
if (!$pdo) {
    echo json_encode(['error' => 'Error de conexión a la base de datos.']);
    exit;
}

// Obtener acción y parámetros de la solicitud
$action      = $_POST['action'] ?? '';
$tipomenu = $_POST['tipomenu'] ?? ''; // Se espera: 'desayuno', 'almuerzo' o 'refrigerio'
$descripcion = $_POST['descripcion'] ?? '';
$fecha       = $_POST['fecha'] ?? '';
$idmenu      = $_POST['idmenu'] ?? 0; // Para acciones de update y delete

try {
    switch ($action) {

        case 'create':
            // Verifica que los parámetros necesarios estén presentes
            if (empty($tipomenu) || empty($descripcion) || empty($fecha)) {
                echo json_encode(['error' => 'Todos los campos son obligatorios.']);
                exit;
            }

            // Consulta SQL para insertar un nuevo menú
            $query = "INSERT INTO menu (tipomenu, descripcion, fecha) VALUES (:tipomenu, :descripcion, :fecha)";
            $stmt = $pdo->prepare($query);
            $stmt->bindParam(':tipomenu', $tipomenu);
            $stmt->bindParam(':descripcion', $descripcion);
            $stmt->bindParam(':fecha', $fecha);
            $stmt->execute();

            echo json_encode(['success' => 'Menú creado exitosamente.']);
            break;

        case 'read':
            $tipomenu = $_POST['tipomenu'] ?? '';
            $tiposPermitidos = ['desayuno', 'almuerzo', 'refrigerio'];
            
            if (!empty($tipomenu)) {
                if (!in_array($tipomenu, $tiposPermitidos)) {
                    echo json_encode(['error' => 'Tipo de menú no válido.']);
                    exit;
                }
                $query = "SELECT * FROM menu WHERE tipomenu = :tipomenu ORDER BY fecha DESC";
                $stmt = $pdo->prepare($query);
                $stmt->bindParam(':tipomenu', $tipomenu);
            } else {
                // Cuando no viene tipomenu, obtener todos
                $query = "SELECT * FROM menu ORDER BY fecha DESC";
                $stmt = $pdo->prepare($query);
            }
            
            if ($stmt->execute()) {
                $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
                echo json_encode($result);
            } else {
                echo json_encode(['error' => 'Error al obtener menús']);
            }
            break;

        case 'update':
            // Verifica que los parámetros estén presentes
            if (empty($idmenu) || empty($descripcion) || empty($fecha)) {
                echo json_encode(['error' => 'Todos los campos son obligatorios.']);
                exit;
            }

            // Consulta SQL para actualizar el menú
            $query = "UPDATE menu SET descripcion = :descripcion, fecha = :fecha WHERE idmenu = :idmenu";
            $stmt = $pdo->prepare($query);
            $stmt->bindParam(':descripcion', $descripcion);
            $stmt->bindParam(':fecha', $fecha);
            $stmt->bindParam(':idmenu', $idmenu);
            $stmt->execute();

            echo json_encode(['success' => 'Menú actualizado exitosamente.']);
            break;

        case 'delete':
            // Verifica que el ID de menú esté presente
            if (empty($idmenu)) {
                echo json_encode(['error' => 'ID de menú es obligatorio.']);
                exit;
            }

            // Consulta SQL para eliminar el menú
            $query = "DELETE FROM menu WHERE idmenu = :idmenu";
            $stmt = $pdo->prepare($query);
            $stmt->bindParam(':idmenu', $idmenu);
            $stmt->execute();

            echo json_encode(['success' => 'Menú eliminado exitosamente.']);
            break;

        default:
            echo json_encode(['error' => 'Acción no válida.']);
            break;
    }
} catch (PDOException $e) {
    // Captura errores de la base de datos
    echo json_encode(['error' => 'Error de conexión o ejecución de la consulta: ' . $e->getMessage()]);
}
?>
