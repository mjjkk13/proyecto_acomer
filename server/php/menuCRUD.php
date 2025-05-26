<?php
// ===============================
// ENCABEZADOS PARA API JSON + CORS
// ===============================
require 'cors.php';
require_once 'conexion.php';

// ===============================
// OpenAPI Annotations
// ===============================

/**
 * @OA\Post(
 *     path="/menu",
 *     summary="Crea, lee, actualiza o elimina un menú",
 *     description="Permite crear, leer, actualizar o eliminar un menú en la base de datos.",
 *     operationId="menuOperations",
 *     tags={"Menu"},
 *     requestBody={
 *         @OA\MediaType(
 *             mediaType="application/json",
 *             @OA\Schema(
 *                 type="object",
 *                 @OA\Property(
 *                     property="action",
 *                     type="string",
 *                     description="Acción a realizar: create, read, update, delete"
 *                 ),
 *                 @OA\Property(
 *                     property="tipomenu",
 *                     type="string",
 *                     description="Tipo de menú a crear, leer, actualizar o eliminar"
 *                 ),
 *                 @OA\Property(
 *                     property="fecha",
 *                     type="string",
 *                     format="date",
 *                     description="Fecha asociada al menú"
 *                 ),
 *                 @OA\Property(
 *                     property="descripcion",
 *                     type="string",
 *                     description="Descripción del menú"
 *                 ),
 *                 @OA\Property(
 *                     property="idmenu",
 *                     type="integer",
 *                     description="ID del menú a actualizar o eliminar"
 *                 )
 *             )
 *         )
 *     },
 *     responses={
 *         @OA\Response(
 *             response=200,
 *             description="Respuesta exitosa con los resultados de la operación",
 *             @OA\JsonContent(
 *                 @OA\Property(
 *                     property="success",
 *                     type="boolean",
 *                     description="Indicador de si la operación fue exitosa"
 *                 ),
 *                 @OA\Property(
 *                     property="message",
 *                     type="string",
 *                     description="Mensaje de éxito de la operación"
 *                 )
 *             )
 *         ),
 *         @OA\Response(
 *             response=400,
 *             description="Error de solicitud, por ejemplo, acción no válida o falta de datos",
 *             @OA\JsonContent(
 *                 @OA\Property(
 *                     property="success",
 *                     type="boolean",
 *                     description="Indicador de error"
 *                 ),
 *                 @OA\Property(
 *                     property="error",
 *                     type="string",
 *                     description="Descripción del error"
 *                 )
 *             )
 *         ),
 *         @OA\Response(
 *             response=500,
 *             description="Error en el servidor o en la base de datos",
 *             @OA\JsonContent(
 *                 @OA\Property(
 *                     property="success",
 *                     type="boolean",
 *                     description="Indicador de error en el servidor"
 *                 ),
 *                 @OA\Property(
 *                     property="message",
 *                     type="string",
 *                     description="Descripción del error en el servidor"
 *                 )
 *             )
 *         )
 *     }
 * )
 */

// ===============================
// LÓGICA PRINCIPAL
// ===============================
$action = $_POST['action'] ?? '';

switch ($action) {
  case 'create':
    // =======================
    // Crear un nuevo menú
    // =======================
    $tipomenu = $_POST['tipomenu'] ?? '';
    $fecha = $_POST['fecha'] ?? '';
    $descripcion = $_POST['descripcion'] ?? '';

    $stmt = $pdo->prepare("INSERT INTO menu (tipomenu, fecha, descripcion) VALUES (?, ?, ?)");
    $success = $stmt->execute([$tipomenu, $fecha, $descripcion]);

    echo json_encode(['success' => $success, 'message' => 'Menú creado correctamente']);
    break;

  case 'read':
    // =======================
    // Leer los menús
    // =======================
    if (isset($_POST['tipomenu'])) {
      $stmt = $pdo->prepare("SELECT * FROM menu WHERE tipomenu = ? ORDER BY fecha DESC");
      $stmt->execute([$_POST['tipomenu']]);
    } else {
      $stmt = $pdo->query("SELECT * FROM menu ORDER BY fecha DESC");
    }
    $menus = $stmt->fetchAll();
    echo json_encode(['success' => true, 'data' => $menus]);
    break;

  case 'update':
    // =======================
    // Actualizar un menú
    // =======================
    $idmenu = $_POST['idmenu'] ?? '';
    $tipomenu = $_POST['tipomenu'] ?? '';
    $fecha = $_POST['fecha'] ?? '';
    $descripcion = $_POST['descripcion'] ?? '';

    $stmt = $pdo->prepare("UPDATE menu SET tipomenu = ?, fecha = ?, descripcion = ? WHERE idmenu = ?");
    $success = $stmt->execute([$tipomenu, $fecha, $descripcion, $idmenu]);

    echo json_encode(['success' => $success, 'message' => 'Menú actualizado correctamente']);
    break;

  case 'delete':
    // =======================
    // Eliminar un menú
    // =======================
    $idmenu = $_POST['idmenu'] ?? '';
    $stmt = $pdo->prepare("DELETE FROM menu WHERE idmenu = ?");
    $success = $stmt->execute([$idmenu]);

    echo json_encode(['success' => $success, 'message' => 'Menú eliminado correctamente']);
    break;

  default:
    // =======================
    // Acción no válida
    // =======================
    echo json_encode(['success' => false, 'error' => 'Acción no válida']);
    break;
}
?>
