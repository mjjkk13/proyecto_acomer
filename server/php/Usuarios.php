<?php
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: http://localhost:5173'); 
header('Access-Control-Allow-Methods: POST, GET, OPTIONS, DELETE'); 
header('Access-Control-Allow-Headers: Content-Type, Authorization'); 
header('Access-Control-Allow-Credentials: true');

require 'conexion.php';

// Activar reportes de errores para desarrollo
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Intentamos leer el body JSON
$rawInput = file_get_contents("php://input");
$dataFromBody = json_decode($rawInput, true);
$action = $dataFromBody['action'] ?? $_GET['action'] ?? '';

if ($action === 'fetchAll') {
    // Consulta para obtener todos los usuarios
    $stmt = $pdo->query('SELECT 
        c.idcredenciales, c.estado,
        c.user AS nombre_usuario_credenciales,
        c.contrasena,
        tu.rol AS tipo_usuario_rol,
        c.ultimoacceso
    FROM usuarios u
    LEFT JOIN tipo_usuario tu ON u.tipo_usuario_idtipo_usuario = tu.idtipo_usuario
    LEFT JOIN credenciales c ON u.credenciales_idcredenciales = c.idcredenciales;');

    $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode(is_array($usuarios) ? $usuarios : []);
    exit;

} elseif ($action === 'update') {
    // Usamos el body JSON (ya decodificado en $dataFromBody)
    $data = $dataFromBody;

    if (!isset($data['id'], $data['user'], $data['status'], $data['rol'])) {
        echo json_encode(['error' => 'Faltan datos obligatorios']);
        exit;
    }

    $id = $data['id'];
    $user = trim($data['user']);
    $password = $data['password'] ?? null;
    $estado = $data['status'];
    $rol = trim($data['rol']);

    // Map de roles
    $rolesMap = [
        'Estudiante SS' => 1,
        'Docente' => 2,
        'Administrador' => 3
    ];

    if (!isset($rolesMap[$rol])) {
        echo json_encode(['error' => 'Rol inválido']);
        exit;
    }
    $rolId = $rolesMap[$rol];

    $pdo->beginTransaction();
    try {
        // Actualizar credenciales
        $sql = "UPDATE credenciales SET user = :user, estado = :estado";
        $params = ['user' => $user, 'estado' => $estado, 'id' => $id];

        if ($password) {
            $sql .= ", contrasena = :password";
            $params['password'] = password_hash($password, PASSWORD_DEFAULT);
        }
        $sql .= " WHERE idcredenciales = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);

        // Actualizar rol del usuario
        $sqlrol = "UPDATE usuarios SET tipo_usuario_idtipo_usuario = :rol WHERE credenciales_idcredenciales = :id";
        $stmtrol = $pdo->prepare($sqlrol);
        $stmtrol->execute(['rol' => $rolId, 'id' => $id]);

        $pdo->commit();
        echo json_encode(['success' => true]);
        exit;
    } catch (Exception $e) {
        $pdo->rollBack();
        echo json_encode(['error' => 'Error al actualizar: ' . $e->getMessage()]);
        exit;
    }

} elseif ($action === 'delete') {
    $data = $dataFromBody;

    if (!isset($data['id'])) {
        echo json_encode(['error' => 'ID faltante']);
        exit;
    }

    $id = $data['id'];

    // Verificar si el ID existe antes de eliminar
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM credenciales WHERE idcredenciales = :id");
    $stmt->execute(['id' => $id]);

    if ($stmt->fetchColumn() == 0) {
        echo json_encode(['error' => 'Credencial no encontrada']);
        exit;
    }

    // Eliminar credencial
    $stmt = $pdo->prepare("DELETE FROM credenciales WHERE idcredenciales = :id");
    $stmt->execute(['id' => $id]);

    echo json_encode(['success' => true]);
    exit;
} else {
    echo json_encode(['error' => 'Acción no válida']);
    exit;
}
?>
