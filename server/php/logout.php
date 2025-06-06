<?php
/**
 * @OA\Post(
 *     path="/logout",
 *     summary="Cerrar sesión",
 *     description="Este endpoint permite a los usuarios cerrar su sesión. Destruye la sesión activa y retorna un mensaje de éxito.",
 *     tags={"Autenticación"},
 *     @OA\Response(
 *         response=200,
 *         description="Sesión cerrada exitosamente",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="success", type="boolean", example=true)
 *         )
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Error en el servidor",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="success", type="boolean", example=false),
 *             @OA\Property(property="message", type="string", example="Error en el servidor. Por favor, intente más tarde.")
 *         )
 *     )
 * )
 */

// Asegurar que la respuesta sea siempre JSON
header('Content-Type: application/json; charset=utf-8');
require 'cors.php';


session_start();
session_destroy();

header('Content-Type: application/json');
echo json_encode(['success' => true]);
?>
