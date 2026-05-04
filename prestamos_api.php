<?php

header('Content-Type: application/json; charset=utf-8');

require_once 'db.php';

session_start();

// Verificar que el usuario esté autenticado
if (!isset($_SESSION['id_usuario'])) {
    echo json_encode(['success' => false, 'message' => 'Usuario no autenticado.']);
    exit();
}

$db = conectarDB();
$usuario_id = $_SESSION['id_usuario'];
$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    // Obtener préstamos del usuario actual
    try {
        $sql = "SELECT p.id_prestamo, p.fecha_prestamo, p.fecha_devolucion,
                       l.nombre as libro_nombre, a.nombre as autor_nombre
                FROM prestamos p
                JOIN libros l ON p.libro_id = l.id_libro
                JOIN autores a ON l.autor_id = a.id_autor
                WHERE p.usuario_id = :usuario_id
                ORDER BY p.fecha_prestamo DESC";
        $query = $db->prepare($sql);
        $query->execute(['usuario_id' => $usuario_id]);
        $loans = $query->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode(['success' => true, 'loans' => $loans]);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Error al cargar préstamos: ' . $e->getMessage()]);
    }
    exit();
} elseif ($method === 'POST') {
    // Crear préstamo o devolver libro
    if (isset($_POST['libro_id'])) {
        // Crear nuevo préstamo
        $libro_id = trim($_POST['libro_id'] ?? '');

        if (empty($libro_id) || !is_numeric($libro_id)) {
            echo json_encode(['success' => false, 'message' => 'Debe seleccionar un libro válido.']);
            exit();
        }

        try {
            // Verificar que el libro existe y está disponible
            $sql_check = "SELECT disponible FROM libros WHERE id_libro = :libro_id";
            $query_check = $db->prepare($sql_check);
            $query_check->execute(['libro_id' => $libro_id]);
            $libro = $query_check->fetch(PDO::FETCH_ASSOC);

            if (!$libro) {
                echo json_encode(['success' => false, 'message' => 'El libro seleccionado no existe.']);
                exit();
            }

            if (!$libro['disponible']) {
                echo json_encode(['success' => false, 'message' => 'El libro no está disponible para préstamo.']);
                exit();
            }

            // Verificar que el usuario no tenga ya este libro prestado
            $sql_check_loan = "SELECT id_prestamo FROM prestamos
                              WHERE usuario_id = :usuario_id AND libro_id = :libro_id AND fecha_devolucion IS NULL";
            $query_check_loan = $db->prepare($sql_check_loan);
            $query_check_loan->execute(['usuario_id' => $usuario_id, 'libro_id' => $libro_id]);

            if ($query_check_loan->rowCount() > 0) {
                echo json_encode(['success' => false, 'message' => 'Ya tienes este libro prestado.']);
                exit();
            }

            // Iniciar transacción
            $db->beginTransaction();

            // Crear el préstamo
            $sql_insert = "INSERT INTO prestamos (usuario_id, libro_id, fecha_prestamo)
                          VALUES (:usuario_id, :libro_id, NOW())";
            $query_insert = $db->prepare($sql_insert);
            $query_insert->execute(['usuario_id' => $usuario_id, 'libro_id' => $libro_id]);

            // Marcar el libro como no disponible
            $sql_update = "UPDATE libros SET disponible = 0 WHERE id_libro = :libro_id";
            $query_update = $db->prepare($sql_update);
            $query_update->execute(['libro_id' => $libro_id]);

            $db->commit();

            echo json_encode(['success' => true, 'message' => 'Préstamo registrado correctamente.']);

        } catch (PDOException $e) {
            if ($db->inTransaction()) {
                $db->rollBack();
            }
            echo json_encode(['success' => false, 'message' => 'Error al crear préstamo: ' . $e->getMessage()]);
        }

    } elseif (isset($_POST['return_id'])) {
        // Devolver libro
        $prestamo_id = trim($_POST['return_id'] ?? '');

        if (empty($prestamo_id) || !is_numeric($prestamo_id)) {
            echo json_encode(['success' => false, 'message' => 'ID de préstamo inválido.']);
            exit();
        }

        try {
            // Verificar que el préstamo pertenece al usuario y no está devuelto
            $sql_check = "SELECT libro_id FROM prestamos
                         WHERE id_prestamo = :prestamo_id AND usuario_id = :usuario_id AND fecha_devolucion IS NULL";
            $query_check = $db->prepare($sql_check);
            $query_check->execute(['prestamo_id' => $prestamo_id, 'usuario_id' => $usuario_id]);
            $prestamo = $query_check->fetch(PDO::FETCH_ASSOC);

            if (!$prestamo) {
                echo json_encode(['success' => false, 'message' => 'Préstamo no encontrado o ya devuelto.']);
                exit();
            }

            // Iniciar transacción
            $db->beginTransaction();

            // Marcar el préstamo como devuelto
            $sql_update_loan = "UPDATE prestamos SET fecha_devolucion = NOW() WHERE id_prestamo = :prestamo_id";
            $query_update_loan = $db->prepare($sql_update_loan);
            $query_update_loan->execute(['prestamo_id' => $prestamo_id]);

            // Marcar el libro como disponible
            $sql_update_book = "UPDATE libros SET disponible = 1 WHERE id_libro = :libro_id";
            $query_update_book = $db->prepare($sql_update_book);
            $query_update_book->execute(['libro_id' => $prestamo['libro_id']]);

            $db->commit();

            echo json_encode(['success' => true, 'message' => 'Libro devuelto correctamente.']);

        } catch (PDOException $e) {
            if ($db->inTransaction()) {
                $db->rollBack();
            }
            echo json_encode(['success' => false, 'message' => 'Error al devolver libro: ' . $e->getMessage()]);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Parámetros insuficientes.']);
    }
    exit();
}

echo json_encode(['success' => false, 'message' => 'Método no permitido.']);
exit();

?>