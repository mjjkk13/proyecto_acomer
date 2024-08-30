<?php
include("conexion.php");

try {
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
    $user = trim($_POST['user']);
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Verifica los datos recibidos
    echo "<pre>";
    var_dump($nombre, $apellido, $email, $hashed_password, $telefono, $direccion, $numerodocumento, $tipo_documento_desc, $rol_desc, $user);
    echo "</pre>";

    // Obtener el ID del tipo de documento
    $sql_doc = "SELECT tdoc FROM tipo_documento WHERE descripcion = :descripcion";
    $stmt_doc = $pdo->prepare($sql_doc);
    $stmt_doc->bindParam(':descripcion', $tipo_documento_desc);
    $stmt_doc->execute();
    $tipo_documento = $stmt_doc->fetchColumn();

    // Verifica el ID del tipo de documento
    if ($tipo_documento === false) {
        throw new Exception("Tipo de documento no encontrado");
    }

    // Obtener el ID del tipo de usuario (rol)
    $sql_rol = "SELECT idtipo_usuario FROM tipo_usuario WHERE rol = :rol";
    $stmt_rol = $pdo->prepare($sql_rol);
    $stmt_rol->bindParam(':rol', $rol_desc);
    $stmt_rol->execute();
    $tipo_usuario = $stmt_rol->fetchColumn();

    // Verifica el ID del tipo de usuario
    if ($tipo_usuario === false) {
        throw new Exception("Rol de usuario no encontrado");
    }

    // Insertar credenciales
    $sql_cred = "INSERT INTO credenciales (user, password, fecharegistro) 
                 VALUES (:user, :password, NOW())";
    $stmt_cred = $pdo->prepare($sql_cred);
    $stmt_cred->bindParam(':user', $user);
    $stmt_cred->bindParam(':password', $hashed_password);
    $stmt_cred->execute();

    // Obtener el ID de las credenciales insertadas
    $credenciales_id = $pdo->lastInsertId();

    // Insertar nuevo usuario
    $sql_usr = "INSERT INTO usuarios (nombre, apellido, email, telefono, direccion, numerodocumento, tipo_documento_tdoc, tipo_usuario_idtipo_usuario, credenciales_idcredenciales) 
                VALUES (:nombre, :apellido, :email, :telefono, :direccion, :numerodocumento, :tipo_documento_tdoc, :tipo_usuario_idtipo_usuario, :credenciales_idcredenciales)";
    
    $stmt_usr = $pdo->prepare($sql_usr);
    
    // Vincular parámetros
    $stmt_usr->bindParam(':nombre', $nombre);
    $stmt_usr->bindParam(':apellido', $apellido);
    $stmt_usr->bindParam(':email', $email);
    $stmt_usr->bindParam(':telefono', $telefono);
    $stmt_usr->bindParam(':direccion', $direccion);
    $stmt_usr->bindParam(':numerodocumento', $numerodocumento);
    $stmt_usr->bindParam(':tipo_documento_tdoc', $tipo_documento);
    $stmt_usr->bindParam(':tipo_usuario_idtipo_usuario', $tipo_usuario);
    $stmt_usr->bindParam(':credenciales_idcredenciales', $credenciales_id);

    // Ejecutar la consulta
    $stmt_usr->execute();

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
?>
