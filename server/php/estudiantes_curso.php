<?php
/**
 * @OA\Get(
 *     path="/obtener-estudiantes",
 *     summary="Obtener estudiantes por curso",
 *     description="Este endpoint devuelve una lista de estudiantes de un curso específico.",
 *     tags={"Estudiantes"},
 *     @OA\Parameter(
 *         name="curso_id",
 *         in="query",
 *         description="ID del curso para obtener los estudiantes",
 *         required=true,
 *         @OA\Schema(
 *             type="integer",
 *             example=101
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Estudiantes obtenidos correctamente",
 *         @OA\JsonContent(
 *             type="array",
 *             @OA\Items(
 *                 type="object",
 *                 @OA\Property(property="idalumno", type="integer", example=1),
 *                 @OA\Property(property="nombre", type="string", example="Juan"),
 *                 @OA\Property(property="apellido", type="string", example="Pérez")
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Falta el ID del curso",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="status", type="string", example="error"),
 *             @OA\Property(property="message", type="string", example="Falta el id del curso")
 *         )
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Error en la base de datos",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="status", type="string", example="error"),
 *             @OA\Property(property="message", type="string", example="Error al obtener los estudiantes: [mensaje de error]")
 *         )
 *     )
 * )
 */
require_once 'conexion.php';
require 'cors.php';

if (!isset($_GET['curso_id'])) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Falta el id del curso'
    ]);
    exit;
}

$curso_id = $_GET['curso_id'];

try {
    $sql = "
        SELECT 
            idalumno, 
            nombre, 
            apellido 
        FROM alumnos 
        WHERE curso_id = :curso_id
    ";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':curso_id', $curso_id, PDO::PARAM_INT);
    $stmt->execute();

    $estudiantes = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($estudiantes);

} catch (PDOException $e) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Error al obtener los estudiantes: ' . $e->getMessage()
    ]);
}
?>
