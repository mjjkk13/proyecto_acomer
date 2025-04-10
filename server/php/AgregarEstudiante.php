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
        // Primero, obtener el id del alumno (ahora es "idalumno" en la tabla "alumnos")
        $stmt1 = $pdo->prepare("SELECT idalumno FROM alumnos WHERE nombre = :nombre AND apellido = :apellido");
        $stmt1->bindParam(':nombre', $nombre);
        $stmt1->bindParam(':apellido', $apellido);
        $stmt1->execute();

        // Obtener el resultado (id del alumno)
        $idalumno = $stmt1->fetchColumn();

        if ($idalumno) {
            // Si se encuentra el alumno, actualizar la tabla "asistencia"
            // Ahora la columna se llama "alumno_id"
            $stmt2 = $pdo->prepare("UPDATE asistencia SET fecha = NOW(), estado = :estado WHERE alumno_id = :idalumno");
            $stmt2->bindParam(':estado', $estado);
            $stmt2->bindParam(':idalumno', $idalumno);
            $stmt2->execute();

            // Verificar si se actualizó algún registro
            if ($stmt2->rowCount() > 0) {
                $response['status'] = 'success';
                $response['message'] = 'Asistencia actualizada correctamente.';

                // 👇 NUEVA LÓGICA PARA ACTUALIZAR LA TABLA estadisticasqr
                if ($estado === 1) {
                    $fechaHoy = date("Y-m-d");

                    // Verificar si ya existe un registro para esa fecha
                    $check = $pdo->prepare("SELECT idestadisticasqr FROM estadisticasqr WHERE fecha = :fecha");
                    $check->execute([':fecha' => $fechaHoy]);

                    if ($check->rowCount() > 0) {
                        // Si existe, actualizamos sumando 1
                        $update = $pdo->prepare("UPDATE estadisticasqr SET estudiantes_q_asistieron = estudiantes_q_asistieron + 1 WHERE fecha = :fecha");
                        $update->execute([':fecha' => $fechaHoy]);
                    } else {
                        // Si no existe, creamos un nuevo registro
                        $insert = $pdo->prepare("INSERT INTO estadisticasqr (fecha, estudiantes_q_asistieron) VALUES (:fecha, 1)");
                        $insert->execute([':fecha' => $fechaHoy]);
                    }
                }
            } else {
                $response['message'] = 'El estado de asistencia ya ha sido registrado previamente para este estudiante.';
            }
        } else {
            $response['message'] = 'El estudiante no está registrado en la base de datos.';
        }
    } catch (PDOException $e) {
        $response['message'] = 'Error en la base de datos: ' . $e->getMessage();
    }

    // Devolver la respuesta en formato JSON
    echo json_encode($response);
}
?>
