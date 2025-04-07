<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once 'conexion.php';
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: http://localhost:5173');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');
header('Access-Control-Allow-Credentials: true');
header('Cache-Control: public, max-age=300');

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        // Solo selecciona los campos de qrgenerados
        $sqlSelect = "SELECT idqrgenerados, codigoqr, fechageneracion
                      FROM qrgenerados
                      ORDER BY fechageneracion DESC";

        try {
            $stmt = $pdo->query($sqlSelect);
            $qrs = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode(['success' => true, 'data' => $qrs]);
        } catch (PDOException $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Error al obtener los datos: ' . $e->getMessage()]);
        }
        break;

    case 'POST':
        $input = json_decode(file_get_contents('php://input'), true);

        if (!isset($input['eliminar']) || !isset($input['idqrgenerados'])) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Faltan parámetros']);
            exit;
        }

        $idQrGenerados = $input['idqrgenerados'];

        try {
            $sqlDelete = "DELETE FROM qrgenerados WHERE idqrgenerados = :idqrgenerados";
            $stmt = $pdo->prepare($sqlDelete);
            $stmt->bindParam(':idqrgenerados', $idQrGenerados, PDO::PARAM_INT);
            $stmt->execute();

            echo json_encode(['success' => true, 'message' => 'QR eliminado correctamente']);
        } catch (PDOException $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Error al eliminar: ' . $e->getMessage()]);
        }
        break;

    default:
        http_response_code(405);
        echo json_encode(['success' => false, 'message' => 'Método no permitido']);
        break;
}
?>
