<?php
// Asegurar que la respuesta sea siempre JSON
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: http://localhost:5173'); 
header('Access-Control-Allow-Methods: POST, GET, OPTIONS'); 
header('Access-Control-Allow-Headers: Content-Type, Authorization'); 
header('Access-Control-Allow-Credentials: true'); 

require 'conexion.php';

session_start();

// Función para manejar respuestas JSON
function sendJsonResponse($success, $message, $data = []) {
    echo json_encode(array_merge(
        ['success' => $success, 'message' => $message],
        $data
    ));
    exit;
}

// Manejar preflight request
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(204);
    exit;
}

// Verificar método POST
if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    sendJsonResponse(false, 'Método no permitido');
}

// Verificar datos recibidos
if (empty($_POST['usuario']) || empty($_POST['inputPassword'])) {
    sendJsonResponse(false, 'Por favor, complete todos los campos');
}

try {
    $usuario = trim($_POST['usuario']);
    $contrasena = $_POST['inputPassword'];

    // Verificar conexión a la base de datos
    if (!isset($pdo)) {
        throw new Exception('Error de conexión a la base de datos');
    }

    $sql = "SELECT u.idusuarios, c.user, c.contrasena, tu.rol 
            FROM credenciales c
            JOIN usuarios u ON c.idcredenciales = u.credenciales_idcredenciales
            JOIN tipo_usuario tu ON u.tipo_usuario_idtipo_usuario = tu.idtipo_usuario
            WHERE c.user = :user";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['user' => $usuario]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$result) {
        sendJsonResponse(false, 'Usuario no encontrado');
    }

    if (!password_verify($contrasena, $result['contrasena'])) {
        sendJsonResponse(false, 'Contraseña incorrecta');
    }

    // Login exitoso
    $_SESSION['idusuarios'] = $result['idusuarios'];
    $_SESSION['user'] = $usuario;
    $_SESSION['rol'] = $result['rol'];

    // Actualizar último acceso
    $updateStmt = $pdo->prepare("UPDATE credenciales SET ultimoacceso = NOW() WHERE user = :user");
    $updateStmt->execute(['user' => $usuario]);

    // Determinar URL de redirección
    $redirect_url = match($result['rol']) {
        'Administrador' => '/admin',
        'Estudiante SS' => '/estudiante',
        'Docente' => '/docente',
        default => '/'
    };

    sendJsonResponse(true, 'Login exitoso', [
        'rol' => $result['rol'],
        'redirect_url' => $redirect_url
    ]);

} catch (PDOException $e) {
    error_log("Error de BD: " . $e->getMessage());
    sendJsonResponse(false, 'Error en la base de datos. Por favor, intente más tarde.');
} catch (Exception $e) {
    error_log("Error general: " . $e->getMessage());
    sendJsonResponse(false, 'Error en el servidor. Por favor, intente más tarde.');
}
?>