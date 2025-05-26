<?php
/**
 * @OA\Get(
 *     path="/estadisticas",
 *     summary="Obtener estadísticas de escaneos de QR",
 *     description="Este endpoint devuelve las estadísticas diarias, semanales y mensuales del número de estudiantes presentes basados en los escaneos de códigos QR.",
 *     tags={"Estadísticas"},
 *     @OA\Response(
 *         response=200,
 *         description="Estadísticas obtenidas correctamente",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(
 *                 property="daily",
 *                 type="array",
 *                 @OA\Items(
 *                     type="object",
 *                     @OA\Property(property="fecha", type="string", example="2025-04-24"),
 *                     @OA\Property(property="totalEstudiantes", type="integer", example=50)
 *                 )
 *             ),
 *             @OA\Property(
 *                 property="weekly",
 *                 type="array",
 *                 @OA\Items(
 *                     type="object",
 *                     @OA\Property(property="semana", type="integer", example=16),
 *                     @OA\Property(property="mes", type="string", example="Abril"),
 *                     @OA\Property(property="totalEstudiantes", type="integer", example=350)
 *                 )
 *             ),
 *             @OA\Property(
 *                 property="monthly",
 *                 type="array",
 *                 @OA\Items(
 *                     type="object",
 *                     @OA\Property(property="mes", type="integer", example=4),
 *                     @OA\Property(property="nombre_mes", type="string", example="Abril"),
 *                     @OA\Property(property="totalEstudiantes", type="integer", example=1000)
 *                 )
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Error interno del servidor",
 *         @OA\JsonContent(
 *             @OA\Property(property="error", type="string", example="Error en la base de datos: [mensaje de error]")
 *         )
 *     )
 * )
 */
require 'cors.php';
require_once 'conexion.php';

try {
    // Estadísticas diarias
    $sqlDaily = "
        SELECT 
            DATE(fecha_escaneo) AS fecha,
            SUM(CAST(
                SUBSTRING_INDEX(
                    SUBSTRING_INDEX(qr_code, 'Estudiantes presentes: ', -1), 
                '\n', 1
            ) AS UNSIGNED)) AS totalEstudiantes
        FROM qrescaneados
        GROUP BY DATE(fecha_escaneo)
        ORDER BY fecha ASC
    ";
    $stmtDaily = $pdo->prepare($sqlDaily);
    $stmtDaily->execute();
    $dailyData = $stmtDaily->fetchAll(PDO::FETCH_ASSOC);

    // Estadísticas semanales
    $sqlWeekly = "
        SELECT 
            WEEK(fecha_escaneo, 1) AS semana,
            MONTHNAME(MIN(fecha_escaneo)) AS mes,
            SUM(CAST(
                SUBSTRING_INDEX(
                    SUBSTRING_INDEX(qr_code, 'Estudiantes presentes: ', -1), 
                '\n', 1
            ) AS UNSIGNED)) AS totalEstudiantes
        FROM qrescaneados
        GROUP BY WEEK(fecha_escaneo, 1)
        ORDER BY MIN(fecha_escaneo) ASC
    ";
    $stmtWeekly = $pdo->prepare($sqlWeekly);
    $stmtWeekly->execute();
    $weeklyData = $stmtWeekly->fetchAll(PDO::FETCH_ASSOC);

    // Estadísticas mensuales
    $sqlMonthly = "
        SELECT 
            MONTH(fecha_escaneo) AS mes,
            MONTHNAME(MIN(fecha_escaneo)) AS nombre_mes,
            SUM(CAST(
                SUBSTRING_INDEX(
                    SUBSTRING_INDEX(qr_code, 'Estudiantes presentes: ', -1), 
                '\n', 1
            ) AS UNSIGNED)) AS totalEstudiantes
        FROM qrescaneados
        GROUP BY MONTH(fecha_escaneo)
        ORDER BY mes ASC
    ";
    $stmtMonthly = $pdo->prepare($sqlMonthly);
    $stmtMonthly->execute();
    $monthlyData = $stmtMonthly->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        'daily' => $dailyData,
        'weekly' => $weeklyData,
        'monthly' => $monthlyData
    ]);

} catch (PDOException $e) {
    echo json_encode([
        'error' => 'Error en la base de datos: ' . $e->getMessage()
    ]);
}
?>
