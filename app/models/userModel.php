<?php
class UserModel {
    private $db;

    // Constructor para inicializar la conexión de la base de datos
    public function __construct(PDO $db) {
        $this->db = $db;
    }

    // Método para obtener el usuario por nombre de usuario
    public function getUserByUsername($username) {
        try {
            $sql = "SELECT u.idusuarios, c.user, c.contrasena, tu.rol 
                    FROM credenciales c
                    JOIN usuarios u ON c.idcredenciales = u.credenciales_idcredenciales
                    JOIN tipo_usuario tu ON u.tipo_usuario_idtipo_usuario = tu.idtipo_usuario
                    WHERE c.user = :user";

            // Preparar la consulta
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':user', $username);
            $stmt->execute();

            // Retornar el resultado como array asociativo
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Error en la consulta: " . $e->getMessage();
        }
    }

    // Método para actualizar el último acceso
    public function updateLastAccess($username) {
        try {
            $sql = "UPDATE credenciales SET ultimoacceso = NOW() WHERE user = :user";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':user', $username);
            $stmt->execute();
        } catch (PDOException $e) {
            echo "Error al actualizar el último acceso: " . $e->getMessage();
        }
    }
}
