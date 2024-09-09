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
            idcredenciales,
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
        $password = $data['password'];

        $encryptedPassword = password_hash($password, PASSWORD_DEFAULT);

        $sql = "UPDATE credenciales SET user = :user, contrasena = :password WHERE idcredenciales = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['user' => $user, 'password' => $encryptedPassword, 'id' => $id]);

        echo json_encode(['success' => true]);

    } elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE' && $action === 'delete') {
        $data = json_decode(file_get_contents("php://input"), true);
        $id = $data['id'];

        $sql = "DELETE FROM credenciales WHERE idcredenciales = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['id' => $id]);

        echo json_encode(['success' => true]);

    } elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && $action === 'insert') {
        $data = json_decode(file_get_contents("php://input"), true);
        $user = $data['user'];
        $password = $data['password'];
        $rol = $data['rol'];

        $encryptedPassword = password_hash($password, PASSWORD_DEFAULT);

        $sql = "INSERT INTO credenciales (user, contrasena, rol) VALUES (:user, :password, :rol)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['user' => $user, 'password' => $encryptedPassword, 'rol' => $rol]);

        echo json_encode(['success' => true]);

    } else {
        echo json_encode(['error' => 'Acción no válida']);
    }
} catch (Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
}

?>
