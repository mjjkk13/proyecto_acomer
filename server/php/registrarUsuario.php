<?php
// Configuración de cabeceras para permitir CORS desde el frontend
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: http://localhost:5173'); // Cambiar según el dominio frontend
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');
header('Access-Control-Allow-Credentials: true');

// Manejar preflight requests (CORS OPTIONS)
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

/**
 * @OA\Post(
 *     path="/register",
 *     summary="Registro de un nuevo usuario en el sistema.",
 *     description="Registra un nuevo usuario con su tipo de documento, rol y credenciales.",
 *     operationId="registrarUsuario",
 *     requestBody={
 *         @OA\RequestBody(
 *             required=true,
 *             @OA\Content(
 *                 mediaType="application/json",
 *                 @OA\Schema(
 *                     type="object",
 *                     required={"nombre", "apellido", "correo", "contrasena", "celular", "direccion", "documento", "tipoDocumento", "rol", "user"},
 *                     @OA\Property(
 *                         property="nombre",
 *                         type="string",
 *                         example="Juan"
 *                     ),
 *                     @OA\Property(
 *                         property="apellido",
 *                         type="string",
 *                         example="Pérez"
 *                     ),
 *                     @OA\Property(
 *                         property="correo",
 *                         type="string",
 *                         example="juan.perez@example.com"
 *                     ),
 *                     @OA\Property(
 *                         property="contrasena",
 *                         type="string",
 *                         example="password123"
 *                     ),
 *                     @OA\Property(
 *                         property="celular",
 *                         type="string",
 *                         example="123456789"
 *                     ),
 *                     @OA\Property(
 *                         property="direccion",
 *                         type="string",
 *                         example="Calle Falsa 123"
 *                     ),
 *                     @OA\Property(
 *                         property="documento",
 *                         type="string",
 *                         example="1234567890"
 *                     ),
 *                     @OA\Property(
 *                         property="tipoDocumento",
 *                         type="string",
 *                         example="Cédula"
 *                     ),
 *                     @OA\Property(
 *                         property="rol",
 *                         type="string",
 *                         example="Docente"
 *                     ),
 *                     @OA\Property(
 *                         property="user",
 *                         type="string",
 *                         example="juanperez"
 *                     )
 *                 )
 *             )
 *         )
 *     },
 *     responses={
 *         @OA\Response(
 *             response=201,
 *             description="Usuario registrado exitosamente.",
 *             @OA\JsonContent(
 *                 @OA\Property(
 *                     property="success",
 *                     type="boolean",
 *                     example=true
 *                 ),
 *                 @OA\Property(
 *                     property="message",
 *                     type="string",
 *                     example="Usuario registrado exitosamente"
 *                 ),
 *                 @OA\Property(
 *                     property="data",
 *                     type="object",
 *                     @OA\Property(
 *                         property="id",
 *                         type="integer",
 *                         example=1
 *                     ),
 *                     @OA\Property(
 *                         property="email",
 *                         type="string",
 *                         example="juan.perez@example.com"
 *                     ),
 *                     @OA\Property(
 *                         property="rol",
 *                         type="string",
 *                         example="Docente"
 *                     )
 *                 )
 *             )
 *         ),
 *         @OA\Response(
 *             response=400,
 *             description="Faltan campos requeridos o datos inválidos."
 *         ),
 *         @OA\Response(
 *             response=409,
 *             description="El email ya está registrado en el sistema."
 *         ),
 *         @OA\Response(
 *             response=500,
 *             description="Error en la base de datos o en el servidor."
 *         )
 *     }
 * )
 */

require_once 'conexion.php';
require 'cors.php';


// Inicializar variables de respuesta y control de transacción
$response = ['success' => false, 'message' => ''];
$transactionStarted = false;

try {
    // Validar método HTTP
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        http_response_code(405);
        throw new Exception("Método no permitido");
    }

    // Obtener datos del cuerpo JSON (React) o de formulario clásico
    $input = json_decode(file_get_contents('php://input'), true) ?? $_POST;

    // Validar que todos los campos requeridos están presentes
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

    // Asignar y limpiar variables
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

    // Validación de formato de correo electrónico
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        throw new Exception("Formato de email inválido", 400);
    }

    // Iniciar transacción
    $pdo->beginTransaction();
    $transactionStarted = true;

    // Verificar existencia del tipo de documento
    $stmt_doc = $pdo->prepare("SELECT tdoc FROM tipo_documento WHERE tdoc = :descripcion");
    $stmt_doc->execute([':descripcion' => $tipo_documento_desc]);
    
    if (!$tipo_documento = $stmt_doc->fetchColumn()) {
        throw new Exception("Tipo documento no válido", 400);
    }

    // Verificar existencia del rol
    $stmt_rol = $pdo->prepare("SELECT idtipo_usuario FROM tipo_usuario WHERE rol = :rol");
    $stmt_rol->execute([':rol' => $rol_desc]);
    
    if (!$tipo_usuario = $stmt_rol->fetchColumn()) {
        throw new Exception("Rol no válido", 400);
    }

    // Validar que el email no esté registrado previamente
    $stmt_email = $pdo->prepare("SELECT idusuarios FROM usuarios WHERE email = :email");
    $stmt_email->execute([':email' => $email]);
    
    if ($stmt_email->fetchColumn()) {
        throw new Exception("El email ya está registrado", 409);
    }

    // Hashear la contraseña para mayor seguridad
    $hashed_password = password_hash($contrasena, PASSWORD_DEFAULT);

    // Insertar credenciales del usuario
    $stmt_cred = $pdo->prepare("
        INSERT INTO credenciales (user, contrasena, fecharegistro, estado)
        VALUES (:user, :contrasena, NOW(), 1)
    ");
    $stmt_cred->execute([':user' => $user, ':contrasena' => $hashed_password]);
    $credenciales_id = $pdo->lastInsertId();

    // Insertar información general del usuario en tabla usuarios
    $stmt_usr = $pdo->prepare("
        INSERT INTO usuarios (
            nombre, apellido, email, telefono, direccion,
            numerodocumento, tipo_documento, tipo_usuario, credenciales
        ) VALUES (
            :nombre, :apellido, :email, :telefono, :direccion,
            :numerodocumento, :tdoc, :tipo_usuario, :credenciales
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

    // Determinar a qué tabla específica insertar según el rol del usuario
    $tabla_rol = match($rol_desc) {
        'Docente' => 'docente',
        'Administrador' => 'admin',
        'Estudiante SS' => 'estudiante_ss',
        default => throw new Exception("Rol no soportado", 400)
    };

    // Insertar en la tabla específica del rol usando solo la columna `usuario_id`
    $stmt_rol_insert = $pdo->prepare("
        INSERT INTO $tabla_rol (usuario_id)
        VALUES (:usuario_id)
    ");
    $stmt_rol_insert->execute([ ':usuario_id' => $usuarios_id ]);

    // Confirmar la transacción
    $pdo->commit();
    
    // Respuesta exitosa
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
    // Revertir si hubo transacción iniciada
    if ($transactionStarted) $pdo->rollBack();
    $response['message'] = "Error en base de datos: " . $e->getMessage();
    http_response_code(500);
} catch (Exception $e) {
    if ($transactionStarted) $pdo->rollBack();
    $response['message'] = $e->getMessage();
    http_response_code($e->getCode() ?: 400);
}

// Retornar JSON al frontend
echo json_encode($response);
?>
