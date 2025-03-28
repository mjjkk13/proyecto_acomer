<?php
// Asegurar que la respuesta sea siempre JSON
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: http://localhost:5173'); 
header('Access-Control-Allow-Methods: POST, GET, OPTIONS'); 
header('Access-Control-Allow-Headers: Content-Type, Authorization'); 
header('Access-Control-Allow-Credentials: true'); 

// Incluir el archivo de conexión
include 'conexion.php';

// Verificar si la solicitud es POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Leer el cuerpo de la solicitud en formato JSON
    $data = json_decode(file_get_contents("php://input"), true);

    // Verificar si los valores existen antes de usarlos
    if (!isset($data['nombreEstudiante'], $data['apellidoEstudiante'], $data['estado'])) {
        echo json_encode(['status' => 'error', 'message' => 'Faltan datos en la solicitud']);
        exit;
    }

    // Recibir los datos del formulario
    $nombre = trim($data['nombreEstudiante']);
    $apellido = trim($data['apellidoEstudiante']);
    $estado = $data['estado'] === 'si' ? 1 : 0;

    $response = ['status' => 'error', 'message' => ''];

    try {
        // Primero, obtener el idalumnos
        $stmt1 = $pdo->prepare("SELECT idalumnos FROM alumnos WHERE nombre = :nombre AND apellido = :apellido");
        $stmt1->bindParam(':nombre', $nombre);
        $stmt1->bindParam(':apellido', $apellido);
        $stmt1->execute();

        // Obtener el resultado
        $idalumnos = $stmt1->fetchColumn();

        if ($idalumnos) {
            // Si se encuentra el idalumnos, proceder a actualizar la tabla asistencia
            $stmt2 = $pdo->prepare("UPDATE asistencia SET fecha = NOW(), estado = :estado WHERE alumnos_idalumnos = :idalumnos");
            $stmt2->bindParam(':estado', $estado);
            $stmt2->bindParam(':idalumnos', $idalumnos);
            $stmt2->execute();

            // Verificar si se actualizó algún registro
            if ($stmt2->rowCount() > 0) {
                $response['status'] = 'success';
                $response['message'] = 'Asistencia actualizada correctamente.';
            } else {
                $response['message'] = 'No se encontró el registro de asistencia para actualizar.';
            }
        } else {
            $response['message'] = 'No se encontró el estudiante.';
        }
    } catch (PDOException $e) {
        $response['message'] = 'Error en la base de datos: ' . $e->getMessage();
    }

    // Devolver la respuesta en formato JSON
    echo json_encode($response);
}
?>
