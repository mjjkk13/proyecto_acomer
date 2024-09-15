<?php
include("../../core/database.php");
include("../../config/config.php");
$host = 'localhost';
$db ='acomer';
$user = 'root';
$pass = 'toor';
$charset = 'utf8mb4';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['usuario']) && isset($_POST['inputPassword'])) {
        $usuario = $_POST['usuario'];
        $contrasena = $_POST['inputPassword'];

        try {
            // Conexión a la base de datos
            $conn = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Consulta SQL para obtener los datos, incluido el id_usuario
            $sql = "SELECT u.idusuarios, c.user, c.contrasena, tu.rol 
                    FROM credenciales c
                    JOIN usuarios u ON c.idcredenciales = u.credenciales_idcredenciales
                    JOIN tipo_usuario tu ON u.tipo_usuario_idtipo_usuario = tu.idtipo_usuario
                    WHERE c.user = :user";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':user', $usuario);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            // Mostrar valores para depuración
            echo "Usuario ingresado: " . htmlspecialchars($usuario) . "<br>";
            echo "Contraseña ingresada: " . htmlspecialchars($contrasena) . "<br>";
            echo "Hash almacenado: " . htmlspecialchars($result['contrasena']) . "<br>";

            if ($result) {
                // Verifica la contraseña encriptada
                if (password_verify($contrasena, $result['contrasena'])) {
                    echo "Contraseña verificada exitosamente.";
                    // Inicio de sesión exitoso
                    session_start();
                    // Guardar el id_usuario en la sesión
                    $_SESSION['idusuarios'] = $result['idusuarios'];
                    $_SESSION['user'] = $usuario;

                    // Actualizar la fecha y hora del último acceso
                    $updateSql = "UPDATE credenciales SET ultimoacceso = NOW() WHERE user = :user";
                    $updateStmt = $conn->prepare($updateSql);
                    $updateStmt->bindParam(':user', $usuario);
                    $updateStmt->execute();

                    // Redirigir según el rol del usuario
                    switch ($result['rol']) {
                        case 'Administrador':
                            header("Location: ../views/Admin/index.html");
                            break;
                        case 'Estudiante SS':
                            header("Location: ../views/Estudiante/index.html");
                            break;
                        case 'Docente':
                            header("Location: ../views/Docente/index.html");
                            break;
                        default:
                            echo "Rol no reconocido.";
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
