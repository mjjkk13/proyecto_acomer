<?php
// Configuración de la base de datos
$host = 'localhost';
$dbname = 'acomer';
$username = 'root';
$password = '';

try {
    // Crear una nueva conexión PDO
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Verificar si el formulario fue enviado
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Recibir los datos del formulario
        $nombre = $_POST['nombreEstudiante'];
        $apellido = $_POST['apellidoEstudiante'];
        $asistio = $_POST['asistio'] === 'si' ? 1 : 0;

        // Consulta SQL para actualizar el registro
        $sql = "UPDATE alumnos_asistencia 
                SET asistio = :asistio 
                WHERE nombreAlumnos = :nombre AND apellidosAlumnos = :apellido";

        // Preparar la declaración
        $stmt = $pdo->prepare($sql);

        // Vincular los parámetros
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':apellido', $apellido);
        $stmt->bindParam(':asistio', $asistio);

        // Ejecutar la declaración
        $stmt->execute();

        // Verificar si se actualizó algún registro
        if ($stmt->rowCount() > 0) {
            echo "Asistencia actualizada correctamente.";
        } else {
            echo "No se encontró el registro del estudiante.";
        }
    }
} catch (PDOException $e) {
    // Mostrar el mensaje de error
    echo "Error al conectar con la base de datos: " . $e->getMessage();
}
?>
