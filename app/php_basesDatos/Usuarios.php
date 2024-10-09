<?php
require_once 'C:/xampp/htdocs/Proyecto/core/database.php';

 // Conexión a la base de datos
 $database = new Database();
 $pdo = $database->getConnection();

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
        $rol = $data['rol']; // Get the rol

        // Map rol name to rol id
        $rolId = '';
        switch ($rol) {
            case 'Estudiante SS':
                $rolId = 1;
                break;
            case 'Docente':
                $rolId = 2;
                break;
            case 'Administrador':
                $rolId = 3;
                break;
            default:
                $rolId = ''; // Or handle unexpected values
                break;
        }

        $pdo->beginTransaction(); // Start transaction

        try {
            // Update the credentials
            $sql = "UPDATE credenciales SET user = :user, estado = :estado" . ($password ? ", contrasena = :password" : "") . " WHERE idcredenciales = :id";
            $stmt = $pdo->prepare($sql);
            $params = ['user' => $user, 'estado' => $estado, 'id' => $id];

            if ($password) {
                $encryptedPassword = password_hash($password, PASSWORD_DEFAULT);
                $params['password'] = $encryptedPassword;
            }

            $stmt->execute($params);

            // Update the rol
            $sqlrol = "UPDATE usuarios SET tipo_usuario_idtipo_usuario = :rol WHERE credenciales_idcredenciales = :id";
            $stmtrol = $pdo->prepare($sqlrol);
            $stmtrol->execute(['rol' => $rolId, 'id' => $id]);

            $pdo->commit(); // Commit transaction

            echo json_encode(['success' => true]);

        } catch (Exception $e) {
            $pdo->rollBack(); // Rollback transaction on error
            echo json_encode(['error' => $e->getMessage()]);
        }

    } elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE' && $action === 'delete') {
        $data = json_decode(file_get_contents("php://input"), true);
        $id = $data['id'];

        $stmt = $pdo->prepare("DELETE FROM credenciales WHERE idcredenciales = :id");
        $stmt->execute(['id' => $id]);

        echo json_encode(['success' => true]);

    } else {
        echo json_encode(['error' => 'Acción no válida']);
    }
} catch (Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>
