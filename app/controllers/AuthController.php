<?php
require_once "../models/UserModel.php";
require_once "../../core/Database.php";

class AuthController {
    private $db;

    public function __construct() {
        // Inicializar la conexión a la base de datos
        $this->db = new Database();
    }

    public function login() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (!empty($_POST['usuario']) && !empty($_POST['inputPassword'])) {
                $usuario = $_POST['usuario'];
                $contrasena = $_POST['inputPassword'];

                // Inicializar el modelo de usuario pasando la conexión de la base de datos
                $userModel = new UserModel($this->db);
                $result = $userModel->getUserByUsername($usuario);

                if ($result) {
                    // Verificar la contraseña
                    if (password_verify($contrasena, $result['contrasena'])) {
                        // Inicio de sesión exitoso
                        session_start();
                        $_SESSION['idusuarios'] = $result['idusuarios'];
                        $_SESSION['user'] = $usuario;

                        // Actualizar el último acceso
                        $userModel->updateLastAccess($usuario);

                        // Redirigir según el rol del usuario
                        switch ($result['rol']) {
                            case 'Administrador':
                                header("Location: ../views/Admin/index.php");
                                break;
                            case 'Estudiante SS':
                                header("Location: ../views/Estudiante/index.php");
                                break;
                            case 'Docente':
                                header("Location: ../views/Docente/index.php");
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
            } else {
                echo "Por favor, complete todos los campos.";
            }
        }
    }
}

// Crear una instancia del controlador y ejecutar el método de login
$authController = new AuthController();
$authController->login();
