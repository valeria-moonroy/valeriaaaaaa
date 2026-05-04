<?php

require_once 'db.php'; // Traemos el código del otro archivo

//  Obtenemos los datos del formulario
$email  = $_POST['email'];
$pwd = $_POST['pwd'];

header('Content-Type: application/json; charset=utf-8');

// Verificamos que los datos no estén vacíos
if (empty($email) || empty($pwd)) {
    echo json_encode(['success' => false, 'message' => 'Por favor, complete todos los campos.']);
    exit();
}

// Verificamos que el email tenga un formato válido
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['success' => false, 'message' => 'Por favor, ingrese un email válido.']);
    exit();
}

$db = conectarDB();

try {
    $sql = "select id_usuario,password,email from usuarios where email= :email";
    $query = $db->prepare($sql);
    $resultado = $query->execute([
        'email'  => $email
    ]);

    $usuario = $query->fetch(PDO::FETCH_ASSOC);

    if (!$usuario) {
        echo json_encode(['success' => false, 'message' => 'Email o contraseña incorrectos.']);
        exit();
    }

    $verify = password_verify($pwd, $usuario['password']);

    if (!$verify) {
        echo json_encode(['success' => false, 'message' => 'Email o contraseña incorrectos.']);
        exit();
    }

    session_start();
    $_SESSION['username'] = $usuario['email'];
    $_SESSION['id_usuario'] = $usuario['id_usuario'];

    echo json_encode(['success' => true, 'redirect' => 'dashboard.php']);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database Error', 'error' => $e->getMessage()]);
}
