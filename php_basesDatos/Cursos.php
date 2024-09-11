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
            $usuarioId = $_POST['usuarioId'] ?? 0;

            if ($nombrecurso && $usuarioId) {
                // Verificar si el usuario seleccionado es un docente
                $queryUsuario = "
                    SELECT 
                        u.idusuarios,
                        u.tipo_documento_tdoc,
                        u.tipo_usuario_idtipo_usuario,
                        u.credenciales_idcredenciales
                    FROM 
                        usuarios u
                    INNER JOIN 
                        tipo_usuario tu ON u.tipo_usuario_idtipo_usuario = tu.idtipo_usuario
                    WHERE 
                        u.idusuarios = :usuarioId AND tu.rol = 'docente'
                ";
                $stmtUsuario = $conn->prepare($queryUsuario);
                $stmtUsuario->execute(['usuarioId' => $usuarioId]);
                $usuario = $stmtUsuario->fetch(PDO::FETCH_ASSOC);

                if ($usuario) {
                    // Insertar el docente en la tabla docente si no existe
                    $queryDocente = "
                        INSERT INTO docente (usuarios_idusuarios, usuarios_tipo_documento_tdoc, usuarios_tipo_usuario_idtipo_usuario, usuarios_credenciales_idcredenciales)
                        SELECT 
                            :usuarioId, 
                            :tipo_documento_tdoc, 
                            :tipo_usuario_idtipo_usuario, 
                            :credenciales_idcredenciales
                        WHERE NOT EXISTS (
                            SELECT 1 FROM docente WHERE usuarios_idusuarios = :usuarioId
                        )
                    ";
                    $stmtDocente = $conn->prepare($queryDocente);
                    $stmtDocente->execute([
                        'usuarioId' => $usuario['idusuarios'],
                        'tipo_documento_tdoc' => $usuario['tipo_documento_tdoc'],
                        'tipo_usuario_idtipo_usuario' => $usuario['tipo_usuario_idtipo_usuario'],
                        'credenciales_idcredenciales' => $usuario['credenciales_idcredenciales']
                    ]);

                    // Obtener el ID del nuevo docente
                    $queryIdDocente = "SELECT iddocente FROM docente WHERE usuarios_idusuarios = :usuarioId";
                    $stmtIdDocente = $conn->prepare($queryIdDocente);
                    $stmtIdDocente->execute(['usuarioId' => $usuario['idusuarios']]);
                    $docente = $stmtIdDocente->fetch(PDO::FETCH_ASSOC);
                    $docenteId = $docente['iddocente'];

                    // Insertar el curso con el ID del docente
                    $queryCurso = "
                        INSERT INTO cursos (nombrecurso, docente_iddocente) 
                        VALUES (:nombrecurso, :docente_iddocente)
                    ";
                    $stmtCurso = $conn->prepare($queryCurso);
                    $stmtCurso->execute([
                        'nombrecurso' => $nombrecurso,
                        'docente_iddocente' => $docenteId
                    ]);

                    echo json_encode(['success' => 'Curso creado exitosamente con el docente asignado.']);
                } else {
                    echo json_encode(['error' => 'El usuario seleccionado no es un docente válido.']);
                }
            } else {
                echo json_encode(['error' => 'Faltan datos para crear el curso o el docente.']);
            }
            break;

        // ... (otros casos para 'read', 'update', 'delete')

        default:
            echo json_encode(['error' => 'Acción no válida.']);
            break;
    }
} catch (PDOException $e) {
    echo json_encode(['error' => 'Error de conexión: ' . $e->getMessage()]);
}

$conn = null;
?>
