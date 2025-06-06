<?php
/**
 * @OA\Get(
 *     path="/session",
 *     tags={"Sesión"},
 *     summary="Verificar sesión activa",
 *     description="Verifica si el usuario tiene una sesión activa. Si es así, devuelve los detalles del usuario y su rol.",
 *     @OA\Response(
 *         response=200,
 *         description="Sesión activa, detalles del usuario y rol",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(
 *                 property="success",
 *                 type="boolean",
 *                 example=true
 *             ),
 *             @OA\Property(
 *                 property="usuario",
 *                 type="string",
 *                 example="juan.perez"
 *             ),
 *             @OA\Property(
 *                 property="rol",
 *                 type="string",
 *                 example="Administrador"
 *             ),
 *             @OA\Property(
 *                 property="message",
 *                 type="string",
 *                 example="Inicio de sesión exitoso"
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="No hay sesión activa",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(
 *                 property="success",
 *                 type="boolean",
 *                 example=false
 *             ),
 *             @OA\Property(
 *                 property="message",
 *                 type="string",
 *                 example="No hay sesión activa"
 *             )
 *         )
 *     )
 * )
 */

header('Content-Type: application/json; charset=utf-8');
require 'cors.php';


session_start();

if (isset($_SESSION['usuario']) && isset($_SESSION['rol'])) {
    echo json_encode([
        'success' => true,
        'usuario' => $_SESSION['usuario'],
        'rol' => $_SESSION['rol'],
        'message' => 'Inicio de sesión exitoso'
    ]);
} else {
    echo json_encode([
        'success' => false,
        'message' => 'No hay sesión activa'
    ]);
}
?>
