<?php
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: http://localhost:5173');  
header('Access-Control-Allow-Credentials: true'); 
header("Access-Control-Allow-Methods: DELETE");
header("Access-Control-Allow-Headers: Content-Type");

if ($_SERVER['REQUEST_METHOD'] !== 'DELETE') {
    http_response_code(405); // Método no permitido
    echo json_encode(['error' => 'Método no permitido']);
    exit;
}

if (!isset($_GET['idqrgenerados'])) {
    http_response_code(400); // Solicitud incorrecta
    echo json_encode(['error' => 'Falta el ID del código QR']);
    exit;
}

$id = intval($_GET['idqrgenerados']);

require 'conexion.php'; // Asegúrate de tener tu conexión aquí

$sql = "DELETE FROM qrgenerados WHERE idqrgenerados = ?";
$stmt = $conn->prepare($sql);

if ($stmt) {
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Código QR eliminado correctamente']);
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Error al ejecutar la consulta']);
    }
    $stmt->close();
} else {
    http_response_code(500);
    echo json_encode(['error' => 'Error al preparar la consulta']);
}

$conn->close();
?>
