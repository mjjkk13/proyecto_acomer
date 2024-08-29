<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

include('conexion.php');

$action = $_POST['action'] ?? '';
$mealType = $_POST['mealType'] ?? ''; // Obtener el tipo de menú desde POST

function getNextDate($dayName) {
    $daysOfWeek = ['domingo', 'lunes', 'martes', 'miércoles', 'jueves', 'viernes', 'sábado'];
    $today = new DateTime();
    $todayDayIndex = $today->format('w'); // 0 = domingo, 1 = lunes, ..., 6 = sábado
    $targetDayIndex = array_search(strtolower($dayName), $daysOfWeek);

    if ($targetDayIndex === false) return null; // Día no válido

    // Calcular la diferencia de días
    $daysUntilTarget = ($targetDayIndex - $todayDayIndex + 7) % 7;
    if ($daysUntilTarget === 0) $daysUntilTarget += 7; // Asegurarse de que la fecha sea del próximo día

    $targetDate = (clone $today)->modify("+$daysUntilTarget days");
    return $targetDate->format('Y-m-d'); // Formato YYYY-MM-DD
}

try {
    $conn = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    switch ($action) {
        case 'create':
            $descripcion = $_POST['descripcion'] ?? '';
            $fecha = $_POST['fecha'] ?? date('Y-m-d');
            $query = "INSERT INTO menu (tipomenu, fecha, descripcion) VALUES (:mealType, :fecha, :descripcion)";
            $stmt = $conn->prepare($query);
            $stmt->execute(['mealType' => $mealType, 'fecha' => $fecha, 'descripcion' => $descripcion]);
            echo json_encode(['success' => 'Menú creado exitosamente.']);
            break;

        case 'read':
            $query = "SELECT idmenu, tipomenu, fecha, descripcion FROM menu WHERE tipomenu = :mealType ORDER BY fecha DESC";
            $stmt = $conn->prepare($query);
            $stmt->execute(['mealType' => $mealType]);
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode($data);
            break;

        case 'update':
            $idmenu = $_POST['idmenu'] ?? 0;
            $descripcion = $_POST['descripcion'] ?? '';
            $fecha = $_POST['fecha'] ?? date('Y-m-d'); // Obtener la fecha o usar la actual por defecto
            $query = "UPDATE menu SET descripcion = :descripcion, fecha = :fecha WHERE idmenu = :idmenu";
            $stmt = $conn->prepare($query);
            $stmt->execute(['descripcion' => $descripcion, 'fecha' => $fecha, 'idmenu' => $idmenu]);
            echo json_encode(['success' => 'Menú actualizado exitosamente.']);
            break;
            

        case 'delete':
            $idmenu = $_POST['idmenu'] ?? 0;
            $query = "DELETE FROM menu WHERE idmenu = :idmenu";
            $stmt = $conn->prepare($query);
            $stmt->execute(['idmenu' => $idmenu]);
            echo json_encode(['success' => 'Menú eliminado exitosamente.']);
            break;

        default:
            echo json_encode(['error' => 'Acción no válida.']);
            break;
    }
} catch (PDOException $e) {
    echo json_encode(['error' => 'Error de conexión: ' . $e->getMessage()]);
}
?>
