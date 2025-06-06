<?php 
session_start();
require_once 'conexion.php';

header('Content-Type: application/json; charset=utf-8');
require 'cors.php';


/**
 * @OA\Get(
 *     path="/getCursos",
 *     summary="Obtener cursos relacionados a un docente",
 *     description="Devuelve una lista de cursos que están asociados a un docente logueado en el sistema.",
 *     tags={"Cursos"},
 *     security={{"cookieAuth": {}}},
 *     @OA\Response(
 *         response=200,
 *         description="Lista de cursos del docente.",
 *         @OA\JsonContent(
 *             type="array",
 *             @OA\Items(
 *                 type="object",
 *                 @OA\Property(property="idcursos", type="integer", description="ID del curso"),
 *                 @OA\Property(property="nombrecurso", type="string", description="Nombre del curso")
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="No autenticado. El usuario no ha iniciado sesión.",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="status", type="string", example="error"),
 *             @OA\Property(property="message", type="string", example="No se ha iniciado sesión.")
 *         )
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="No se encontró un docente relacionado con el usuario.",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="status", type="string", example="error"),
 *             @OA\Property(property="message", type="string", example="No se encontró un docente relacionado con este usuario.")
 *         )
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Error al obtener los cursos desde la base de datos.",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="status", type="string", example="error"),
 *             @OA\Property(property="message", type="string", example="Error al obtener los cursos: {error_message}")
 *         )
 *     )
 * )
 */

// Verificar si el usuario está autenticado
if (!isset($_SESSION['idusuarios'])) {
    echo json_encode([
        'status' => 'error',
        'message' => 'No se ha iniciado sesión.'
    ]);
    exit;
}

$usuario_id = $_SESSION['idusuarios'];

try {
    // Obtener iddocente
    $sqlDocente = "SELECT iddocente FROM docente WHERE usuario_id = :usuario_id";
    $stmtDocente = $pdo->prepare($sqlDocente);
    $stmtDocente->bindParam(':usuario_id', $usuario_id, PDO::PARAM_INT);
    $stmtDocente->execute();
    $docente = $stmtDocente->fetch(PDO::FETCH_ASSOC);

    if (!$docente) {
        echo json_encode([
            'status' => 'error',
            'message' => 'No se encontró un docente relacionado con este usuario.'
        ]);
        exit;
    }

    $iddocente = $docente['iddocente'];

    // Obtener cursos
    $sql = "SELECT idcursos, nombrecurso FROM cursos WHERE docente_id = :docente_id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':docente_id', $iddocente, PDO::PARAM_INT);
    $stmt->execute();
    $cursos = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($cursos);
} catch (PDOException $e) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Error al obtener los cursos: ' . $e->getMessage()
    ]);
}
?>
