<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'C:/xampp/htdocs/Proyecto/core/database.php'; // Asegúrate de que la ruta es correcta

$action = $_POST['action'] ?? '';

try {
 

    switch ($action) {
        case 'create':
            $nombrecurso = $_POST['nombrecurso'] ?? '';
            $docente_id = $_POST['docente_id'] ?? 0;  // ID del docente seleccionado

            if ($nombrecurso && $docente_id) {
                // Obtener los datos del docente desde la tabla docente
                $query = "
                    SELECT 
                        iddocente, 
                        usuarios_idusuarios, 
                        usuarios_tipo_documento_tdoc, 
                        usuarios_tipo_usuario_idtipo_usuario, 
                        usuarios_credenciales_idcredenciales
                    FROM docente
                    WHERE iddocente = :docente_id";
                
                $stmt = $conn->prepare($query);
                $stmt->bindParam(':docente_id', $docente_id, PDO::PARAM_INT);
                $stmt->execute();
                $docente = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($docente) {
                    // Insertar el curso con los datos del docente
                    $query = "
                        INSERT INTO cursos (
                            nombrecurso, 
                            docente_iddocente, 
                            docente_usuarios_idusuarios, 
                            docente_usuarios_tipo_documento_tdoc, 
                            docente_usuarios_tipo_usuario_idtipo_usuario, 
                            docente_usuarios_credenciales_idcredenciales
                        ) 
                        VALUES (
                            :nombrecurso, 
                            :docente_iddocente, 
                            :usuarios_idusuarios, 
                            :tipo_documento_tdoc, 
                            :tipo_usuario_idtipo_usuario, 
                            :credenciales_idcredenciales
                        )";
                    
                    $stmt = $conn->prepare($query);
                    $stmt->execute([
                        'nombrecurso' => $nombrecurso,
                        'docente_iddocente' => $docente['iddocente'],
                        'usuarios_idusuarios' => $docente['usuarios_idusuarios'],
                        'tipo_documento_tdoc' => $docente['usuarios_tipo_documento_tdoc'],
                        'tipo_usuario_idtipo_usuario' => $docente['usuarios_tipo_usuario_idtipo_usuario'],
                        'credenciales_idcredenciales' => $docente['usuarios_credenciales_idcredenciales']
                    ]);

                    echo json_encode(['success' => 'Curso creado exitosamente.']);
                } else {
                    echo json_encode(['error' => 'No se encontró el docente.']);
                }
            } else {
                echo json_encode(['error' => 'Faltan datos para crear el curso.']);
            }
            break;

        case 'read':
            $query = "
                SELECT 
                    cursos.idcurso,
                    cursos.nombrecurso, 
                    CONCAT(usuarios.nombre, ' ', usuarios.apellido) AS nombreDocente
                FROM 
                    cursos 
                INNER JOIN 
                    usuarios ON cursos.docente_usuarios_idusuarios = usuarios.idusuarios
                WHERE 
                    usuarios.tipo_usuario_idtipo_usuario = 2
            ";
            $stmt = $conn->prepare($query);
            $stmt->execute();
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode($data);
            break;

        case 'update':
            $idcurso = $_POST['idcurso'] ?? 0;
            $nombrecurso = $_POST['nombrecurso'] ?? '';
            $docente_id = $_POST['docente_id'] ?? 0;

            if ($idcurso && $nombrecurso && $docente_id) {
                // Obtener los datos del docente
                $query = "
                    SELECT 
                        iddocente, 
                        usuarios_idusuarios, 
                        usuarios_tipo_documento_tdoc, 
                        usuarios_tipo_usuario_idtipo_usuario, 
                        usuarios_credenciales_idcredenciales
                    FROM docente
                    WHERE iddocente = :docente_id";
                
                $stmt = $conn->prepare($query);
                $stmt->execute(['docente_id' => $docente_id]);
                $docente = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($docente) {
                    // Actualizar el curso con los datos del docente
                    $query = "
                        UPDATE cursos 
                        SET 
                            nombrecurso = :nombrecurso, 
                            docente_iddocente = :docente_iddocente, 
                            docente_usuarios_idusuarios = :usuarios_idusuarios, 
                            docente_usuarios_tipo_documento_tdoc = :tipo_documento_tdoc, 
                            docente_usuarios_tipo_usuario_idtipo_usuario = :tipo_usuario_idtipo_usuario, 
                            docente_usuarios_credenciales_idcredenciales = :credenciales_idcredenciales
                        WHERE idcurso = :idcurso";
                    
                    $stmt = $conn->prepare($query);
                    $stmt->execute([
                        'nombrecurso' => $nombrecurso,
                        'docente_iddocente' => $docente['iddocente'],
                        'usuarios_idusuarios' => $docente['usuarios_idusuarios'],
                        'tipo_documento_tdoc' => $docente['usuarios_tipo_documento_tdoc'],
                        'tipo_usuario_idtipo_usuario' => $docente['usuarios_tipo_usuario_idtipo_usuario'],
                        'credenciales_idcredenciales' => $docente['usuarios_credenciales_idcredenciales'],
                        'idcurso' => $idcurso
                    ]);

                    echo json_encode(['success' => 'Curso actualizado exitosamente.']);
                } else {
                    echo json_encode(['error' => 'No se encontró el docente.']);
                }
            } else {
                echo json_encode(['error' => 'Faltan datos para actualizar el curso.']);
            }
            break;

        case 'delete':
            $idcurso = $_POST['idcurso'] ?? 0;

            if ($idcurso) {
                $query = "DELETE FROM cursos WHERE idcurso = :idcurso";
                $stmt = $conn->prepare($query);
                $stmt->execute(['idcurso' => $idcurso]);
                echo json_encode(['success' => 'Curso eliminado exitosamente.']);
            } else {
                echo json_encode(['error' => 'Faltan datos para eliminar el curso.']);
            }
            break;

        case 'register_docentes':
            // Registrar docentes desde usuarios con rol docente (idtipo_usuario = 2)
            $query = "
                SELECT 
                    idusuarios, 
                    tipo_documento_tdoc, 
                    tipo_usuario_idtipo_usuario, 
                    credenciales_idcredenciales
                FROM usuarios
                WHERE tipo_usuario_idtipo_usuario = 2";
            
            $stmt = $conn->prepare($query);
            $stmt->execute();
            $docentes = $stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($docentes as $docente) {
                $query = "
                    INSERT INTO docente (
                        usuarios_idusuarios, 
                        usuarios_tipo_documento_tdoc, 
                        usuarios_tipo_usuario_idtipo_usuario, 
                        usuarios_credenciales_idcredenciales
                    ) 
                    SELECT :idusuarios, :tipo_documento_tdoc, :tipo_usuario_idtipo_usuario, :credenciales_idcredenciales
                    WHERE NOT EXISTS (
                        SELECT 1 FROM docente WHERE usuarios_idusuarios = :idusuarios
                    )";
                
                $stmt = $conn->prepare($query);
                $stmt->execute([
                    'idusuarios' => $docente['idusuarios'],
                    'tipo_documento_tdoc' => $docente['tipo_documento_tdoc'],
                    'tipo_usuario_idtipo_usuario' => $docente['tipo_usuario_idtipo_usuario'],
                    'credenciales_idcredenciales' => $docente['credenciales_idcredenciales']
                ]);
            }
            
            echo json_encode(['success' => 'Docentes registrados correctamente.']);
            break;

        default:
            echo json_encode(['error' => 'Acción no válida.']);
            break;
    }
} catch (PDOException $e) {
    echo json_encode(['error' => 'Error de conexión: ' . $e->getMessage()]);
}

?>
