<?php
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: http://localhost:5173');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');
header('Access-Control-Allow-Credentials: true');
header('Cache-Control: public, max-age=300');

if ($_SERVER['CONTENT_TYPE'] === 'application/json') {
    $json_data = file_get_contents('php://input');
    $data = json_decode($json_data, true);
    if ($data !== null) {
        $_POST = array_merge($_POST, $data);
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit();
}

require_once 'conexion.php';

$action = $_GET['action'] ?? $_POST['action'] ?? null;
if (!$action) {
    echo json_encode(['error' => 'No se especificó ninguna acción.']);
    exit();
}

try {
    switch ($action) {
        case 'create':
            $nombrecurso = $_POST['nombrecurso'] ?? '';
            $docente_id = $_POST['docente_id'] ?? 0;

            // Validación de campos obligatorios
            if (empty($nombrecurso) || empty($docente_id)) {
                echo json_encode(['error' => 'Todos los campos son obligatorios.']);
                exit();
            }

            // Verificar docente
            $query = "SELECT * FROM usuarios 
                      WHERE idusuarios = :docente_id 
                      AND tipo_usuario_idtipo_usuario = 2";
            $stmt = $pdo->prepare($query);
            $stmt->execute(['docente_id' => $docente_id]);
            $docente = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$docente) {
                echo json_encode(['error' => 'Docente no válido.']);
                exit();
            }

            // Insertar curso
            $query = "INSERT INTO cursos (nombrecurso, docente_iddocente) 
                      VALUES (:nombrecurso, :docente_id)";
            $stmt = $pdo->prepare($query);
            $stmt->execute([
                'nombrecurso' => $nombrecurso,
                'docente_id' => $docente_id
            ]);
            echo json_encode(['success' => 'Curso creado exitosamente.']);
            break;

        case 'read':
            $query = "SELECT c.idcursos AS idcurso, c.nombrecurso, 
                      c.docente_iddocente AS docente_id,
                      CONCAT(u.nombre, ' ', u.apellido) AS nombreDocente 
                      FROM cursos c 
                      INNER JOIN usuarios u ON c.docente_iddocente = u.idusuarios";
            $stmt = $pdo->prepare($query);
            $stmt->execute();
            echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
            break;

        case 'update':
            $idcurso = $_POST['idcursos'] ?? 0; // ← Corregir nombre del parámetro
            $nombrecurso = $_POST['nombrecurso'] ?? '';
            $docente_id = $_POST['docente_id'] ?? 0;
        
            if (empty($idcurso) || empty($nombrecurso) || empty($docente_id)) {
                echo json_encode(['error' => 'Todos los campos son obligatorios.']);
                exit();
            }
        
            $query = "UPDATE cursos 
                      SET nombrecurso = :nombrecurso, 
                          docente_iddocente = :docente_id 
                      WHERE idcursos = :idcursos"; // ← Nombre correcto de columna
            $stmt = $pdo->prepare($query);
            $stmt->execute([
                'nombrecurso' => $nombrecurso,
                'docente_id' => $docente_id,
                'idcursos' => $idcurso // ← Coincide con el placeholder
            ]);
            
            if ($stmt->rowCount() === 0) {
                echo json_encode(['error' => 'No se encontró el curso.']);
                exit();
            }
            
            echo json_encode(['success' => 'Curso actualizado exitosamente.']);
            break;

        case 'delete':
            $idcurso = $_POST['idcurso'] ?? 0;
            if (!$idcurso) {
                echo json_encode(['error' => 'Faltan datos para eliminar el curso.']);
                exit();
            }

            $query = "DELETE FROM cursos WHERE idcursos = :idcursos"; // Parámetro corregido
            $stmt = $pdo->prepare($query);
            $stmt->execute(['idcursos' => $idcurso]);
            
            if ($stmt->rowCount() === 0) {
                echo json_encode(['error' => 'No se encontró el curso.']);
                exit();
            }
            
            echo json_encode(['success' => 'Curso eliminado exitosamente.']);
            break;

        case 'register_docentes':
            $query = "INSERT INTO docente (usuarios_idusuarios, usuarios_tipo_documento_tdoc, usuarios_tipo_usuario_idtipo_usuario, usuarios_credenciales_idcredenciales) 
                      SELECT idusuarios, tipo_documento_tdoc, tipo_usuario_idtipo_usuario, credenciales_idcredenciales 
                      FROM usuarios 
                      WHERE tipo_usuario_idtipo_usuario = 2 
                      AND idusuarios NOT IN (SELECT usuarios_idusuarios FROM docente)";
            $stmt = $pdo->prepare($query);
            $stmt->execute();
            echo json_encode(['success' => 'Docentes registrados correctamente.']);
            break;

        case 'docentes':
            $query = "SELECT idusuarios, nombre, apellido FROM usuarios WHERE tipo_usuario_idtipo_usuario = 2";
            $stmt = $pdo->prepare($query);
            $stmt->execute();
            echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
            break;

        default:
            echo json_encode(['error' => 'Acción no válida.']);
            break;
    }
} catch (PDOException $e) {
    echo json_encode(['error' => 'Error en la base de datos: ' . $e->getMessage()]);
}
?>