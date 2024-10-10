<?php
// Definición de rutas
// Incluye el archivo del controlador
require_once '../app/controllers/ViewsController.php';
require_once '../app/controllers/AuthController.php';
require_once '../app/controllers/SessionController.php';
require '../app/controllers/CodigoQrCrudController.php';

// Obtén la ruta de la solicitud
$requestUri = $_SERVER['REQUEST_URI'];

$request = $_SERVER['REQUEST_URI'];
$params = explode('/', trim($request, '/'));



$controller = !empty($params[0]) ? ucfirst($params[0]) . 'Controller' : 'ViewsController';
$action = !empty($params[1]) ? $params[1] : 'index';

if (file_exists('../controllers/' . $controller . '.php')) {
    include '../controllers/' . $controller . '.php';
    $controllerInstance = new $controller();
    if (method_exists($controllerInstance, $action)) {
        $controllerInstance->$action();
    } else {
        // Acción no encontrada
        header('HTTP/1.0 404 Not Found');
    }
} else {
    // Controlador no encontrado
    header('HTTP/1.0 404 Not Found');
}if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    require_once 'controllers/SessionController.php';
    $controller = new SessionController();
    $controller->logout();
}
?>