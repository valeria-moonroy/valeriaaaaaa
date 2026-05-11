<?php

header('Content-Type: application/json; charset=utf-8');

require_once 'db.php';

$nombre = $_POST['nombre'];
$email  = $_POST['email'];
$pwd = $_POST['pwd'];
$db = conectarDB();

try {
    $sql = "INSERT INTO usuarios (nombre, email, password) VALUES (:nombre, :email, :password)";
    $query = $db->prepare($sql);
    $passwordHash = password_hash($pwd, PASSWORD_DEFAULT);

    $resultado = $query->execute([
        'nombre' => $nombre,
        'email'  => $email,
        'password' => $passwordHash
    ]);

    if ($resultado) {
        echo json_encode(['success' => true, 'redirect' => 'index.php']);
        exit();
    }
} catch (PDOException $e) {
    if ($e->errorInfo[1] == 1062) {
        echo json_encode(['success' => false, 'message' => 'El email ya existe, favor de intentarlo con otro correo.']);
        exit();
    }
    echo json_encode(['success' => false, 'message' => 'Database Error: ' . $e->getMessage()]);
    exit();
}
