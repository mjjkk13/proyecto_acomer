<?php
// Mostrar errores para depuración
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Incluir el archivo de conexión a la base de datos
require_once 'C:/xampp/htdocs/Proyecto/core/database.php';

// Asegúrate de que siempre se envíe una respuesta JSON
header('Content-Type: application/json');

$response = [];

try {
    // Conexión a la base de datos
    $database = new Database();
    $pdo = $database->getConnection();

    // Obtener la acción (getCourses, addCourse, editCourse, deleteCourse, getDocentes)
    $action = $_POST['action'] ?? $_GET['action'] ?? null;

    // Verificar que la acción no sea nula o inválida
    if (!in_array($action, ['getCourses', 'addCourse', 'editCourse', 'deleteCourse', 'getDocentes'])) {
        throw new Exception('Acción no válida o no proporcionada');
    }

    // Filtrar y validar las entradas dependiendo de la acción
    switch ($action) {
        case 'getCourses':
            // Consulta para obtener cursos con JOIN
            $sql = "
                SELECT c.idcursos, c.nombrecurso, u.nombre AS nombredocente, u.apellido AS apellidodocente
                FROM cursos c
                JOIN docente d ON c.docente_iddocente = d.iddocente
                JOIN usuarios u ON d.usuarios_idusuarios = u.idusuarios
                JOIN tipo_usuario tu ON u.tipo_usuario_idtipo_usuario = tu.idtipo_usuario
                WHERE tu.rol = 'Docente'
            ";

            $stmt = $conn->prepare($sql);
            $stmt->execute();
            $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if (count($resultados) > 0) {
                $response = $resultados;
            } else {
                $response['error'] = 'No se encontraron cursos';
            }
            break;

        case 'addCourse':
            // Agregar curso
            $nombreCurso = $_POST['nombreCurso'] ?? null;
            $idDocente = $_POST['idDocente'] ?? null;

            if (empty($nombreCurso) || empty($idDocente)) {
                throw new Exception('Los campos "nombreCurso" y "idDocente" son obligatorios.');
            }

            // Validar que el idDocente sea un número válido
            if (!is_numeric($idDocente)) {
                throw new Exception('El campo "idDocente" debe ser un número válido.');
            }

            $sql = "INSERT INTO cursos (nombrecurso, docente_iddocente) VALUES (?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$nombreCurso, $idDocente]);

            $response['success'] = true;
            break;

        case 'editCourse':
            // Editar curso
            $idcursos = $_POST['idcursos'] ?? null;
            $nombreCurso = $_POST['nombreCurso'] ?? null;
            $idDocente = $_POST['idDocente'] ?? null;

            if (empty($idcursos) || empty($nombreCurso) || empty($idDocente)) {
                throw new Exception('Todos los campos son obligatorios para editar el curso.');
            }

            if (!is_numeric($idcursos) || !is_numeric($idDocente)) {
                throw new Exception('Los campos "idcursos" y "idDocente" deben ser números válidos.');
            }

            $sql = "UPDATE cursos SET nombrecurso = ?, docente_iddocente = ? WHERE idcursos = ?";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$nombreCurso, $idDocente, $idcursos]);

            $response['success'] = true;
            break;

        case 'deleteCourse':
            // Borrar curso
            $idcursos = $_POST['idcursos'] ?? null;

            if (empty($idcursos)) {
                throw new Exception('El "idcursos" es obligatorio para eliminar el curso.');
            }

            if (!is_numeric($idcursos)) {
                throw new Exception('El "idcursos" debe ser un número válido.');
            }

            $sql = "DELETE FROM cursos WHERE idcursos = ?";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$idcursos]);

            $response['success'] = true;
            break;

        case 'getDocentes':
            // Consulta para obtener docentes
            $sql = "
                SELECT u.idusuarios, u.nombre, u.apellido
                FROM usuarios u
                JOIN tipo_usuario tu ON u.tipo_usuario_idtipo_usuario = tu.idtipo_usuario
                WHERE tu.rol = 'Docente'
            ";

            $stmt = $conn->prepare($sql);
            $stmt->execute();
            $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if (count($resultados) > 0) {
                $response = $resultados;
            } else {
                $response['error'] = 'No se encontraron docentes';
            }
            break;

        default:
            throw new Exception('Acción no válida.');
    }

} catch (PDOException $e) {
    $response['error'] = 'Error en la consulta SQL: ' . $e->getMessage();
} catch (Exception $e) {
    $response['error'] = $e->getMessage();
}

// Devolver siempre una respuesta JSON válida
echo json_encode($response);
?>
