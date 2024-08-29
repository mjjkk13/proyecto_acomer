<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include 'conexion.php'; 

header('Content-Type: application/json');


if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $sqlSelect = "SELECT a.idasistencia, a.fecha, q.codigoqr, q.fechageneracion
                  FROM asistencia a
                  JOIN qrgenerados q ON a.qrgenerados_idqrgenerados = q.idqrgenerados
                  ORDER BY a.fecha DESC";

    try {
        $stmt = $pdo->query($sqlSelect);
        $codigos = array();

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $codigos[] = array(
                'id_asistencia' => $row['idasistencia'],
                'fecha_hora' => $row['fechageneracion'],
                'imagen' => $row['codigoqr']
            );
        }

        echo json_encode($codigos);
    } catch (PDOException $e) {
        echo json_encode(['error' => 'Error en SELECT: ' . $e->getMessage()]);
    }
}

// Crear un nuevo registro 
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['crear'])) {
    $codigoQR = $_POST['codigoqr'];
    $fechaGeneracion = $_POST['fechageneracion'];

    $sqlInsert = "INSERT INTO qrgenerados (codigoqr, fechageneracion) VALUES (:codigoqr, :fechageneracion)";

    try {
        $stmt = $pdo->prepare($sqlInsert);
        $stmt->bindParam(':codigoqr', $codigoQR, PDO::PARAM_STR);
        $stmt->bindParam(':fechageneracion', $fechaGeneracion, PDO::PARAM_STR);
        $stmt->execute();

        echo json_encode(['success' => true, 'message' => 'Nuevo registro creado exitosamente.']);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Error en CREATE: ' . $e->getMessage()]);
    }
}

// Actualizar un registro existente 
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['actualizar'])) {
    $idAsistencia = $_POST['id_asistencia'];
    $codigoQR = $_POST['codigoqr'];
    $fecha = $_POST['fecha'];

    $sqlUpdate = "UPDATE qrgenerados SET codigoqr = :codigoqr, fechageneracion = :fecha WHERE idqrgenerados = :id_asistencia";

    try {
        $stmt = $pdo->prepare($sqlUpdate);
        $stmt->bindParam(':id_asistencia', $idAsistencia, PDO::PARAM_INT);
        $stmt->bindParam(':codigoqr', $codigoQR, PDO::PARAM_STR);
        $stmt->bindParam(':fecha', $fecha, PDO::PARAM_STR);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            echo json_encode(['success' => true, 'message' => 'Registro actualizado con éxito.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'No se encontró el registro a actualizar.']);
        }
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Error en UPDATE: ' . $e->getMessage()]);
    }
}

// Eliminar un registro 
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['eliminar'])) {
    $idAsistencia = $_POST['id_asistencia'];

    $sqlDelete = "DELETE FROM asistencia WHERE idasistencia = :id_asistencia";

    try {
        $stmt = $pdo->prepare($sqlDelete);
        $stmt->bindParam(':id_asistencia', $idAsistencia, PDO::PARAM_INT);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            echo json_encode(['success' => true, 'message' => 'Registro eliminado con éxito.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'No se encontró el registro a eliminar.']);
        }
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Error en DELETE: ' . $e->getMessage()]);
    }
}

$pdo = null; 
?>
