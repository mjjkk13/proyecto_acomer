<?php
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: http://localhost:5173');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');
header('Access-Control-Allow-Credentials: true');
header('Cache-Control: public, max-age=300');

require_once 'conexion.php'; 

try {
    // Consulta ajustada para mostrar un solo código QR por curso
    $sql = "SELECT 
                MAX(a.fecha) AS fecha_hora,  -- Tomamos la fecha más reciente para cada curso
                c.nombrecurso, 
                q.codigoqr AS imagen
            FROM asistencia a
            INNER JOIN qrgenerados q ON a.qrgenerados_id = q.idqrgenerados
            INNER JOIN alumnos al ON a.alumno_id = al.idalumno
            INNER JOIN cursos c ON al.curso_id = c.idcursos
            WHERE a.qrgenerados_id IS NOT NULL  -- Solo registros que tengan un código QR generado
            GROUP BY c.nombrecurso, q.codigoqr  -- Agrupamos por nombre del curso y código QR
            ORDER BY fecha_hora DESC";  
            
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($result);
    
} catch (PDOException $e) {
    echo json_encode(["error" => $e->getMessage()]);
}
?>
