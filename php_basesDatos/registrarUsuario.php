<?php
include("conexion.php");

try {
    // Iniciar la transacción
    $pdo->beginTransaction();

    // Recibir y limpiar datos del formulario
    $nombre = trim($_POST['nombre']);
    $apellido = trim($_POST['apellido']);
    $email = filter_var(trim($_POST['correo']), FILTER_VALIDATE_EMAIL);
    $password = trim($_POST['password']); 
    $telefono = trim($_POST['celular']);
    $direccion = trim($_POST['direccion']);
    $numerodocumento = trim($_POST['documento']);
    $tipo_documento_desc = trim($_POST['tipoDocumento']);
    $rol_desc = isset($_POST['rol']) ? trim($_POST['rol']) : null;
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    if (strlen($telefono) > 20) {
        $telefono = substr($telefono, 0, 20);
    }

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


    // Insertar nuevo usuario
    $sql = "INSERT INTO usuarios (nombre, apellido, email, contraseña, telefono, direccion, numerodocumento, tipo_documento_tdoc, tipo_usuario_idtipo_usuario) 
            VALUES (:nombre, :apellido, :email, :contraseña, :telefono, :direccion, :numerodocumento, :tipo_documento_tdoc, :tipo_usuario_idtipo_usuario)";
    
    $stmt = $pdo->prepare($sql);
    
    // Vincular parámetros
    $stmt->bindParam(':nombre', $nombre);
    $stmt->bindParam(':apellido', $apellido);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':contraseña', $hashed_password);
    $stmt->bindParam(':telefono', $telefono);
    $stmt->bindParam(':direccion', $direccion);
    $stmt->bindParam(':numerodocumento', $numerodocumento);
    $stmt->bindParam(':tipo_documento_tdoc', $tipo_documento);
    $stmt->bindParam(':tipo_usuario_idtipo_usuario', $tipo_usuario);

    echo "SQL Query: " . $sql . "\n";
    echo "Parameters: " . json_encode([
        'nombre' => $nombre,
        'apellido' => $apellido,
        'email' => $email,
        'contraseña' => $hashed_password,
        'telefono' => $telefono,
        'direccion' => $direccion,
        'numerodocumento' => $numerodocumento,
        'tipo_documento_tdoc' => $tipo_documento,
        'tipo_usuario_idtipo_usuario' => $tipo_usuario
    ]) . "\n";

    // Ejecutar la consulta
    $stmt->execute();

    // Confirmar la transacción
    $pdo->commit();

    echo "Nuevo usuario registrado exitosamente";
} catch (Exception $e) {
    // En caso de error, revertir la transacción
    $pdo->rollBack();
    echo "Error: " . $e->getMessage();
} catch (PDOException $e) {
    // Manejar excepciones PDO
    $pdo->rollBack();
    echo "Error en la base de datos: " . $e->getMessage();
}

// PHP cierra automáticamente la conexión al final del script
?>
