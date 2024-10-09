<?php
require_once(__DIR__ ."/../config/config.php");

class Database {
    private $host = DB_HOST;
    private $user = DB_USER;
    private $pass = DB_PASS;
    private $dbname = DB_NAME;
    private $charset = DB_CHARSET;
    private $url = BASE_URL;
    private $pdo;
    private $error;
    private $stmt;

    public function __construct() {
        // Configurar la conexión
        $dsn = "mysql:host=" . $this->host . ";dbname=" . $this->dbname . ";";
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];

        // Crear una nueva instancia de PDO
        try {
            $this->pdo = new PDO($dsn, $this->user, $this->pass, $options);
        } catch (PDOException $e) {
            $this->error = $e->getMessage();
            echo "Error de conexión: " . $this->error;
        }
    }

    public function getConnection(): PDO {
        return $this->pdo;
    }

    // Método para preparar una consulta
    public function query($sql) {
        $this->stmt = $this->pdo->prepare($sql);
    }

    // Vincular valores
    public function bind($param, $value, $type = null) {
        if (is_null($type)) {
            switch (true) {
                case is_int($value):
                    $type = PDO::PARAM_INT;
                    break;
                case is_bool($value):
                    $type = PDO::PARAM_BOOL;
                    break;
                case is_null($value):
                    $type = PDO::PARAM_NULL;
                    break;
                default:
                    $type = PDO::PARAM_STR;
            }
        }
        $this->stmt->bindValue($param, $value, $type);
    }

    // Ejecutar la consulta
    public function execute() {
        return $this->stmt->execute();
    }

    // Obtener un solo registro
    public function single() {
        $this->execute();
        return $this->stmt->fetch();
    }

    // Obtener múltiples registros
    public function resultSet() {
        $this->execute();
        return $this->stmt->fetchAll();
    }
    public function prepare($sql) {

        return $this->pdo->prepare($sql);

    }
}
?>