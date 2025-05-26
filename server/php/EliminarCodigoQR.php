<?php
/**
 * @OA\Delete(
 *     path="/delete/qrcode",
 *     summary="Eliminar un código QR",
 *     description="Elimina un código QR de la base de datos utilizando su ID.",
 *     tags={"QR Codes"},
 *     @OA\Parameter(
 *         name="idqrgenerados",
 *         in="query",
 *         description="ID del código QR que se desea eliminar",
 *         required=true,
 *         @OA\Schema(
 *             type="integer",
 *             format="int64"
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Código QR eliminado correctamente",
 *         @OA\JsonContent(
 *             @OA\Property(
 *                 property="success",
 *                 type="boolean",
 *                 example=true
 *             ),
 *             @OA\Property(
 *                 property="message",
 *                 type="string",
 *                 example="Código QR eliminado correctamente"
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Solicitud incorrecta, falta el ID del código QR",
 *         @OA\JsonContent(
 *             @OA\Property(
 *                 property="error",
 *                 type="string",
 *                 example="Falta el ID del código QR"
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=405,
 *         description="Método no permitido",
 *         @OA\JsonContent(
 *             @OA\Property(
 *                 property="error",
 *                 type="string",
 *                 example="Método no permitido"
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Error interno del servidor",
 *         @OA\JsonContent(
 *             @OA\Property(
 *                 property="error",
 *                 type="string",
 *                 example="Error al ejecutar la consulta"
 *             )
 *         )
 *     )
 * )
 */

require 'cors.php';

// Verificar si el método de la solicitud es DELETE
if ($_SERVER['REQUEST_METHOD'] !== 'DELETE') {
    http_response_code(405); // Método no permitido
    echo json_encode(['error' => 'Método no permitido']);
    exit;
}

// Verificar si el parámetro 'idqrgenerados' está presente en la URL
if (!isset($_GET['idqrgenerados'])) {
    http_response_code(400); // Solicitud incorrecta
    echo json_encode(['error' => 'Falta el ID del código QR']);
    exit;
}

$id = intval($_GET['idqrgenerados']); // Obtener el ID y asegurarse de que sea un número entero

require 'conexion.php'; // Conexión a la base de datos

try {
    // Consulta para eliminar el código QR
    $sql = "DELETE FROM qrgenerados WHERE idqrgenerados = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        // Vincular el ID como parámetro de la consulta
        $stmt->bind_param("i", $id);

        // Ejecutar la consulta
        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Código QR eliminado correctamente']);
        } else {
            http_response_code(500); // Error interno del servidor
            echo json_encode(['error' => 'Error al ejecutar la consulta']);
        }

        $stmt->close(); // Cerrar la declaración preparada
    } else {
        http_response_code(500); // Error al preparar la consulta
        echo json_encode(['error' => 'Error al preparar la consulta']);
    }
} catch (Exception $e) {
    http_response_code(500); // Error interno del servidor
    echo json_encode(['error' => 'Error al conectar con la base de datos: ' . $e->getMessage()]);
} finally {
    $conn->close(); // Cerrar la conexión a la base de datos
}
?>
