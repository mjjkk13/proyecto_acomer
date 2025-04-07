<?php
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: http://localhost:5173');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS, DELETE');
header('Access-Control-Allow-Headers: Content-Type, Authorization');
header('Access-Control-Allow-Credentials: true');

// Soporte para preflight CORS
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

require 'conexion.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Entrada desde el body JSON
$rawInput = file_get_contents("php://input");
$dataFromBody = json_decode($rawInput, true);
$action = $dataFromBody['action'] ?? $_GET['action'] ?? '';

try {
    switch ($action) {
        case 'fetchAll':
            fetchAll($pdo);
            break;
        case 'update':
            updateUser($pdo, $dataFromBody);
            break;
        case 'delete':
            deleteUser($pdo, $dataFromBody);
            break;
        default:
            http_response_code(400);
            echo json_encode(['error' => 'Acción no válida']);
    }
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Error de base de datos: ' . $e->getMessage()]);
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode(['error' => $e->getMessage()]);
}

// ======================================
// FUNCIONES
// ======================================

function fetchAll($pdo) {
    $stmt = $pdo->query('
        SELECT 
            c.idcredenciales,
            c.user AS nombre_usuario,
            tu.rol AS rol,
            c.estado,
            c.ultimoacceso
        FROM usuarios u
        LEFT JOIN tipo_usuario tu ON u.tipo_usuario = tu.idtipo_usuario
        LEFT JOIN credenciales c ON u.credenciales = c.idcredenciales
    ');

    $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($usuarios ?: []);
    exit;
}

function updateUser($pdo, $data) {
    $required = ['id', 'user', 'status', 'rol'];
    foreach ($required as $field) {
        if (!isset($data[$field]) || $data[$field] === '') {
            throw new Exception("Campo requerido: $field");
        }
    }

    // Validar y obtener ID del rol
    $stmt = $pdo->prepare('SELECT idtipo_usuario FROM tipo_usuario WHERE rol = ?');
    $stmt->execute([$data['rol']]);
    $tipo = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$tipo) {
        throw new Exception("Rol inválido");
    }

    // Preparar actualización de credenciales
    $query = 'UPDATE credenciales SET user = ?, estado = ?';
    $params = [$data['user'], $data['status']];

    // Solo actualiza contraseña si se envía, y la hashea
    if (!empty($data['password'])) {
        $hashedPassword = password_hash($data['password'], PASSWORD_BCRYPT);
        $query .= ', contrasena = ?';
        $params[] = $hashedPassword;
    }

    $query .= ' WHERE idcredenciales = ?';
    $params[] = $data['id'];

    // Ejecutar actualizaciones
    $stmt = $pdo->prepare($query);
    $stmt->execute($params);

    $stmt = $pdo->prepare('UPDATE usuarios SET tipo_usuario = ? WHERE credenciales = ?');
    $stmt->execute([$tipo['idtipo_usuario'], $data['id']]);

    echo json_encode(['success' => true]);
    exit;
}

function deleteUser($pdo, $data) {
    if (empty($data['id'])) {
        throw new Exception("ID requerido");
    }

    $id = $data['id'];

    // Eliminar primero de `usuarios`
    $stmt = $pdo->prepare('DELETE FROM usuarios WHERE credenciales = ?');
    $stmt->execute([$id]);

    // Luego eliminar credenciales
    $stmt = $pdo->prepare('DELETE FROM credenciales WHERE idcredenciales = ?');
    $stmt->execute([$id]);

    echo json_encode(['success' => true]);
    exit;
}
