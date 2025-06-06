<?php
header('Content-Type: application/json; charset=utf-8');
require 'cors.php';

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    exit(0);
}

require_once 'conexion.php';

function getInputData() {
    $contentType = isset($_SERVER['CONTENT_TYPE']) ? trim($_SERVER['CONTENT_TYPE']) : '';
    
    if ($contentType === 'application/json') {
        $input = file_get_contents('php://input');
        return json_decode($input, true);
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
            $required = ['tipomenu', 'fecha', 'descripcion'];
            foreach ($required as $field) {
                if (empty($data[$field])) {
                    throw new Exception("El campo $field es requerido");
                }
            }
            
            $stmt = $pdo->prepare("INSERT INTO menu (tipomenu, fecha, descripcion) VALUES (?, ?, ?)");
            $stmt->execute([$data['tipomenu'], $data['fecha'], $data['descripcion']]);
            
            echo json_encode([
                'success' => true,
                'id' => $pdo->lastInsertId()
            ]);
            break;
            
        case 'update':
            if (empty($data['idmenu'])) {
                throw new Exception('ID del menú es requerido para actualizar');
            }
            
            $stmt = $pdo->prepare("UPDATE menu SET tipomenu = ?, fecha = ?, descripcion = ? WHERE idmenu = ?");
            $stmt->execute([
                $data['tipomenu'],
                $data['fecha'],
                $data['descripcion'],
                $data['idmenu']
            ]);
            
            echo json_encode(['success' => $stmt->rowCount() > 0]);
            break;
            
        case 'delete':
            if (empty($data['idmenu'])) {
                throw new Exception('ID del menú es requerido para eliminar');
            }
            
            $stmt = $pdo->prepare("DELETE FROM menu WHERE idmenu = ?");
            $stmt->execute([$data['idmenu']]);
            
            echo json_encode(['success' => $stmt->rowCount() > 0]);
            break;
            
        default:
            throw new Exception('Acción no válida. Acciones permitidas: read, create, update, delete');
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