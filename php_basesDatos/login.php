<?php
include("conexion.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['usuario']) && isset($_POST['inputPassword'])) {
        $usuario = $_POST['usuario'];
        $contrasena = $_POST['inputPassword'];

        // Preparar la consulta SQL para evitar inyecciones SQL y obtener el tipo de usuario
        $sql = "SELECT u.user, u.passwordUsuario, t.nombreUsuario 
                FROM usuarios u 
                JOIN tipousuario t ON u.idTipoUsuario = t.idTipoUsuario 
                WHERE u.user = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $usuario);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Obtener la fila de resultados
            $row = $result->fetch_assoc();
            
            // Verificar la contraseña en texto plano
            if ($contrasena === $row['passwordUsuario']) {
                // Inicio de sesión exitoso
                session_start();
                $_SESSION['usuario'] = $usuario;
                
                // Redirigir según el rol del usuario
                switch ($row['nombreUsuario']) {
                    case 'Administrador':
                        header("Location: ../Php/Admin/index.html");
                        break;
                    case 'Estudiante':
                        header("Location: ../Php/Estudiante/index.html");
                        break;
                    case 'Docente':
                        header("Location: ../Php/Docente/index.html");
                        break;
                    default:
                        echo "Rol de usuario no reconocido";
                        break;
                }
            } else {
                // Contraseña incorrecta
                echo "Contraseña incorrecta";
            }
        } else {
            // Usuario no encontrado
            echo "Usuario no encontrado";
        }

        $stmt->close();
        $conn->close();
    } else {
        echo "Por favor, complete todos los campos.";
    }
} else {
    echo "Método de solicitud no válido.";
}
?>
