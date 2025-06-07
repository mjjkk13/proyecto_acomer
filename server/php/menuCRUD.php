<?php
session_set_cookie_params([
    'lifetime' => 0,
    'path' => '/',
    'domain' => 'acomer.onrender.com',
    'secure' => true,
    'httponly' => true,
    'samesite' => 'None',
]);

session_start();

header('Content-Type: application/json; charset=utf-8');
require 'cors.php';
require_once 'conexion.php';
$pdo = getPDO();

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit(0);
}

function getInputData() {
    $contentType = $_SERVER['CONTENT_TYPE'] ?? '';
    if (stripos($contentType, 'application/json') !== false) {
        $json = file_get_contents('php://input');
        $data = json_decode($json, true);
        return is_array($data) ? $data : [];
    }
    return $_POST;
}

try {
    $data = getInputData();

    if (empty($data['action'])) {
        throw new Exception('El parámetro "action" es requerido');
    }

    // Validar sesión general
    if (!isset($_SESSION['idusuarios'], $_SESSION['usuario'], $_SESSION['rol'])) {
        throw new Exception("Sesión no iniciada correctamente.");
    }

    $userId = $_SESSION['idusuarios'];
    $usuario = $_SESSION['usuario'];
    $role = $_SESSION['rol'];

    // Define qué roles tienen permisos para modificar
    $rolesAdmin = ['Administrador', 'Admin', 'admin']; // Ajusta según tus roles reales

    switch ($data['action']) {
        case 'read':
            // Cualquier usuario con sesión puede leer
            $tipomenu = $data['tipomenu'] ?? null;

            if ($tipomenu) {
                $stmt = $pdo->prepare("SELECT * FROM menu WHERE tipomenu = ? ORDER BY fecha DESC");
                $stmt->execute([trim($tipomenu)]);
            } else {
                $stmt = $pdo->prepare("SELECT * FROM menu ORDER BY fecha DESC");
                $stmt->execute();
            }
            
            $menus = $stmt->fetchAll(PDO::FETCH_ASSOC);

            echo json_encode([
                'success' => true,
                'data' => $menus,
                'count' => count($menus)
            ]);
            break;

        case 'create':
        case 'update':
        case 'delete':
            // Solo roles con permiso pueden crear, actualizar o eliminar
            if (!in_array($role, $rolesAdmin, true)) {
                throw new Exception("No tiene permisos para realizar esta acción.");
            }

            if ($data['action'] === 'create') {
                $requiredFields = ['tipomenu', 'fecha', 'descripcion'];
                foreach ($requiredFields as $field) {
                    if (empty($data[$field])) {
                        throw new Exception("El campo '$field' es obligatorio");
                    }
                }
                $stmt = $pdo->prepare("INSERT INTO menu (tipomenu, fecha, descripcion, admin_id) VALUES (?, ?, ?, ?)");
                $stmt->execute([
                    trim($data['tipomenu']),
                    trim($data['fecha']),
                    trim($data['descripcion']),
                    $userId // el id del usuario que está creando
                ]);
                echo json_encode([
                    'success' => true,
                    'message' => 'Menú creado correctamente',
                    'id' => $pdo->lastInsertId()
                ]);
            }
            elseif ($data['action'] === 'update') {
                if (empty($data['idmenu'])) {
                    throw new Exception('El campo "idmenu" es obligatorio para actualizar');
                }
                $stmt = $pdo->prepare("UPDATE menu SET tipomenu = ?, fecha = ?, descripcion = ? WHERE idmenu = ?");
                $stmt->execute([
                    trim($data['tipomenu']),
                    trim($data['fecha']),
                    trim($data['descripcion']),
                    (int)$data['idmenu']
                ]);
                echo json_encode([
                    'success' => $stmt->rowCount() > 0,
                    'message' => $stmt->rowCount() > 0 ? 'Menú actualizado correctamente' : 'No se realizaron cambios'
                ]);
            }
            elseif ($data['action'] === 'delete') {
                if (empty($data['idmenu'])) {
                    throw new Exception('El campo "idmenu" es obligatorio para eliminar');
                }
                $stmt = $pdo->prepare("DELETE FROM menu WHERE idmenu = ?");
                $stmt->execute([(int)$data['idmenu']]);
                echo json_encode([
                    'success' => $stmt->rowCount() > 0,
                    'message' => $stmt->rowCount() > 0 ? 'Menú eliminado correctamente' : 'No se encontró el menú a eliminar'
                ]);
            }
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
