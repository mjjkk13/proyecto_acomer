<?php
// require_once 'C:/xampp/htdocs/Proyecto/core/database.php'; Asegúrate de que la ruta es correcta

// try {
//     Obtener la conexión a la base de datos
//     $database = new Database();
//     $pdo = $database->getConnection();

//     $sqlDaily = 'SELECT DATE_FORMAT(fecha, "%Y-%m-%d") as fecha, 
//                         DATE_FORMAT(fecha, "%W") as dia, 
//                         SUM(estudiantesqasistieron) as totalEstudiantes 
//                  FROM estadisticasqr 
//                  GROUP BY fecha 
//                  ORDER BY fecha ASC';
//     $stmtDaily = $pdo->prepare($sqlDaily);
//     $stmtDaily->execute();
//     $dailyData = $stmtDaily->fetchAll(PDO::FETCH_ASSOC);

//     Consulta para calcular estadísticas semanales cada 5 días
//     $sqlWeekly = 'SELECT WEEK(fecha, 1) as semana, 
//                         MONTHNAME(fecha) as mes, 
//                         SUM(estudiantesqasistieron) as totalEstudiantes 
//                   FROM estadisticasqr 
//                   WHERE DAYOFWEEK(fecha) BETWEEN 2 AND 6
//                   GROUP BY semana 
//                   HAVING COUNT(fecha) = 5
//                   ORDER BY MIN(fecha) ASC';
//     $stmtWeekly = $pdo->prepare($sqlWeekly);
//     $stmtWeekly->execute();
//     $weeklyData = $stmtWeekly->fetchAll(PDO::FETCH_ASSOC);

//     $data = [
//         'daily' => $dailyData,
//         'weekly' => $weeklyData
//     ];

//     echo json_encode($data);
// } catch (PDOException $e) {
//     echo 'Error: ' . $e->getMessage();
// }
?>