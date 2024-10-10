<?php
require_once '../../core/database.php';

class CodigosQrCrudModel {
    private $db;

    public function __construct() {
        // Crear una instancia de la clase Database
        $this->db = new Database();
    }

    // Método para obtener todos los códigos QR ordenados por fecha de generación
    public function obtenerCodigosQR() {
        try {
            // Preparar la consulta SQL
            $sql = "SELECT q.codigoqr, q.fechageneracion, q.idqrgenerados FROM qrgenerados q ORDER BY q.fechageneracion DESC";
            
            // Preparar la consulta
            $this->db->query($sql);
            
            // Ejecutar la consulta y obtener todos los resultados
            return $this->db->resultSet();
        } catch (PDOException $e) {
            // Loguea el error
            error_log('Error en obtenerCodigosQR: ' . $e->getMessage());
            return false;
        }
    }

    // Método para eliminar un código QR por su ID
    public function eliminarCodigoQR($idQrGenerados) {
        try {
            // Preparar la consulta SQL para eliminar un código QR
            $sql = "DELETE FROM qrgenerados WHERE idqrgenerados = :idqrgenerados";

            // Preparar la consulta
            $this->db->query($sql);
            
            // Vincular el parámetro
            $this->db->bind(':idqrgenerados', $idQrGenerados);

            // Ejecutar la consulta
            return $this->db->execute();
        } catch (PDOException $e) {
            // Loguea el error
            error_log('Error en eliminarCodigoQR: ' . $e->getMessage());
            return false;
        }
    }
}
