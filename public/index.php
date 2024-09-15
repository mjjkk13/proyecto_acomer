<?php
require_once '../core/model.php';
require_once '../core/database.php';
require_once '../config/config.php';
require_once 'core/database.php';
require_once 'controllers/UserController.php';

// Cargar configuración de rutas
require_once '../config/routes.php';

// Obtener la URL y manejar el enrutamiento
$url = isset($_GET['url']) ? rtrim($_GET['url'], '/') : 'views/index';
$url = explode('/', $url);

// Verificar que la ruta existe en la configuración
$controllerName = ucfirst($url[0]) . 'Controller';
$method = isset($url[1]) ? $url[1] : 'index';

// Verificar si el controlador existe
$controllerPath = '../app/controllers/' . $controllerName . '.php';
if (file_exists($controllerPath)) {
    require_once $controllerPath;
    $controller = new $controllerName($pdo);

    // Verificar si el método existe en el controlador
    if (method_exists($controller, $method)) {
        $controller->{$method}();
        $controller->login();
    } else {
        // Define handleError function
        function handleError($message) {
            echo $message;
        }
        handleError("Método '$method' no encontrado en el controlador '$controllerName'.");
    }
} else {
    handleError("Controlador '$controllerName' no encontrado.");
}