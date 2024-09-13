<?php
session_start();  // Iniciar la sesión

if (isset($_SESSION['id_usuario'])) {
    $id_usuario = $_SESSION['id_usuario'];

    // Conexión a la base de datos
    include("conexion.php");

    try {
        $conn = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Consulta para obtener los datos personales del usuario autenticado
        $sql = "SELECT idusuarios, nombre, apellido, email, telefono,direccion FROM usuarios WHERE id_usuario = :id_usuario";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':idusuarios', $id_usuario);
        $stmt->execute();

        // Fetch de los datos del usuario
        $userData = $stmt->fetch(PDO::FETCH_ASSOC);

        // Devolver los datos en formato JSON
        echo json_encode($userData);
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
} else {
    // Si no hay sesión iniciada, devolver un error
    echo json_encode(['error' => 'Usuario no autenticado']);
}
?>
