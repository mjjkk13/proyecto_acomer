<?php
include ("conexion.php");

try {
    // Crear conexión usando PDO
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    // Configurar el modo de error de PDO a excepción
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Recibir datos del formulario
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $email = $_POST['email'];
    $telefono = $_POST['telefono'];
    $direccion = $_POST['direccion'];
    $numerodocumento = $_POST['numerodocumento'];
    $tipo_documento = $_POST['tipo_documento_tdoc'];
    $tipo_usuario = $_POST['tipo_usuario_idtipo_usuario'];

    // Consulta SQL para insertar un nuevo usuario usando una consulta preparada
    $sql = "INSERT INTO usuarios (nombre, apellido, email, telefono, direccion, numerodocumento, tipo_documento_tdoc, tipo_usuario_idtipo_usuario) 
            VALUES (:nombre, :apellido, :email, :telefono, :direccion, :numerodocumento, :tipo_documento_tdoc, :tipo_usuario_idtipo_usuario)";

    // Preparar la consulta
    $stmt = $conn->prepare($sql);

    // Vincular los parámetros
    $stmt->bindParam(':nombre', $nombre);
    $stmt->bindParam(':apellido', $apellido);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':telefono', $telefono);
    $stmt->bindParam(':direccion', $direccion);
    $stmt->bindParam(':numerodocumento', $numerodocumento);
    $stmt->bindParam(':tipo_documento_tdoc', $tipo_documento);
    $stmt->bindParam(':tipo_usuario_idtipo_usuario', $tipo_usuario);

    // Ejecutar la consulta
    $stmt->execute();

    echo "Nuevo usuario registrado exitosamente";
} catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
}

// Cerrar la conexión
$conn = null;
?>