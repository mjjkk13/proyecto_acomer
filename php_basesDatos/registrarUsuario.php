<?php
include ("conexion.php");

try {
    // Recibir datos del formulario
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $email = $_POST['correo'];
    $telefono = $_POST['celular'];
    $direccion = $_POST['direccion'];
    $numerodocumento = $_POST['documento'];
    $tipo_documento_desc = $_POST['tipoDocumento'];
    $rol_desc = $_POST['rol'];

    // Obtener el ID del tipo de documento
    $sql_doc = "SELECT tdoc FROM tipo_documento WHERE descripcion = :descripcion";
    $stmt_doc = $pdo->prepare($sql_doc);
    $stmt_doc->bindParam(':descripcion', $tipo_documento_desc);
    $stmt_doc->execute();
    $tipo_documento = $stmt_doc->fetchColumn();

    // Obtener el ID del tipo de usuario (rol)
    $sql_rol = "SELECT idtipo_usuario FROM tipo_usuario WHERE rol = :rol";
    $stmt_rol = $pdo->prepare($sql_rol);
    $stmt_rol->bindParam(':rol', $rol_desc);
    $stmt_rol->execute();
    $tipo_usuario = $stmt_rol->fetchColumn();

    // Consulta SQL para insertar un nuevo usuario usando una consulta preparada
    $sql = "INSERT INTO usuarios (nombre, apellido, email, telefono, direccion, numerodocumento, tipo_documento_tdoc, tipo_usuario_idtipo_usuario) 
            VALUES (:nombre, :apellido, :email, :telefono, :direccion, :numerodocumento, :tipo_documento_tdoc, :tipo_usuario_idtipo_usuario)";

    // Preparar la consulta
    $stmt = $pdo->prepare($sql);  

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

// No es necesario cerrar la conexión manualmente; PHP lo hace automáticamente al final del script.
?>
