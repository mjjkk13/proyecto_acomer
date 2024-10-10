<?php
// Activar el manejo de errores para desarrollo
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Cargar todos los archivos necesarios (Modelos, Controladores, etc.)
// Usar un autoloader en lugar de incluir archivos manualmente es una buena práctica

require_once '../core/model.php';
require_once '../config/config.php';
require_once '../app/controllers/AuthController.php';
require_once '../app/controllers/ViewsController.php';
require_once '../config/Routes.php';

// Cargar configuración de rutas
require_once '../core/Router.php';

// Inicializar la conexión a la base de datos
require_once '../core/database.php';

// Iniciar el enrutador
$router = new Router();
$router->route();

// Obtener la URL y manejar el enrutamiento
$url = isset($_GET['url']) ? rtrim($_GET['url'], '/') : 'public/index';
$url = explode('/', $url);

// Verificar que la ruta existe en la configuración
$controllerName = ucfirst($url[0]) . 'Controller';
$method = isset($url[1]) ? $url[1] : 'Views';

// // Verificar si el controlador existe
// $controllerPath = '../app/controllers/' . $controllerName . '.php';
// if (file_exists($controllerPath)) {
//     require_once $controllerPath;
//     $controller = new $controllerName($pdo);

//     // Verificar si el método existe en el controlador
//     if (method_exists($controller, $method)) {
//         $controller->{$method}();
//         $controller->login();
//     } else {
//         // Define handleError function
//         function handleError($message) {
//             echo $message;
//         }
//         handleError("Método '$method' no encontrado en el controlador '$controllerName'.");
//     }
// } else {
//     handleError("Controlador '$controllerName' no encontrado.");
// }