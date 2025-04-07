<?php
// ===============================
// ENCABEZADOS PARA API JSON + CORS
// ===============================
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: http://localhost:5173'); // ajusta esto según tu front
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type, Authorization');
header('Access-Control-Allow-Credentials: true');

// ===============================
// CONEXIÓN A LA BASE DE DATOS
// ===============================
$host = 'localhost';
$db = 'acomer';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
  PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
  PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

try {
  $pdo = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
  echo json_encode(['success' => false, 'error' => 'DB error: ' . $e->getMessage()]);
  exit;
}

// ===============================
// LÓGICA PRINCIPAL
// ===============================
$action = $_POST['action'] ?? '';

switch ($action) {
  case 'create':
    $tipomenu = $_POST['tipomenu'] ?? '';
    $fecha = $_POST['fecha'] ?? '';
    $descripcion = $_POST['descripcion'] ?? '';

    $stmt = $pdo->prepare("INSERT INTO menu (tipomenu, fecha, descripcion) VALUES (?, ?, ?)");
    $success = $stmt->execute([$tipomenu, $fecha, $descripcion]);

    echo json_encode(['success' => $success]);
    break;

  case 'read':
    if (isset($_POST['tipomenu'])) {
      $stmt = $pdo->prepare("SELECT * FROM menu WHERE tipomenu = ? ORDER BY fecha DESC");
      $stmt->execute([$_POST['tipomenu']]);
    } else {
      $stmt = $pdo->query("SELECT * FROM menu ORDER BY fecha DESC");
    }
    $menus = $stmt->fetchAll();
    echo json_encode($menus);
    break;

  case 'update':
    $idmenu = $_POST['idmenu'] ?? '';
    $tipomenu = $_POST['tipomenu'] ?? '';
    $fecha = $_POST['fecha'] ?? '';
    $descripcion = $_POST['descripcion'] ?? '';

    $stmt = $pdo->prepare("UPDATE menu SET tipomenu = ?, fecha = ?, descripcion = ? WHERE idmenu = ?");
    $success = $stmt->execute([$tipomenu, $fecha, $descripcion, $idmenu]);

    echo json_encode(['success' => $success]);
    break;

  case 'delete':
    $idmenu = $_POST['idmenu'] ?? '';
    $stmt = $pdo->prepare("DELETE FROM menu WHERE idmenu = ?");
    $success = $stmt->execute([$idmenu]);

    echo json_encode(['success' => $success]);
    break;

  default:
    echo json_encode(['success' => false, 'error' => 'Acción no válida']);
    break;
}
