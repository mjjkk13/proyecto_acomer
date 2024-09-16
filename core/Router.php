<?php
class Router {
    public function route() {
        // Obtener la URL solicitada
        $url = isset($_GET['url']) ? rtrim($_GET['url'], '/') : '';
        $url = filter_var($url, FILTER_SANITIZE_URL);
        $url = explode('/', $url);

        // Definir controlador y método por defecto
        $controllerName = !empty($url[0]) ? ucfirst($url[0]) . 'Controller' : 'ViewsController';
        $methodName = isset($url[1]) ? $url[1] : 'index';

        // Verificar si el controlador existe
        if (file_exists("../app/controllers/$controllerName.php")) {
            require_once "../app/controllers/$controllerName.php";
            $controller = new $controllerName();

            // Verificar si el método existe
            if (method_exists($controller, $methodName)) {
                $controller->{$methodName}();
            } else {
                echo "Método $methodName no encontrado en el controlador $controllerName.";
            }
        } else {
            echo "Controlador $controllerName no encontrado.";
        }
    }
}
?>
