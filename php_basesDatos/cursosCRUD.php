<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

include('conexion.php');

$action = $_POST['action'] ?? '';

try {
    $conn = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    switch ($action) {
        case 'create':
            $nombrecurso = $_POST['nombrecurso'] ?? '';
            $docente_id = $_POST['docente_id'] ?? 0;

            if ($nombrecurso && $docente_id) {
                $query = "INSERT INTO cursos (nombrecurso, docente_iddocente) VALUES (:nombrecurso, :docente_id)";
                $stmt = $conn->prepare($query);
                $stmt->execute(['nombrecurso' => $nombrecurso, 'docente_id' => $docente_id]);
                echo json_encode(['success' => 'Curso creado exitosamente.']);
            } else {
                echo json_encode(['error' => 'Faltan datos para crear el curso.']);
            }
            break;

        case 'read':
            $query = "
                SELECT 
                    cursos.nombrecurso, 
                    CONCAT(usuarios.nombre, ' ', usuarios.apellido) AS nombreDocente
                FROM 
                    cursos 
                INNER JOIN 
                    usuarios ON cursos.docente_iddocente = usuarios.docente_iddocente
                WHERE 
                    usuarios.tipo_usuario_idtipo_usuario = 2
            ";
            $stmt = $conn->prepare($query);
            $stmt->execute();
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode($data);
            break;

        default:
            echo json_encode(['error' => 'Acción no válida.']);
            break;
    }
} catch (PDOException $e) {
    echo json_encode(['error' => 'Error de conexión: ' . $e->getMessage()]);
}

$conn = null;
?>
