<?php
session_start();

header('Content-Type: application/json; charset=utf-8');
require 'cors.php'; // Habilita CORS si es necesario

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

require_once 'conexion.php';
$pdo = getPDO();

// Función para obtener datos del cuerpo JSON o POST
function getInputData() {
    $contentType = $_SERVER['CONTENT_TYPE'] ?? '';
    if (stripos($contentType, 'application/json') !== false) {
        return json_decode(file_get_contents('php://input'), true);
    }
    return $_POST;
}

try {
    $data = getInputData();

    if (empty($data['action'])) {
        throw new Exception('El parámetro "action" es requerido');
    }

    switch ($data['action']) {
        case 'read':
            $tipomenu = $data['tipomenu'] ?? null;
            $stmt = $tipomenu
                ? $pdo->prepare("SELECT * FROM menu WHERE tipomenu = ? ORDER BY fecha DESC")
                : $pdo->prepare("SELECT * FROM menu ORDER BY fecha DESC");

            $stmt->execute($tipomenu ? [$tipomenu] : []);
            $menus = $stmt->fetchAll(PDO::FETCH_ASSOC);

            echo json_encode([
                'success' => true,
                'data' => $menus,
                'count' => count($menus)
            ]);
            break;

        case 'create':
            // Validar sesión de administrador
            if (empty($_SESSION['admin_id'])) {
                throw new Exception('No se encontró la sesión del administrador');
            }

            $admin_id = $_SESSION['admin_id'];

            $requiredFields = ['tipomenu', 'fecha', 'descripcion'];
            foreach ($requiredFields as $field) {
                if (empty($data[$field])) {
                    throw new Exception("El campo '$field' es obligatorio");
                }
            }

            $stmt = $pdo->prepare("INSERT INTO menu (tipomenu, fecha, descripcion, admin_id) VALUES (?, ?, ?, ?)");
            $stmt->execute([
                $data['tipomenu'],
                $data['fecha'],
                $data['descripcion'],
                $admin_id
            ]);

            echo json_encode([
                'success' => true,
                'message' => 'Menú creado correctamente',
                'id' => $pdo->lastInsertId()
            ]);
            break;

        case 'update':
            if (empty($_SESSION['admin_id'])) {
                throw new Exception('No se encontró la sesión del administrador');
            }

            if (empty($data['idmenu'])) {
                throw new Exception('El campo "idmenu" es obligatorio para actualizar');
            }

            $stmt = $pdo->prepare("UPDATE menu SET tipomenu = ?, fecha = ?, descripcion = ? WHERE idmenu = ?");
            $stmt->execute([
                $data['tipomenu'],
                $data['fecha'],
                $data['descripcion'],
                $data['idmenu']
            ]);

            echo json_encode([
                'success' => $stmt->rowCount() > 0,
                'message' => $stmt->rowCount() > 0 ? 'Menú actualizado correctamente' : 'No se realizaron cambios'
            ]);
            break;

        case 'delete':
            if (empty($_SESSION['admin_id'])) {
                throw new Exception('No se encontró la sesión del administrador');
            }

            if (empty($data['idmenu'])) {
                throw new Exception('El campo "idmenu" es obligatorio para eliminar');
            }

            $stmt = $pdo->prepare("DELETE FROM menu WHERE idmenu = ?");
            $stmt->execute([$data['idmenu']]);

            echo json_encode([
                'success' => $stmt->rowCount() > 0,
                'message' => $stmt->rowCount() > 0 ? 'Menú eliminado correctamente' : 'No se encontró el menú a eliminar'
            ]);
            break;

        default:
            throw new Exception('Acción no válida. Usa: read, create, update, delete');
    }

} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage(),
        'received_data' => $data ?? null
    ]);
}
?>
