<?php
/**
 * @OA\Get(
 *     path="/usuario",
 *     tags={"Usuario"},
 *     summary="Obtener datos del usuario autenticado",
 *     description="Devuelve los datos personales del usuario que ha iniciado sesión mediante la sesión PHP.",
 *     @OA\Response(
 *         response=200,
 *         description="Datos del usuario obtenidos correctamente",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(
 *                 property="idusuarios",
 *                 type="integer",
 *                 example=3
 *             ),
 *             @OA\Property(
 *                 property="nombre",
 *                 type="string",
 *                 example="Juan"
 *             ),
 *             @OA\Property(
 *                 property="apellido",
 *                 type="string",
 *                 example="Pérez"
 *             ),
 *             @OA\Property(
 *                 property="email",
 *                 type="string",
 *                 example="juan@example.com"
 *             ),
 *             @OA\Property(
 *                 property="telefono",
 *                 type="string",
 *                 example="3101234567"
 *             ),
 *             @OA\Property(
 *                 property="direccion",
 *                 type="string",
 *                 example="Calle Falsa 123"
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="Usuario no autenticado",
 *         @OA\JsonContent(
 *             @OA\Property(
 *                 property="status",
 *                 type="string",
 *                 example="error"
 *             ),
 *             @OA\Property(
 *                 property="message",
 *                 type="string",
 *                 example="Usuario no autenticado"
 *             )
 *         )
 *     )
 * )
 * 
 * @OA\Post(
 *     path="/usuario",
 *     tags={"Usuario"},
 *     summary="Actualizar datos del usuario autenticado",
 *     requestBody={
 *         "required": true,
 *         "content": {
 *             "application/json": {
 *                 "schema": {
 *                     "type": "object",
 *                     "properties": {
 *                         "nombre": {
 *                             "type": "string",
 *                             "example": "Juan"
 *                         },
 *                         "apellido": {
 *                             "type": "string",
 *                             "example": "Pérez"
 *                         },
 *                         "email": {
 *                             "type": "string",
 *                             "example": "juan@example.com"
 *                         },
 *                         "telefono": {
 *                             "type": "string",
 *                             "example": "3101234567"
 *                         },
 *                         "direccion": {
 *                             "type": "string",
 *                             "example": "Calle Falsa 123"
 *                         },
 *                         "nuevaContraseña": {
 *                             "type": "string",
 *                             "example": "NuevaContraseñaSegura123"
 *                         }
 *                     }
 *                 }
 *             }
 *         }
 *     },
 *     @OA\Response(
 *         response=200,
 *         description="Datos actualizados correctamente",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(
 *                 property="status",
 *                 type="string",
 *                 example="success"
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Error en la entrada o datos faltantes"
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="Usuario no autenticado"
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Error en la base de datos"
 *     )
 * )
 */

session_start();  // Iniciar la sesión

header('Content-Type: application/json; charset=utf-8');
require_once 'cors.php';


require_once __DIR__ . '/conexion.php';

if (!isset($pdo)) {
    echo json_encode(['status' => 'error', 'message' => 'La conexión a la base de datos no se ha establecido']);
    exit();
}

if (isset($_SESSION['idusuarios'])) {
    $id_usuario = $_SESSION['idusuarios'];

    try {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $sql = "SELECT idusuarios, nombre, apellido, email, telefono, direccion 
                    FROM usuarios 
                    WHERE idusuarios = :idusuarios";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':idusuarios', $id_usuario, PDO::PARAM_INT);
            $stmt->execute();

            $userData = $stmt->fetch(PDO::FETCH_ASSOC);
            echo json_encode($userData ? $userData : []);
            exit();
        } elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $datosActualizados = json_decode(file_get_contents("php://input"), true);

            if (!$datosActualizados) {
                echo json_encode(['status' => 'error', 'message' => 'No se recibieron datos']);
                exit();
            }

            $sql = "UPDATE usuarios 
                    SET nombre = :nombre, 
                        apellido = :apellido, 
                        email = :email, 
                        telefono = :telefono, 
                        direccion = :direccion 
                    WHERE idusuarios = :idusuarios";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':nombre', $datosActualizados['nombre'], PDO::PARAM_STR);
            $stmt->bindParam(':apellido', $datosActualizados['apellido'], PDO::PARAM_STR);
            $stmt->bindParam(':email', $datosActualizados['email'], PDO::PARAM_STR);
            $stmt->bindParam(':telefono', $datosActualizados['telefono'], PDO::PARAM_STR);
            $stmt->bindParam(':direccion', $datosActualizados['direccion'], PDO::PARAM_STR);
            $stmt->bindParam(':idusuarios', $id_usuario, PDO::PARAM_INT);
            $stmt->execute();

            if (!empty($datosActualizados['nuevaContraseña'])) {
                $nuevaContraseña = password_hash($datosActualizados['nuevaContraseña'], PASSWORD_BCRYPT);

                $sql = "SELECT credenciales FROM usuarios WHERE idusuarios = :idusuarios";
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':idusuarios', $id_usuario, PDO::PARAM_INT);
                $stmt->execute();
                $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($usuario) {
                    $idcredenciales = $usuario['credenciales'];

                    $sql = "UPDATE credenciales 
                            SET contrasena = :contrasena 
                            WHERE idcredenciales = :idcredenciales";
                    $stmt = $pdo->prepare($sql);
                    $stmt->bindParam(':contrasena', $nuevaContraseña, PDO::PARAM_STR);
                    $stmt->bindParam(':idcredenciales', $idcredenciales, PDO::PARAM_INT);
                    $stmt->execute();
                } else {
                    echo json_encode(['status' => 'error', 'message' => 'Usuario no encontrado']);
                    exit();
                }
            }

            echo json_encode(['status' => 'success']);
            exit();
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Método no permitido']);
            exit();
        }
    } catch (PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => 'Error en la base de datos: ' . $e->getMessage()]);
        exit();
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Usuario no autenticado']);
    exit();
}
?>
