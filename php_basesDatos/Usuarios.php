<?php
include("conexion.php");
header('Content-Type: application/json');
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

try {
    $action = isset($_GET['action']) ? $_GET['action'] : '';

    if ($_SERVER['REQUEST_METHOD'] === 'GET' && $action === 'fetchAll') {
        $stmt = $pdo->query('SELECT 
            idcredenciales, estado,
            c.user AS nombre_usuario_credenciales,
            c.contrasena,
            tu.rol AS tipo_usuario_rol,
            c.ultimoacceso
        FROM 
            usuarios u
            LEFT JOIN tipo_usuario tu ON u.tipo_usuario_idtipo_usuario = tu.idtipo_usuario
            LEFT JOIN credenciales c ON u.credenciales_idcredenciales = c.idcredenciales;');
        $credenciales = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($credenciales);

    } elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && $action === 'update') {
        $data = json_decode(file_get_contents("php://input"), true);
        $id = $data['id'];
        $user = $data['user'];
        $password = $data['password'] ?? null; // Check if password is provided
        $estado = $data['status'];
    
        $sql = "UPDATE credenciales SET user = :user, estado = :estado WHERE idcredenciales = :id";
    
        if ($password) {
            $encryptedPassword = password_hash($password, PASSWORD_DEFAULT);
            $sql = "UPDATE credenciales SET user = :user, contrasena = :password, estado = :estado WHERE idcredenciales = :id";
        }
    
        $stmt = $pdo->prepare($sql);
        $params = ['user' => $user, 'estado' => $estado, 'id' => $id];
    
        if ($password) {
            $params['password'] = $encryptedPassword;
        }
    
        $stmt->execute($params);
    
        echo json_encode(['success' => true]);
    }
    elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE' && $action === 'delete') {
        $data = json_decode(file_get_contents("php://input"), true);
        $id = $data['id'];

        $sql = "DELETE FROM credenciales WHERE idcredenciales = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['id' => $id]);

        echo json_encode(['success' => true]);

    } else {
        echo json_encode(['error' => 'Acción no válida']);
    }
} catch (Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
}

?>
