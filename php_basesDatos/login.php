<?php
include("conexion.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['usuario']) && isset($_POST['inputPassword'])) {
        $usuario = $_POST['usuario'];
        $contrasena = $_POST['inputPassword'];

        try {
            $conn = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Preparar la consulta SQL para evitar inyecciones SQL y obtener el tipo de usuario
            $sql = "SELECT u.user, u.password, t.rol 
                    FROM usuarios u 
                    JOIN tipousuario t ON u.tipo_usuario_idtipo_usuario = t.idtipo_usuario 
                    WHERE u.user = :user";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':user', $usuario);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($result) {
                // Verificar la contraseña en texto plano
                if (password_verify($contrasena, $result['password'])) {
                    // Inicio de sesión exitoso
                    session_start();
                    $_SESSION['user'] = $usuario;

                    // Redirigir según el rol del usuario
                    switch ($result['nombreUsuario']) {
                        case 'Admin':
                            header("Location: ../Php/Admin/index.html");
                            break;
                        case 'Estudiante SS':
                            header("Location: ../Php/Estudiante/index.html");
                            break;
                        case 'Docente':
                            header("Location: ../Php/Docente/index.html");
                            break;
                    }
                    exit();
                } else {
                    echo "Contraseña incorrecta.";
                }
            } else {
                echo "Usuario no encontrado.";
            }
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    } else {
        echo "Por favor, complete todos los campos.";
    }
}
?>