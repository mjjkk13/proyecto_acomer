<?php
// 1. Configuración CORS - Debe ser lo primero en el archivo
require 'cors.php';
header('Content-Type: application/json; charset=utf-8');

// 2. Manejar preflight (OPTIONS) request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// 3. Cargar dependencias y configuración
require 'vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// 4. Cargar variables de entorno
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

// 5. Validar variables de entorno requeridas
$requiredEnvVars = ['EMAIL_USER', 'EMAIL_PASSWORD'];
foreach ($requiredEnvVars as $var) {
    if (!isset($_ENV[$var])) {
        error_log("Falta la variable de entorno: $var");
        http_response_code(500);
        die(json_encode(['success' => false, 'message' => 'Error de configuración del servidor']));
    }
}

// 6. Función para validar email
function isValidEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

// 7. Función para crear plantilla HTML
function createCredentialsTemplate($usuario, $contrasena) {
    return '
    <!DOCTYPE html>
    <html>
    <head>
      <meta charset="UTF-8">
      <style>
        .container { font-family: Arial; max-width: 600px; color: #1f2937; }
        .header { color: #2563eb; }
        .credentials { background-color: #f3f4f6; padding: 1rem; border-radius: 0.5rem; }
        .warning { color: #dc2626; font-size: 0.875rem; }
      </style>
    </head>
    <body>
      <div class="container">
        <h2 class="header">¡Bienvenido a Acomer!</h2>
        <p>Tus credenciales de acceso:</p>
        <div class="credentials">
          <p><strong>Usuario:</strong> '.htmlspecialchars($usuario).'</p>
          <p><strong>Contraseña temporal:</strong> '.htmlspecialchars($contrasena).'</p>
        </div>
        <p class="warning">⚠️ Por seguridad, cambia tu contraseña inmediatamente después del primer acceso.</p>
      </div>
    </body>
    </html>
    ';
}

// 8. Función principal para enviar credenciales
function enviarCredenciales($destinatario, $usuario, $contrasena) {
    if (!isValidEmail($destinatario)) {
        return ['success' => false, 'error' => 'Formato de correo electrónico inválido'];
    }

    $mail = new PHPMailer(true);

    try {
        // Configuración del servidor SMTP
        $mail->isSMTP();
        $mail->Host = $_ENV['SMTP_HOST'] ?? 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = $_ENV['EMAIL_USER'];
        $mail->Password = $_ENV['EMAIL_PASSWORD'];
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port = $_ENV['SMTP_PORT'] ?? 465;
        $mail->CharSet = 'UTF-8';
        $mail->Timeout = 10;

        // Remitente y destinatario
        $mail->setFrom($_ENV['EMAIL_USER'], 'Sistema Acomer');
        $mail->addAddress($destinatario);
        if (isset($_ENV['EMAIL_SUPPORT'])) {
            $mail->addReplyTo($_ENV['EMAIL_SUPPORT'], 'Soporte Acomer');
        }

        // Contenido del correo
        $mail->isHTML(true);
        $mail->Subject = 'Credenciales de Acceso - Sistema Acomer';
        $mail->Body = createCredentialsTemplate($usuario, $contrasena);
        $mail->Priority = 1; // Alta prioridad

        $mail->send();
        
        error_log('Correo enviado exitosamente a: ' . $destinatario);
        return ['success' => true, 'messageId' => $mail->getLastMessageID()];
        
    } catch (Exception $e) {
        error_log('Error enviando credenciales: ' . $e->getMessage());
        return [
            'success' => false,
            'error' => $_ENV['APP_ENV'] === 'production' 
                ? 'Error al enviar el correo' 
                : $e->getMessage()
        ];
    }
}

// 9. Manejo de la solicitud POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener datos del cuerpo de la solicitud
    $input = file_get_contents('php://input');
    $data = json_decode($input, true);
    
    // Validar si el JSON se decodificó correctamente
    if (json_last_error() !== JSON_ERROR_NONE) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Formato JSON inválido']);
        exit;
    }
    
    // Validación básica
    if (empty($data['destinatario']) || empty($data['usuario']) || empty($data['contrasena'])) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Faltan campos requeridos']);
        exit;
    }
    
    $resultado = enviarCredenciales($data['destinatario'], $data['usuario'], $data['contrasena']);
    
    if ($resultado['success']) {
        echo json_encode(['success' => true, 'message' => 'Correo enviado exitosamente']);
    } else {
        http_response_code(500);
        echo json_encode([
            'success' => false,
            'message' => 'Error al enviar correo',
            'error' => $resultado['error']
        ]);
    }
    exit;
}

// 10. Si se accede directamente al archivo sin POST
http_response_code(405);
echo json_encode(['success' => false, 'message' => 'Método no permitido']);
?>