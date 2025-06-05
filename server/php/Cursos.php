<?php
session_start();
require_once 'conexion.php';

// Configuración de CORS y headers
header('Content-Type: application/json; charset=utf-8');
require_once 'cors.php';


// Manejo de solicitud OPTIONS para CORS
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit();
}

// Captura de datos JSON si existen
if (isset($_SERVER['CONTENT_TYPE']) && $_SERVER['CONTENT_TYPE'] === 'application/json') {
    $json_data = file_get_contents('php://input');
    $data = json_decode($json_data, true);
    if ($data !== null) {
        $_POST = array_merge($_POST, $data);
    }
}

// Determinar la acción
$action = $_GET['action'] ?? $_POST['action'] ?? null;
if (!$action) {
    http_response_code(400);
    echo json_encode(['error' => 'No se especificó ninguna acción.']);
    exit();
}

try {
    switch ($action) {
        // Acción para crear un nuevo curso
        case 'create':
            $nombrecurso = $_POST['nombrecurso'] ?? '';
            $docente_id = $_POST['docente_id'] ?? 0;

            if (empty($nombrecurso) || empty($docente_id)) {
                http_response_code(400);
                echo json_encode(['error' => 'Todos los campos son obligatorios.']);
                exit();
            }

            // Validar que el docente exista
            $query = "SELECT * FROM docente WHERE iddocente = :docente_id";
            $stmt = $pdo->prepare($query);
            $stmt->execute(['docente_id' => $docente_id]);
            $docente = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$docente) {
                http_response_code(404);
                echo json_encode(['error' => 'Docente no válido.']);
                exit();
            }

            // Insertar curso
            $query = "INSERT INTO cursos (nombrecurso, docente_id) VALUES (:nombrecurso, :docente_id)";
            $stmt = $pdo->prepare($query);
            $stmt->execute([
                'nombrecurso' => $nombrecurso,
                'docente_id' => $docente_id
            ]);
            
            http_response_code(201);
            echo json_encode(['success' => 'Curso creado exitosamente.']);
            break;

        // Acción para obtener todos los cursos
        case 'read':
            $query = "SELECT c.idcursos AS idcurso, c.nombrecurso, 
                             d.iddocente AS docente_id,
                             CONCAT(u.nombre, ' ', u.apellido) AS nombreDocente 
                      FROM cursos c
                      INNER JOIN docente d ON c.docente_id = d.iddocente
                      INNER JOIN usuarios u ON d.usuario_id = u.idusuarios";
            $stmt = $pdo->prepare($query);
            $stmt->execute();
            $cursos = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            http_response_code(200);
            echo json_encode($cursos);
            break;

        // Acción para actualizar un curso
        case 'update':
            $idcurso = $_POST['idcursos'] ?? 0;
            $nombrecurso = $_POST['nombrecurso'] ?? '';
            $docente_id = $_POST['docente_id'] ?? 0;

            if (empty($idcurso) || empty($nombrecurso) || empty($docente_id)) {
                http_response_code(400);
                echo json_encode(['error' => 'Todos los campos son obligatorios.']);
                exit();
            }

            // Validar que el docente exista
            $query = "SELECT * FROM docente WHERE iddocente = :docente_id";
            $stmt = $pdo->prepare($query);
            $stmt->execute(['docente_id' => $docente_id]);
            $docente = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$docente) {
                http_response_code(404);
                echo json_encode(['error' => 'Docente no válido.']);
                exit();
            }

            $query = "UPDATE cursos SET nombrecurso = :nombrecurso, docente_id = :docente_id 
                      WHERE idcursos = :idcursos";
            $stmt = $pdo->prepare($query);
            $stmt->execute([
                'nombrecurso' => $nombrecurso,
                'docente_id' => $docente_id,
                'idcursos' => $idcurso
            ]);

            if ($stmt->rowCount() === 0) {
                http_response_code(404);
                echo json_encode(['error' => 'No se encontró el curso.']);
                exit();
            }

            http_response_code(200);
            echo json_encode(['success' => 'Curso actualizado exitosamente.']);
            break;

        // Acción para eliminar un curso
        case 'delete':
            $idcurso = $_POST['idcurso'] ?? 0;
            if (!$idcurso) {
                http_response_code(400);
                echo json_encode(['error' => 'Faltan datos para eliminar el curso.']);
                exit();
            }

            $query = "DELETE FROM cursos WHERE idcursos = :idcursos";
            $stmt = $pdo->prepare($query);
            $stmt->execute(['idcursos' => $idcurso]);

            if ($stmt->rowCount() === 0) {
                http_response_code(404);
                echo json_encode(['error' => 'No se encontró el curso.']);
                exit();
            }

            http_response_code(200);
            echo json_encode(['success' => 'Curso eliminado exitosamente.']);
            break;

        // Acción para obtener todos los docentes
        case 'get_docentes':
            $query = "SELECT d.iddocente, u.nombre, u.apellido 
                      FROM docente d
                      INNER JOIN usuarios u ON d.usuario_id = u.idusuarios
                      ORDER BY u.apellido, u.nombre";
            $stmt = $pdo->prepare($query);
            $stmt->execute();
            $docentes = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            if (empty($docentes)) {
                http_response_code(404);
                echo json_encode(['error' => 'No hay docentes registrados.']);
                exit();
            }
            
            http_response_code(200);
            echo json_encode($docentes);
            break;

        default:
            http_response_code(400);
            echo json_encode(['error' => 'Acción no válida.']);
            break;
    }
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Error en la base de datos: ' . $e->getMessage()]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Error en el servidor: ' . $e->getMessage()]);
}
?>