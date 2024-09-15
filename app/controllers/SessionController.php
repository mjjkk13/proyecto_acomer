<?php
// controllers/SessionController.php

class SessionController {
    public function logout() {
        session_start();
        session_unset();
        session_destroy();
        header("Location: ../views/iniciarSesion.html"); // Redirige a la página de inicio de sesión
        exit();
    }
}
?>
