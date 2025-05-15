<?php
session_start();
require_once 'conexion.php';

// Encabezados CORS y configuración
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: http://localhost:5173');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');
header('Access-Control-Allow-Credentials: true');
header('Cache-Control: public, max-age=300');

// Manejo de solicitud OPTIONS para CORS
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit();
}

// Captura de datos JSON si existen
if ($_SERVER['CONTENT_TYPE'] === 'application/json') {
    $json_data = file_get_contents('php://input');
    $data = json_decode($json_data, true);
    if ($data !== null) {
        $_POST = array_merge($_POST, $data);
    }
}

// Determinar la acción
$action = $_GET['action'] ?? $_POST['action'] ?? null;
if (!$action) {
    echo json_encode(['error' => 'No se especificó ninguna acción.']);
    exit();
}

try {
    switch ($action) {
        /**
         * @OA\Post(
         *     path="/curso",
         *     summary="Crear un curso",
         *     description="Crea un curso nuevo asignado a un docente.",
         *     tags={"Cursos"},
         *     @OA\RequestBody(
         *         required=true,
         *         content={
         *             "application/x-www-form-urlencoded": {
         *                 @OA\Property(property="nombrecurso", type="string", description="Nombre del curso"),
         *                 @OA\Property(property="docente_id", type="integer", description="ID del docente asignado")
         *             }
         *         }
         *     ),
         *     @OA\Response(
         *         response=200,
         *         description="Curso creado exitosamente.",
         *         content={
         *             "application/json": {
         *                 @OA\Property(property="success", type="string")
         *             }
         *         }
         *     ),
         *     @OA\Response(
         *         response=400,
         *         description="Error de validación.",
         *         content={
         *             "application/json": {
         *                 @OA\Property(property="error", type="string")
         *             }
         *         }
         *     ),
         *     @OA\Response(
         *         response=404,
         *         description="Docente no válido.",
         *         content={
         *             "application/json": {
         *                 @OA\Property(property="error", type="string")
         *             }
         *         }
         *     )
         * )
         */
        case 'create':
            $nombrecurso = $_POST['nombrecurso'] ?? '';
            $docente_id = $_POST['docente_id'] ?? 0;

            if (empty($nombrecurso) || empty($docente_id)) {
                echo json_encode(['error' => 'Todos los campos son obligatorios.']);
                exit();
            }

            // Validar que el docente exista
            $query = "SELECT * FROM docente 
                      WHERE iddocente = :docente_id";
            $stmt = $pdo->prepare($query);
            $stmt->execute(['docente_id' => $docente_id]);
            $docente = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$docente) {
                echo json_encode(['error' => 'Docente no válido.']);
                exit();
            }

            // Insertar curso
            $query = "INSERT INTO cursos (nombrecurso, docente_id) 
                      VALUES (:nombrecurso, :docente_id)";
            $stmt = $pdo->prepare($query);
            $stmt->execute([
                'nombrecurso' => $nombrecurso,
                'docente_id' => $docente_id
            ]);
            echo json_encode(['success' => 'Curso creado exitosamente.']);
            break;

        /**
         * @OA\Get(
         *     path="/cursos",
         *     summary="Obtener todos los cursos",
         *     description="Recupera todos los cursos con su nombre y docente asignado.",
         *     tags={"Cursos"},
         *     @OA\Response(
         *         response=200,
         *         description="Lista de cursos.",
         *         content={
         *             "application/json": {
         *                 @OA\Items(
         *                     type="object",
         *                     @OA\Property(property="idcurso", type="integer"),
         *                     @OA\Property(property="nombrecurso", type="string"),
         *                     @OA\Property(property="docente_id", type="integer"),
         *                     @OA\Property(property="nombreDocente", type="string")
         *                 )
         *             }
         *         }
         *     )
         * )
         */
        case 'read':
            $query = "SELECT c.idcursos AS idcurso, c.nombrecurso, 
                             d.iddocente AS docente_id,
                             CONCAT(u.nombre, ' ', u.apellido) AS nombreDocente 
                      FROM cursos c
                      INNER JOIN docente d ON c.docente_id = d.iddocente
                      INNER JOIN usuarios u ON d.usuario_id = u.idusuarios";
            $stmt = $pdo->prepare($query);
            $stmt->execute();
            echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
            break;

        /**
         * @OA\Put(
         *     path="/curso/{id}",
         *     summary="Actualizar un curso",
         *     description="Actualiza los detalles de un curso existente.",
         *     tags={"Cursos"},
         *     parameters={
         *         @OA\Parameter(
         *             name="id",
         *             in="path",
         *             required=true,
         *             description="ID del curso a actualizar",
         *             schema={ "type": "integer" }
         *         ),
         *         @OA\Parameter(
         *             name="nombrecurso",
         *             in="formData",
         *             required=true,
         *             description="Nuevo nombre del curso",
         *             schema={ "type": "string" }
         *         ),
         *         @OA\Parameter(
         *             name="docente_id",
         *             in="formData",
         *             required=true,
         *             description="ID del docente asignado al curso",
         *             schema={ "type": "integer" }
         *         )
         *     },
         *     responses={
         *         @OA\Response(
         *             response=200,
         *             description="Curso actualizado exitosamente.",
         *             content={ "application/json": { @OA\Property(property="success", type="string") } }
         *         ),
         *         @OA\Response(
         *             response=400,
         *             description="Error de validación.",
         *             content={ "application/json": { @OA\Property(property="error", type="string") } }
         *         ),
         *         @OA\Response(
         *             response=404,
         *             description="Curso no encontrado.",
         *             content={ "application/json": { @OA\Property(property="error", type="string") } }
         *         )
         *     }
         * )
         */
        case 'update':
            $idcurso = $_POST['idcursos'] ?? 0;
            $nombrecurso = $_POST['nombrecurso'] ?? '';
            $docente_id = $_POST['docente_id'] ?? 0;

            if (empty($idcurso) || empty($nombrecurso) || empty($docente_id)) {
                echo json_encode(['error' => 'Todos los campos son obligatorios.']);
                exit();
            }

            $query = "UPDATE cursos 
                      SET nombrecurso = :nombrecurso, 
                          docente_id = :docente_id 
                      WHERE idcursos = :idcursos";
            $stmt = $pdo->prepare($query);
            $stmt->execute([
                'nombrecurso' => $nombrecurso,
                'docente_id' => $docente_id,
                'idcursos' => $idcurso
            ]);

            if ($stmt->rowCount() === 0) {
                echo json_encode(['error' => 'No se encontró el curso.']);
                exit();
            }

            echo json_encode(['success' => 'Curso actualizado exitosamente.']);
            break;

        /**
         * @OA\Delete(
         *     path="/curso/{id}",
         *     summary="Eliminar un curso",
         *     description="Elimina un curso por su ID.",
         *     tags={"Cursos"},
         *     parameters={
         *         @OA\Parameter(
         *             name="id",
         *             in="path",
         *             required=true,
         *             description="ID del curso a eliminar",
         *             schema={ "type": "integer" }
         *         )
         *     },
         *     responses={
         *         @OA\Response(
         *             response=200,
         *             description="Curso eliminado exitosamente.",
         *             content={ "application/json": { @OA\Property(property="success", type="string") } }
         *         ),
         *         @OA\Response(
         *             response=400,
         *             description="Error al eliminar el curso.",
         *             content={ "application/json": { @OA\Property(property="error", type="string") } }
         *         ),
         *         @OA\Response(
         *             response=404,
         *             description="Curso no encontrado.",
         *             content={ "application/json": { @OA\Property(property="error", type="string") } }
         *         )
         *     }
         * )
         */
        case 'delete':
            $idcurso = $_POST['idcurso'] ?? 0;
            if (!$idcurso) {
                echo json_encode(['error' => 'Faltan datos para eliminar el curso.']);
                exit();
            }

            $query = "DELETE FROM cursos WHERE idcursos = :idcursos";
            $stmt = $pdo->prepare($query);
            $stmt->execute(['idcursos' => $idcurso]);

            if ($stmt->rowCount() === 0) {
                echo json_encode(['error' => 'No se encontró el curso.']);
                exit();
            }

            echo json_encode(['success' => 'Curso eliminado exitosamente.']);
            break;

        // Otras acciones...

        default:
            echo json_encode(['error' => 'Acción no válida.']);
            break;
    }
} catch (PDOException $e) {
    echo json_encode(['error' => 'Error en la base de datos: ' . $e->getMessage()]);
}
?>
