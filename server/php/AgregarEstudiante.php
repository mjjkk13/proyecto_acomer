<?php
/**
 * @OA\Info(
 *     title="API de Comedores Escolares",
 *     version="1.0.0",
 *     description="API para gestión de comedores escolares, incluyendo funciones para gestionar usuarios, menús, asistencia, etc."
 * )
 */
/**
 * @OA\Post(
 *     path="/registrar-escaneo",
 *     tags={"Escaneos"},
 *     summary="Registrar escaneo de asistencia por estudiante de servicio social",
 *     description="Registra un escaneo que indica la presencia de un estudiante en un curso determinado.",
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"nombreEstudiante", "apellidoEstudiante"},
 *             @OA\Property(property="nombreEstudiante", type="string", example="Juan"),
 *             @OA\Property(property="apellidoEstudiante", type="string", example="Pérez")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Escaneo registrado exitosamente.",
 *         @OA\JsonContent(
 *             @OA\Property(property="status", type="string", example="success"),
 *             @OA\Property(property="message", type="string", example="Escaneo registrado exitosamente.")
 *         )
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Datos faltantes o error de autenticación.",
 *         @OA\JsonContent(
 *             @OA\Property(property="status", type="string", example="error"),
 *             @OA\Property(property="message", type="string", example="Faltan datos o usuario no autenticado.")
 *         )
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Error interno del servidor o error en la base de datos.",
 *         @OA\JsonContent(
 *             @OA\Property(property="status", type="string", example="error"),
 *             @OA\Property(property="message", type="string", example="Error BD: descripción del error")
 *         )
 *     )
 * )
 */

session_start();
require 'cors.php';
include 'conexion.php';
$pdo = getPDO(); 

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
        $stmtCurso->bindParam(':idCurso', $alumno['curso_id']);
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

        // Construir el contenido a guardar como "qr_code"
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
?>
