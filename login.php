<?php

require_once 'db.php'; // Traemos el código del otro archivo

//  Obtenemos los datos del formulario
$email  = $_POST['email'];
$pwd = $_POST['pwd'];

// Verificamos que los datos no estén vacíos
if (empty($email) || empty($pwd)) {
    echo "Por favor, complete todos los campos.";
    exit();
}

// Verificamos que el email tenga un formato válido
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo "Por favor, ingrese un email válido.";
    exit();
}

// Llamamos a la función y guardamos el objeto en $db
$db = conectarDB();

try {

    $sql = "select id_usuario,password,email from usuarios where email= :email";
    $query = $db->prepare($sql);

    // Ejecutamos pasando los datos en un array
    $resultado = $query->execute([
        'email'  => $email
    ]);

    $usuario = $query->fetch(PDO::FETCH_ASSOC);

    if (!$usuario) {
        echo "No se encontraron datos!";
        exit();
    }

    $verify = password_verify($pwd, $usuario['password']);

    if (!$verify) {
        echo "No se encontraron datos!";
        exit();
    }

    session_start();
    $_SESSION['username'] = $usuario['email']; // Store session data
    $_SESSION['id_usuario'] = $usuario['id_usuario'];
    header("Location: dashboard.php");
} catch (PDOException $e) {
    // Manejo de errores (ej. si el email ya existe y es único)
    echo "Database Error: " . $e->getMessage();
}
