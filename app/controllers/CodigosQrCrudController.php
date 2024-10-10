<?php
require_once '../models/CodigosQrCrudModel.php';

class CodigosQrCrudController {
    private $model;

    public function __construct() {
        $this->model = new CodigosQrCrudModel();
    }

    // Método para obtener los códigos QR
    public function obtenerCodigos() {
        $codigos = $this->model->obtenerCodigosQR();
        header('Content-Type: application/json');
        echo json_encode($codigos);
    }

    // Método para eliminar un código QR
    public function eliminarCodigo() {
        if (isset($_POST['idqrgenerados'])) {
            $idQrGenerados = $_POST['idqrgenerados'];
            $eliminado = $this->model->eliminarCodigoQR($idQrGenerados);

            if ($eliminado) {
                echo json_encode(['success' => true, 'message' => 'Registro eliminado con éxito.']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Error al eliminar el código QR.']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'ID no proporcionado.']);
        }
    }

    // Método principal para dirigir la solicitud
    public function index() {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $this->obtenerCodigos();
        } elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['eliminar'])) {
            $this->eliminarCodigo();
        }
    }
}

$controller = new CodigosQrCrudController();
$controller->index();
?>
