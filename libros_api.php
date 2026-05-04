<?php

header('Content-Type: application/json; charset=utf-8');

require_once 'db.php';

$db = conectarDB();

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    // Obtener todos los libros con información del autor
    try {
        $sql = "SELECT l.id_libro, l.nombre, l.disponible, a.nombre as autor_nombre, a.id_autor
                FROM libros l
                JOIN autores a ON l.autor_id = a.id_autor
                ORDER BY l.nombre";
        $query = $db->query($sql);
        $books = $query->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode(['success' => true, 'books' => $books]);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Error al cargar libros: ' . $e->getMessage()]);
    }
    exit();
} elseif ($method === 'POST') {
    // Crear nuevo libro
    $nombre = trim($_POST['nombre'] ?? '');
    $autor_id = trim($_POST['autor_id'] ?? '');

    if (empty($nombre)) {
        echo json_encode(['success' => false, 'message' => 'El nombre del libro es obligatorio.']);
        exit();
    }

    if (empty($autor_id) || !is_numeric($autor_id)) {
        echo json_encode(['success' => false, 'message' => 'Debe seleccionar un autor válido.']);
        exit();
    }

    try {
        // Verificar que el autor existe
        $sql_check = "SELECT id_autor FROM autores WHERE id_autor = :autor_id";
        $query_check = $db->prepare($sql_check);
        $query_check->execute(['autor_id' => $autor_id]);

        if ($query_check->rowCount() === 0) {
            echo json_encode(['success' => false, 'message' => 'El autor seleccionado no existe.']);
            exit();
        }

        // Insertar el libro
        $sql = "INSERT INTO libros (nombre, autor_id, disponible) VALUES (:nombre, :autor_id, 1)";
        $query = $db->prepare($sql);
        $resultado = $query->execute([
            'nombre' => $nombre,
            'autor_id' => $autor_id
        ]);

        if ($resultado) {
            echo json_encode(['success' => true, 'message' => 'Libro creado correctamente.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error al crear el libro.']);
        }
    } catch (PDOException $e) {
        if ($e->errorInfo[1] == 1062) {
            echo json_encode(['success' => false, 'message' => 'Ya existe un libro con ese nombre para este autor.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error al crear libro: ' . $e->getMessage()]);
        }
    }
    exit();
}

echo json_encode(['success' => false, 'message' => 'Método no permitido.']);
exit();

?>