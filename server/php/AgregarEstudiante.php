<?php
session_start();
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: http://localhost:5173');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');
header('Access-Control-Allow-Credentials: true');

include 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);

    if (!isset($data['nombreEstudiante'], $data['apellidoEstudiante'])) {
        echo json_encode(['status' => 'error', 'message' => 'Faltan datos']);
        exit;
    }

    if (!isset($_SESSION['idusuarios'])) {
        echo json_encode(['status' => 'error', 'message' => 'Usuario no autenticado']);
        exit;
    }

    $nombre = trim($data['nombreEstudiante']);
    $apellido = trim($data['apellidoEstudiante']);
    $idUsuario = $_SESSION['idusuarios'];

    try {
        // Obtener ID y curso del estudiante
        $stmt1 = $pdo->prepare("SELECT idalumno, curso_id FROM alumnos WHERE nombre = :nombre AND apellido = :apellido");
        $stmt1->bindParam(':nombre', $nombre);
        $stmt1->bindParam(':apellido', $apellido);
        $stmt1->execute();
        $alumno = $stmt1->fetch(PDO::FETCH_ASSOC);

        if (!$alumno) {
            echo json_encode(['status' => 'error', 'message' => 'Estudiante no encontrado']);
            exit;
        }

        // Obtener el nombre del curso (opcional)
        $stmtCurso = $pdo->prepare("SELECT nombreCurso FROM cursos WHERE idCursos = :idCurso");
        $stmtCurso->bindParam(':idCurso', $alumno['idCursos']);
        $stmtCurso->execute();
        $nombreCurso = $stmtCurso->fetchColumn() ?? 'Curso desconocido';

        // Obtener ID del estudiante SS desde el usuario
        $stmt2 = $pdo->prepare("SELECT idestudiante_ss FROM estudiante_ss WHERE usuario_id = :usuario_id");
        $stmt2->bindParam(':usuario_id', $idUsuario);
        $stmt2->execute();
        $idestudiante_ss = $stmt2->fetchColumn();

        if (!$idestudiante_ss) {
            echo json_encode(['status' => 'error', 'message' => 'Estudiante SS no encontrado para el usuario']);
            exit;
        }

        // Construir el contenido a guardar como "qr_code" (aunque no sea un QR literal)
        $contenido = "Curso: $nombreCurso\nEstudiantes presentes: 1\nFecha: " . date('Y-m-d H:i:s');

        // Insertar el registro
        $stmt3 = $pdo->prepare("
            INSERT INTO qrescaneados (fecha_escaneo, estudiante_ss_id, qr_code)
            VALUES (NOW(), :estudiante_ss_id, :contenido)
        ");
        $stmt3->bindParam(':estudiante_ss_id', $idestudiante_ss);
        $stmt3->bindParam(':contenido', $contenido);
        $stmt3->execute();

        echo json_encode(['status' => 'success', 'message' => 'Escaneo registrado exitosamente.']);
    } catch (PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => 'Error BD: ' . $e->getMessage()]);
    }
}
