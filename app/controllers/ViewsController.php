<?php

class ViewsController {
    // Método para cargar una vista
    public function index() {
        // Cargar una vista de ejemplo
        $this->loadView('index');
        
    }

    // Método para cargar una vista específica
    public function loadView($view) {
        $viewPath = '../app/views/' . $view . '.php';
        
        if (file_exists($viewPath)) {
            require_once $viewPath;
        } else {
            die("La vista '$view' no existe.");
        }
    }

    // Método para redirigir a iniciarSesion.php
    public function ShowLogIn() {
        header("Location: ../app/views/iniciarSesion.php");
        exit(); // Es importante detener la ejecución después de una redirección
    }
}

?>
