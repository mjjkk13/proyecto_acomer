<?php
require '../vendor/autoload.php';
include 'registrarUsuario.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener datos del formulario
    $user = $_POST['user'];
    $contrasena = $_POST['contrasena'];
    $correo = $_POST['correo'];

    // Configurar y enviar el correo
    $mail = new PHPMailer(true);
    try {
        // Configuración del servidor SMTP
        $mail->SMTPDebug = 2; 
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'marianajimenezv2006@gmail.com';
        $mail->Password = 'irbmipajfuswuoyl'; // Considera usar una contraseña de aplicación
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS; // Utiliza la encriptación correcta
        $mail->Port = 465;

        // Remitente y destinatario
        $mail->setFrom('no-reply@acomer.com', 'A Comer');
        $mail->addAddress($correo); // Correo del usuario registrado

        // Contenido del correo
        $mail->isHTML(true);
        $mail->Subject = 'Detalles de tu Registro';
        $mail->Body    = "Hola $user,<br><br>Te has registrado exitosamente.<br><br>Tu nombre de usuario es: <b>$user</b><br>Tu contraseña es: <b>$contrasena</b><br><br>Gracias por registrarte.";
        $mail->AltBody = "Hola $user,\n\nTe has registrado exitosamente.\n\nTu nombre de usuario es: $user\nTu contraseña es: $contrasena\n\nGracias por registrarte.";

        $mail->send();
        echo "El registro fue exitoso y se envió un correo a $correo.";
    } catch (Exception $e) {
        echo "El correo no pudo ser enviado. Error: {$mail->ErrorInfo}";
    }
}
?>
