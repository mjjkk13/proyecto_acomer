<?php
session_start();  // Iniciar la sesión

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: http://localhost:5173'); 
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');
header('Access-Control-Allow-Credentials: true');

// Incluir el archivo de conexión
require_once __DIR__ . '/conexion.php';

// Verifica que la conexión se estableció correctamente
if (!isset($pdo)) {
    echo json_encode(['status' => 'error', 'message' => 'La conexión a la base de datos no se ha establecido']);
    exit();
}

if (isset($_SESSION['idusuarios'])) {
    $id_usuario = $_SESSION['idusuarios'];

    try {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $sql = "SELECT idusuarios, nombre, apellido, email, telefono, direccion FROM usuarios WHERE idusuarios = :idusuarios";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':idusuarios', $id_usuario, PDO::PARAM_INT);
            $stmt->execute();

            $userData = $stmt->fetch();
            echo json_encode($userData ? $userData : []);
            exit();
        } elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $datosActualizados = json_decode(file_get_contents("php://input"), true);

            if (!$datosActualizados) {
                echo json_encode(['status' => 'error', 'message' => 'No se recibieron datos']);
                exit();
            }

            $sql = "UPDATE usuarios 
                    SET nombre = :nombre, 
                        apellido = :apellido, 
                        email = :email, 
                        telefono = :telefono, 
                        direccion = :direccion 
                    WHERE idusuarios = :idusuarios";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':nombre', $datosActualizados['nombre'], PDO::PARAM_STR);
            $stmt->bindParam(':apellido', $datosActualizados['apellido'], PDO::PARAM_STR);
            $stmt->bindParam(':email', $datosActualizados['email'], PDO::PARAM_STR);
            $stmt->bindParam(':telefono', $datosActualizados['telefono'], PDO::PARAM_STR);
            $stmt->bindParam(':direccion', $datosActualizados['direccion'], PDO::PARAM_STR);
            $stmt->bindParam(':idusuarios', $id_usuario, PDO::PARAM_INT);
            $stmt->execute();

            echo json_encode(['status' => 'success']);
            exit();
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Método no permitido']);
            exit();
        }
    } catch (PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => 'Error en la base de datos: ' . $e->getMessage()]);
        exit();
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Usuario no autenticado']);
    exit();
}
?>
