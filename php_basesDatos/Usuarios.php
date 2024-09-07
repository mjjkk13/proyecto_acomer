    <?php
    include 'conexion.php';

    $stmt = $pdo->query("SELECT * FROM credenciales");
    $credenciales = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($credenciales);
    ?>
 