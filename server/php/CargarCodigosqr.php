<?php
/**
 * @OA\Get(
 *     path="/ultimos-qrs",
 *     tags={"Asistencia"},
 *     summary="Obtener el último código QR generado por curso",
 *     description="Devuelve la fecha más reciente y el código QR más reciente registrado por curso.",
 *     @OA\Response(
 *         response=200,
 *         description="Lista de cursos con el QR más reciente",
 *         @OA\JsonContent(
 *             type="array",
 *             @OA\Items(
 *                 type="object",
 *                 @OA\Property(
 *                     property="fecha_hora",
 *                     type="string",
 *                     format="date-time",
 *                     example="2024-09-10 08:00:00"
 *                 ),
 *                 @OA\Property(
 *                     property="nombrecurso",
 *                     type="string",
 *                     example="Curso 901"
 *                 ),
 *                 @OA\Property(
 *                     property="imagen",
 *                     type="string",
 *                     example="base64imagenQR"
 *                 )
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Error al realizar la consulta a la base de datos",
 *         @OA\JsonContent(
 *             @OA\Property(
 *                 property="error",
 *                 type="string",
 *                 example="Error en la base de datos"
 *             )
 *         )
 *     )
 * )
 */

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: http://localhost:5173');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');
header('Access-Control-Allow-Credentials: true');
header('Cache-Control: public, max-age=300');

require_once 'conexion.php';

try {
    $sql = "
        SELECT 
            sub.fecha_hora,
            sub.nombrecurso,
            sub.imagen
        FROM (
            SELECT 
                MAX(a.fecha) AS fecha_hora,
                c.nombrecurso,
                q.codigoqr AS imagen,
                RANK() OVER (PARTITION BY c.idcursos ORDER BY a.fecha DESC) AS rnk
            FROM asistencia a
            INNER JOIN qrgenerados q ON a.qrgenerados_id = q.idqrgenerados
            INNER JOIN alumnos al ON a.alumno_id = al.idalumno
            INNER JOIN cursos c ON al.curso_id = c.idcursos
            WHERE a.qrgenerados_id IS NOT NULL
        ) AS sub
        WHERE sub.rnk = 1
        ORDER BY sub.fecha_hora DESC
    ";

    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($result);
    
} catch (PDOException $e) {
    echo json_encode(["error" => $e->getMessage()]);
}
?>
