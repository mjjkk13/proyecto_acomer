<?php
<<<<<<< HEAD
echo json_encode([
    "success" => true,
    "message" => "API funcionando correctamente"
=======
// server/php/index.php

header('Content-Type: application/json');
echo json_encode([
    'message' => 'API A Comer - Backend en funcionamiento',
    'status' => 'OK',
    'timestamp' => date('Y-m-d H:i:s')
>>>>>>> main
]);
