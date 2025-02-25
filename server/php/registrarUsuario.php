<?php
require_once 'C:/xampp/htdocs/Proyecto/core/database.php';

header('Content-Type: application/json');

$response = array('success' => false, 'message' => '');

try {


    // Recibir y limpiar datos del formulario
    $nombre = trim($_POST['nombre']);
    $apellido = trim($_POST['apellido']);
    $email = filter_var(trim($_POST['correo']), FILTER_VALIDATE_EMAIL);
    $contrasena = trim($_POST['contrasena']); 
    $telefono = trim($_POST['celular']);
    $direccion = trim($_POST['direccion']);
    $numerodocumento = trim($_POST['documento']);
    $tipo_documento_desc = trim($_POST['tipoDocumento']);
    $rol_desc = isset($_POST['rol']) ? trim($_POST['rol']) : null;
    $user = trim($_POST['user']);
    $hashed_password = password_hash($contrasena, PASSWORD_DEFAULT);

    // Obtener el ID del tipo de documento
    $sql_doc = "SELECT tdoc FROM tipo_documento WHERE tdoc = :descripcion";
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

    // Verifica si el usuario ya existe
    $sql_usr_check = "SELECT idusuarios FROM usuarios WHERE email = :email";
    $stmt_usr_check = $pdo->prepare($sql_usr_check);
    $stmt_usr_check->bindParam(':email', $email);
    $stmt_usr_check->execute();
    if ($stmt_usr_check->fetchColumn()) {
        throw new Exception("El usuario con el correo electrónico proporcionado ya existe.");
    }

    // Insertar en la tabla de credenciales
    $sql_cred = "INSERT INTO credenciales (user, contrasena, fecharegistro, estado) VALUES (:user, :contrasena, NOW(), 1)";
    $stmt_cred = $pdo->prepare($sql_cred);
    $stmt_cred->bindParam(':user', $user);
    $stmt_cred->bindParam(':contrasena', $hashed_password);
    $stmt_cred->execute();
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
    $usuarios_id = $pdo->lastInsertId();

    // Insertar en la tabla de conexión según el rol
    if ($rol_desc === 'Docente') {
        $sql_docente = "INSERT INTO docente (usuarios_idusuarios, usuarios_tipo_documento_tdoc, usuarios_tipo_usuario_idtipo_usuario, usuarios_credenciales_idcredenciales) 
                        VALUES (:usuarios_idusuarios, :tipo_documento_tdoc, :tipo_usuario_idtipo_usuario, :credenciales_idcredenciales)";
        
        $stmt_docente = $pdo->prepare($sql_docente);
        $stmt_docente->bindParam(':usuarios_idusuarios', $usuarios_id);
        $stmt_docente->bindParam(':tipo_documento_tdoc', $tipo_documento);
        $stmt_docente->bindParam(':tipo_usuario_idtipo_usuario', $tipo_usuario);
        $stmt_docente->bindParam(':credenciales_idcredenciales', $credenciales_id);
        $stmt_docente->execute();
    } elseif ($rol_desc === 'Administrador') {
        $sql_admin = "INSERT INTO admin (usuarios_idusuarios, usuarios_tipo_documento_tdoc, usuarios_tipo_usuario_idtipo_usuario, usuarios_credenciales_idcredenciales) 
                      VALUES (:usuarios_idusuarios, :tipo_documento_tdoc, :tipo_usuario_idtipo_usuario, :credenciales_idcredenciales)";
        
        $stmt_admin = $pdo->prepare($sql_admin);
        $stmt_admin->bindParam(':usuarios_idusuarios', $usuarios_id);
        $stmt_admin->bindParam(':tipo_documento_tdoc', $tipo_documento);
        $stmt_admin->bindParam(':tipo_usuario_idtipo_usuario', $tipo_usuario);
        $stmt_admin->bindParam(':credenciales_idcredenciales', $credenciales_id);
        $stmt_admin->execute();
    } elseif ($rol_desc === 'Estudiante SS') {
        $sql_estudiante_ss = "INSERT INTO estudiante_ss (usuarios_idusuarios, usuarios_tipo_documento_tdoc, usuarios_tipo_usuario_idtipo_usuario, usuarios_credenciales_idcredenciales) 
                              VALUES (:usuarios_idusuarios, :tipo_documento_tdoc, :tipo_usuario_idtipo_usuario, :credenciales_idcredenciales)";
        
        $stmt_estudiante_ss = $pdo->prepare($sql_estudiante_ss);
        $stmt_estudiante_ss->bindParam(':usuarios_idusuarios', $usuarios_id);
        $stmt_estudiante_ss->bindParam(':tipo_documento_tdoc', $tipo_documento);
        $stmt_estudiante_ss->bindParam(':tipo_usuario_idtipo_usuario', $tipo_usuario);
        $stmt_estudiante_ss->bindParam(':credenciales_idcredenciales', $credenciales_id);
        $stmt_estudiante_ss->execute();
    }

    // Confirmar la transacción
    $pdo->commit();

    $response['success'] = true;
    $response['message'] = "Nuevo usuario registrado exitosamente";
} catch (Exception $e) {
    // En caso de error, revertir la transacción
    if (isset($pdo)) {
        if (isset($pdo)) {
            $pdo->rollBack();
        }
    }
    $response['message'] = "Error: " . $e->getMessage();
} catch (PDOException $e) {
    // Manejar excepciones PDO
    $pdo->rollBack();
    $response['message'] = "Error en la base de datos: " . $e->getMessage();
}

echo json_encode($response);
?>
