<?php

session_start();  // Iniciar la sesión

header('Content-Type: application/json; charset=utf-8');
require 'cors.php';


require_once __DIR__ . '/conexion.php';
$pdo = getPDO(); 
if (!isset($pdo)) {
    echo json_encode(['status' => 'error', 'message' => 'La conexión a la base de datos no se ha establecido']);
    exit();
}

if (isset($_SESSION['idusuarios'])) {
    $id_usuario = $_SESSION['idusuarios'];

    try {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $sql = "SELECT idusuarios, nombre, apellido, email, telefono, direccion 
                    FROM usuarios 
                    WHERE idusuarios = :idusuarios";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':idusuarios', $id_usuario, PDO::PARAM_INT);
            $stmt->execute();

            $userData = $stmt->fetch(PDO::FETCH_ASSOC);
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

            if (!empty($datosActualizados['nuevaContraseña'])) {
                $nuevaContraseña = password_hash($datosActualizados['nuevaContraseña'], PASSWORD_BCRYPT);

                $sql = "SELECT credenciales FROM usuarios WHERE idusuarios = :idusuarios";
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':idusuarios', $id_usuario, PDO::PARAM_INT);
                $stmt->execute();
                $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($usuario) {
                    $idcredenciales = $usuario['credenciales'];

                    $sql = "UPDATE credenciales 
                            SET contrasena = :contrasena 
                            WHERE idcredenciales = :idcredenciales";
                    $stmt = $pdo->prepare($sql);
                    $stmt->bindParam(':contrasena', $nuevaContraseña, PDO::PARAM_STR);
                    $stmt->bindParam(':idcredenciales', $idcredenciales, PDO::PARAM_INT);
                    $stmt->execute();
                } else {
                    echo json_encode(['status' => 'error', 'message' => 'Usuario no encontrado']);
                    exit();
                }
            }

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
