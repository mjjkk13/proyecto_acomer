<?php
// models/UserModel.php

class UserModel {
    private $conn;

    public function __construct($pdo) {
        $this->conn = $pdo;
    }

    public function getUserByUsername($username) {
        $sql = "SELECT u.idusuarios, c.user, c.contrasena, tu.rol 
                FROM credenciales c
                JOIN usuarios u ON c.idcredenciales = u.credenciales_idcredenciales
                JOIN tipo_usuario tu ON u.tipo_usuario_idtipo_usuario = tu.idtipo_usuario
                WHERE c.user = :user";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':user', $username);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updateLastAccess($username) {
        $sql = "UPDATE credenciales SET ultimoacceso = NOW() WHERE user = :user";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':user', $username);
        $stmt->execute();
    }
}
?>
