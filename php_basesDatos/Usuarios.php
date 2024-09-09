<?php
include("conexion.php");
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Assuming you have a PDO connection $pdo already established
$stmt = $pdo->query('SELECT 
       
    c.user AS nombre_usuario_credenciales,
    c.contrasena,
    tu.rol AS tipo_usuario_rol,
    c.ultimoacceso
FROM 
    usuarios u
    LEFT JOIN tipo_usuario tu ON u.tipo_usuario_idtipo_usuario = tu.idtipo_usuario
    LEFT JOIN credenciales c ON u.credenciales_idcredenciales = c.idcredenciales;');
$credenciales = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($credenciales);
?>