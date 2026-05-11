<?php

header('Content-Type: application/json; charset=utf-8');

require_once 'db.php';

session_start();

$db = conectarDB();

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    // Obtener todos los autores
    try {
        $sql = "SELECT id_autor, nombre FROM autores ORDER BY nombre";
        $query = $db->query($sql);
        $authors = $query->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode(['success' => true, 'authors' => $authors]);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Error al cargar autores: ' . $e->getMessage()]);
    }
    exit();
} elseif ($method === 'POST') {
    // Crear nuevo autor 
    // Usamos la funcion trim para eliminar espacios en blanco al inicio y al final del nombre del autor
    // Ejemplo: " Gabriel García Márquez " se convierte en "Gabriel García Márquez"
    $nombre = trim($_POST['nombre'] ?? '');

    if (empty($nombre)) {
        echo json_encode(['success' => false, 'message' => 'El nombre del autor es obligatorio.']);
        exit();
    }

    try {
        $sql = "INSERT INTO autores (nombre) VALUES (:nombre)";
        $query = $db->prepare($sql);
        // Sanitizamos para evitar inyecciones SQL, aunque el uso de prepared statements ya ayuda a prevenir esto.
        // Ejemplo: Si el usuario ingresa "Gabriel García Márquez; DROP TABLE autores;", el prepared statement tratará todo como un valor de texto, evitando la ejecución de comandos maliciosos.
        $resultado = $query->execute(['nombre' => $nombre]);

        if ($resultado) {
            echo json_encode(['success' => true, 'message' => 'Autor creado correctamente.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error al crear el autor.']);
        }
    } catch (PDOException $e) {
        if ($e->errorInfo[1] == 1062) {
            echo json_encode(['success' => false, 'message' => 'Ya existe un autor con ese nombre.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error al crear autor: ' . $e->getMessage()]);
        }
    }
    exit();
}

echo json_encode(['success' => false, 'message' => 'Método no permitido.']);
exit();

?>