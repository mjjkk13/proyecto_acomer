<?php 
require 'cors.php';
header('Content-Type: application/json; charset=utf-8');

require_once 'conexion.php';

$pdo = getPDO(); 

$response = ['success' => false, 'message' => ''];
$transactionStarted = false;

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        http_response_code(405);
        throw new Exception("Método no permitido");
    }

    $input = json_decode(file_get_contents('php://input'), true) ?? $_POST;

    $requiredFields = [
        'nombre', 'apellido', 'correo', 'contrasena',
        'celular', 'direccion', 'documento', 'tipoDocumento',
        'rol', 'user'
    ];

    foreach ($requiredFields as $field) {
        if (empty($input[$field])) {
            http_response_code(400);
            throw new Exception("Campo requerido: $field");
        }
    }

    $nombre = trim($input['nombre']);
    $apellido = trim($input['apellido']);
    $email = trim($input['correo']);
    $contrasena = trim($input['contrasena']);
    $telefono = trim($input['celular']);
    $direccion = trim($input['direccion']);
    $numerodocumento = trim($input['documento']);
    $tipo_documento_desc = trim($input['tipoDocumento']);
    $rol_desc = trim($input['rol']);
    $user = trim($input['user']);

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        throw new Exception("Formato de email inválido", 400);
    }

    $pdo->beginTransaction();
    $transactionStarted = true;

    $stmt_doc = $pdo->prepare("SELECT tdoc FROM tipo_documento WHERE tdoc = :descripcion");
    $stmt_doc->execute([':descripcion' => $tipo_documento_desc]);
    if (!$tipo_documento = $stmt_doc->fetchColumn()) {
        throw new Exception("Tipo documento no válido", 400);
    }

    $stmt_rol = $pdo->prepare("SELECT idtipo_usuario FROM tipo_usuario WHERE rol = :rol");
    $stmt_rol->execute([':rol' => $rol_desc]);
    if (!$tipo_usuario = $stmt_rol->fetchColumn()) {
        throw new Exception("Rol no válido", 400);
    }

    $stmt_email = $pdo->prepare("SELECT idusuarios FROM usuarios WHERE email = :email");
    $stmt_email->execute([':email' => $email]);
    if ($stmt_email->fetchColumn()) {
        throw new Exception("El email ya está registrado", 409);
    }

    $hashed_password = password_hash($contrasena, PASSWORD_DEFAULT);

    $stmt_cred = $pdo->prepare("
        INSERT INTO credenciales (user, contrasena, fecharegistro, estado)
        VALUES (:user, :contrasena, NOW(), 1)
    ");
    $stmt_cred->execute([':user' => $user, ':contrasena' => $hashed_password]);
    $credenciales_id = $pdo->lastInsertId();

    // NO SE INCLUYE `ultimoacceso` en este insert
    $stmt_usr = $pdo->prepare("
        INSERT INTO usuarios (
            nombre, apellido, email, telefono, direccion,
            numerodocumento, tipo_documento, tipo_usuario,
            credenciales
        ) VALUES (
            :nombre, :apellido, :email, :telefono, :direccion,
            :numerodocumento, :tdoc, :tipo_usuario,
            :credenciales
        )
    ");
    $stmt_usr->execute([ 
        ':nombre' => $nombre,
        ':apellido' => $apellido,
        ':email' => $email,
        ':telefono' => $telefono,
        ':direccion' => $direccion,
        ':numerodocumento' => $numerodocumento,
        ':tdoc' => $tipo_documento,
        ':tipo_usuario' => $tipo_usuario,
        ':credenciales' => $credenciales_id
    ]);
    
    $usuarios_id = $pdo->lastInsertId();

    $tabla_rol = match($rol_desc) {
        'Docente' => 'docente',
        'Administrador' => 'admin',
        'Estudiante SS' => 'estudiante_ss',
        default => throw new Exception("Rol no soportado", 400)
    };

    $stmt_rol_insert = $pdo->prepare("
        INSERT INTO $tabla_rol (usuario_id)
        VALUES (:usuario_id)
    ");
    $stmt_rol_insert->execute([ ':usuario_id' => $usuarios_id ]);

    $pdo->commit();
    
    $response = [
        'success' => true,
        'message' => 'Usuario registrado exitosamente',
        'data' => [
            'id' => $usuarios_id,
            'email' => $email,
            'rol' => $rol_desc
        ]
    ];
    
    http_response_code(201);

} catch (PDOException $e) {
    if ($transactionStarted) $pdo->rollBack();
    $response['message'] = "Error en base de datos: " . $e->getMessage();
    http_response_code(500);
} catch (Exception $e) {
    if ($transactionStarted) $pdo->rollBack();
    $response['message'] = $e->getMessage();
    http_response_code($e->getCode() ?: 400);
}

echo json_encode($response);
?>
