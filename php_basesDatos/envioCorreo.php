<?php
require '../../vendor/autoload.php'; // Asegúrate de tener PHPMailer instalado

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener datos del formulario
    $user = $_POST['user'];
    $contrasena = $_POST['contrasena'];
    $correo = $_POST['correo'];

    // Aquí podrías guardar el usuario en una base de datos
    // ...

    // Configurar y enviar el correo
    $mail = new PHPMailer(true);
    try {
        // Configuración del servidor SMTP
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'marianajimenezv2006@gmail.com';
        $mail->Password = 'Mariana2018_';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Remitente y destinatario
        $mail->setFrom('no-reply@acomer.com', 'A Comer');
        $mail->addAddress($correo); // Correo del usuario registrado

        // Contenido del correo
        $mail->isHTML(true);
        $mail->Subject = 'Detalles de tu Registro';
        $mail->Body    = "Hola $nombre,<br><br>Te has registrado exitosamente.<br><br>Tu nombre de usuario es: <b>$user</b><br>Tu contraseña es: <b>$contrasena</b><br><br>Gracias por registrarte.";
        $mail->AltBody = "Hola $nombre,\n\nTe has registrado exitosamente.\n\nTu nombre de usuario es: $user\nTu contraseña es: $password\n\nGracias por registrarte.";

        $mail->send();
        echo "El registro fue exitoso y se envió un correo a $correo.";
    } catch (Exception $e) {
        echo "El correo no pudo ser enviado. Error: {$mail->ErrorInfo}";
    }
} else {
    echo "Método de solicitud no válido.";
}
?>
