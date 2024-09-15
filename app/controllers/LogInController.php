<?php
// controllers/UserController.php
require_once '../../core/database.php';
require_once '../../models/UserModel.php';

class LogInController {
    private $model;

    public function __construct($pdo) {
        $this->model = new UserModel($pdo);
    }

    public function login() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (isset($_POST['usuario']) && isset($_POST['inputPassword'])) {
                $usuario = $_POST['usuario'];
                $contrasena = $_POST['inputPassword'];

                $result = $this->model->getUserByUsername($usuario);

                if ($result) {
                    // Verifica la contraseña encriptada
                    if (password_verify($contrasena, $result['contrasena'])) {
                        session_start();
                        $_SESSION['idusuarios'] = $result['idusuarios'];
                        $_SESSION['user'] = $usuario;

                        $this->model->updateLastAccess($usuario);

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
            } else {
                echo "Por favor, complete todos los campos.";
            }
        }
    }
}
?>
