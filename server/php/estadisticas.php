<?php
/**
 * @OA\Get(
 *     path="/estadisticas",
 *     summary="Obtener estadísticas de escaneos de QR",
 *     description="Este endpoint devuelve las estadísticas diarias, semanales y mensuales del número de estudiantes presentes basados en los escaneos de códigos QR, filtrados por horarios de desayuno (7-9am), almuerzo (11:30am-1pm) y refrigerio (otros horarios hasta la 1pm).",
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
 *                     @OA\Property(property="desayuno", type="integer", example=20),
 *                     @OA\Property(property="almuerzo", type="integer", example=25),
 *                     @OA\Property(property="refrigerio", type="integer", example=5),
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
 *                     @OA\Property(property="desayuno", type="integer", example=140),
 *                     @OA\Property(property="almuerzo", type="integer", example=175),
 *                     @OA\Property(property="refrigerio", type="integer", example=35),
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
 *                     @OA\Property(property="desayuno", type="integer", example=400),
 *                     @OA\Property(property="almuerzo", type="integer", example=500),
 *                     @OA\Property(property="refrigerio", type="integer", example=100),
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
header('Content-Type: application/json; charset=utf-8');
require 'cors.php';


require_once 'conexion.php';
$pdo = getPDO(); 

try {
    // Estadísticas diarias
    $sqlDaily = "
        SELECT 
            DATE(fecha_escaneo) AS fecha,
            SUM(CASE WHEN TIME(fecha_escaneo) BETWEEN '07:00:00' AND '09:00:00' 
                THEN CAST(SUBSTRING_INDEX(SUBSTRING_INDEX(qr_code, 'Estudiantes presentes: ', -1), '\n', 1) AS UNSIGNED)
                ELSE 0 END) AS desayuno,
            SUM(CASE WHEN TIME(fecha_escaneo) BETWEEN '11:30:00' AND '13:00:00' 
                THEN CAST(SUBSTRING_INDEX(SUBSTRING_INDEX(qr_code, 'Estudiantes presentes: ', -1), '\n', 1) AS UNSIGNED)
                ELSE 0 END) AS almuerzo,
            SUM(CASE WHEN (TIME(fecha_escaneo) NOT BETWEEN '07:00:00' AND '09:00:00') 
                      AND (TIME(fecha_escaneo) NOT BETWEEN '11:30:00' AND '13:00:00')
                      AND TIME(fecha_escaneo) <= '13:00:00'
                THEN CAST(SUBSTRING_INDEX(SUBSTRING_INDEX(qr_code, 'Estudiantes presentes: ', -1), '\n', 1) AS UNSIGNED)
                ELSE 0 END) AS refrigerio,
            SUM(CAST(SUBSTRING_INDEX(SUBSTRING_INDEX(qr_code, 'Estudiantes presentes: ', -1), '\n', 1) AS UNSIGNED)) AS totalEstudiantes
        FROM qrescaneados
        WHERE TIME(fecha_escaneo) <= '13:00:00'
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
            SUM(CASE WHEN TIME(fecha_escaneo) BETWEEN '07:00:00' AND '09:00:00' 
                THEN CAST(SUBSTRING_INDEX(SUBSTRING_INDEX(qr_code, 'Estudiantes presentes: ', -1), '\n', 1) AS UNSIGNED)
                ELSE 0 END) AS desayuno,
            SUM(CASE WHEN TIME(fecha_escaneo) BETWEEN '11:30:00' AND '13:00:00' 
                THEN CAST(SUBSTRING_INDEX(SUBSTRING_INDEX(qr_code, 'Estudiantes presentes: ', -1), '\n', 1) AS UNSIGNED)
                ELSE 0 END) AS almuerzo,
            SUM(CASE WHEN (TIME(fecha_escaneo) NOT BETWEEN '07:00:00' AND '09:00:00') 
                      AND (TIME(fecha_escaneo) NOT BETWEEN '11:30:00' AND '13:00:00')
                      AND TIME(fecha_escaneo) <= '13:00:00'
                THEN CAST(SUBSTRING_INDEX(SUBSTRING_INDEX(qr_code, 'Estudiantes presentes: ', -1), '\n', 1) AS UNSIGNED)
                ELSE 0 END) AS refrigerio,
            SUM(CAST(SUBSTRING_INDEX(SUBSTRING_INDEX(qr_code, 'Estudiantes presentes: ', -1), '\n', 1) AS UNSIGNED)) AS totalEstudiantes
        FROM qrescaneados
        WHERE TIME(fecha_escaneo) <= '13:00:00'
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
            SUM(CASE WHEN TIME(fecha_escaneo) BETWEEN '07:00:00' AND '09:00:00' 
                THEN CAST(SUBSTRING_INDEX(SUBSTRING_INDEX(qr_code, 'Estudiantes presentes: ', -1), '\n', 1) AS UNSIGNED)
                ELSE 0 END) AS desayuno,
            SUM(CASE WHEN TIME(fecha_escaneo) BETWEEN '11:30:00' AND '13:00:00' 
                THEN CAST(SUBSTRING_INDEX(SUBSTRING_INDEX(qr_code, 'Estudiantes presentes: ', -1), '\n', 1) AS UNSIGNED)
                ELSE 0 END) AS almuerzo,
            SUM(CASE WHEN (TIME(fecha_escaneo) NOT BETWEEN '07:00:00' AND '09:00:00') 
                      AND (TIME(fecha_escaneo) NOT BETWEEN '11:30:00' AND '13:00:00')
                      AND TIME(fecha_escaneo) <= '13:00:00'
                THEN CAST(SUBSTRING_INDEX(SUBSTRING_INDEX(qr_code, 'Estudiantes presentes: ', -1), '\n', 1) AS UNSIGNED)
                ELSE 0 END) AS refrigerio,
            SUM(CAST(SUBSTRING_INDEX(SUBSTRING_INDEX(qr_code, 'Estudiantes presentes: ', -1), '\n', 1) AS UNSIGNED)) AS totalEstudiantes
        FROM qrescaneados
        WHERE TIME(fecha_escaneo) <= '13:00:00'
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