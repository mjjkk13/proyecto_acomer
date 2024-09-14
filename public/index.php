<?php
require_once '../core/Controller.php';
require_once '../core/Model.php';
require_once '../core/Database.php';
require_once '../config/config.php';

// Obtener la URL y manejar el enrutamiento
// $url = isset($_GET['url']) ? rtrim($_GET['url'], '/') : 'home/index';
// $url = explode('/', $url);

// $controllerName = ucfirst($url[0]) . 'Controller';
// $methodName = isset($url[1]) ? $url[1] : 'index';

// Cargar el controlador
// require_once '../app/controllers/' . $controllerName . '.php';
// $controller = new $controllerName();

// Llamar al método
// if (method_exists($controller, $methodName)) {
//     $controller->{$methodName}();
// } else {
//     die('Método no encontrado');
// }
//ESTE ARCHIVO LLAMA A LOS MÉTODOS
?>