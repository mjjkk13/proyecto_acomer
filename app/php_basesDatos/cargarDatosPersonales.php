<?php
session_start();  // Iniciar la sesión

if (isset($_SESSION['idusuarios'])) {
    $id_usuario = $_SESSION['idusuarios'];

    // Incluir el archivo de conexión
    require_once 'C:/xampp/htdocs/Proyecto/core/database.php';

    try {
        // Obtener la conexión a la base de datos
        $database = new Database();
        $pdo = $database->getConnection();

        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            // Si la solicitud es GET, devolver los datos personales del usuario
            $sql = "SELECT idusuarios, nombre, apellido, email, telefono, direccion FROM usuarios WHERE idusuarios = :idusuarios";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':idusuarios', $id_usuario);
            $stmt->execute();

            // Fetch de los datos del usuario
            $userData = $stmt->fetch(PDO::FETCH_ASSOC);

            // Devolver los datos en formato JSON
            echo json_encode($userData);
        } elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Si la solicitud es POST, actualizar los datos personales del usuario
            $datosActualizados = json_decode(file_get_contents("php://input"), true);

            // Consulta para actualizar los datos personales del usuario
            $sql = "UPDATE usuarios SET nombre = :nombre, apellido = :apellido, email = :email, telefono = :telefono, direccion = :direccion WHERE idusuarios = :idusuarios";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':nombre', $datosActualizados['nombre']);
            $stmt->bindParam(':apellido', $datosActualizados['apellido']);
            $stmt->bindParam(':email', $datosActualizados['email']);
            $stmt->bindParam(':telefono', $datosActualizados['telefono']);
            $stmt->bindParam(':direccion', $datosActualizados['direccion']);
            $stmt->bindParam(':idusuarios', $id_usuario);
            $stmt->execute();

            // Devolver una respuesta de éxito
            echo json_encode(['status' => 'success']);
        }
    } catch (PDOException $e) {
        echo "Error en la base de datos: " . $e->getMessage();
        exit();
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Usuario no autenticado']);
}
?>