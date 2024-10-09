<?php
session_start();

function checkUserRole($requiredRole) {
    if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== $requiredRole) {
        header("Location: C:/xampp/htdocs/Proyecto/app/views/unauthorized.php");
        exit();
    }
}
?>