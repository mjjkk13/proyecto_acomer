<?php
require_once 'conexion.php';
require 'cors.php';

// Establecer zona horaria de Bogotá
date_default_timezone_set('America/Bogota');

$pdo = getPDO(); 
try {
    $sql = "SELECT fecha_escaneo, qr_code FROM qrescaneados ORDER BY fecha_escaneo DESC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();

    $resultados = [];

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $qr = $row['qr_code'];
        $fecha = $row['fecha_escaneo'];

        if (empty($qr)) continue;

        // Extraer el nombre del curso
        preg_match('/Curso:\s*(Curso\s*\d+)/i', $qr, $matchCurso);
        // Extraer la cantidad de estudiantes presentes
        preg_match('/Estudiantes presentes:\s*(\d+)/i', $qr, $matchCantidad);

        $curso = isset($matchCurso[1]) ? trim($matchCurso[1]) : 'Curso desconocido';
        $cantidad = isset($matchCantidad[1]) ? (int)$matchCantidad[1] : 0;

        $resultados[] = [
            'curso' => $curso,
            'cantidad' => $cantidad,
            'fecha' => date("Y-m-d H:i:s", strtotime($fecha)) // Formateado con zona horaria de Bogotá
        ];
    }

    echo json_encode($resultados);
} catch (PDOException $e) {
    echo json_encode(['error' => 'Error en la consulta: ' . $e->getMessage()]);
}
?>
