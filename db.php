<?php

// db.php

function conectarDB() {
    $host = "localhost";
    $db   = "vmonroy_db";
    $user = "vmonroy";
    $pass = "valinge15";
    $charset = "utf8mb4";

    // El DSN (Data Source Name) define el tipo de driver y los datos del servidor
    $dsn = "mysql:host=$host;dbname=$db;charset=$charset";

    // Opciones recomendadas para PDO
    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ];

    try {
        $pdo = new PDO($dsn, $user, $pass, $options);
        try {
            crearEsquemaBiblioteca($pdo);
        } catch (PDOException $e) {
            // Si no hay permisos para crear tablas, no bloquea la conexión.
            echo "Error al crear el esquema: " . $e->getMessage();
        }
        return $pdo;
    } catch (\PDOException $e) {
        die("Error de conexión:  " . $e->getMessage());
    }
}

function crearEsquemaBiblioteca(PDO $db) {
    $db->exec("CREATE TABLE IF NOT EXISTS usuarios (
        id_usuario INT AUTO_INCREMENT PRIMARY KEY,
        nombre VARCHAR(255) NOT NULL,
        email VARCHAR(255) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL,
        fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

    $db->exec("CREATE TABLE IF NOT EXISTS autores (
        id_autor INT AUTO_INCREMENT PRIMARY KEY,
        nombre VARCHAR(255) NOT NULL UNIQUE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

    $db->exec("CREATE TABLE IF NOT EXISTS libros (
        id_libro INT AUTO_INCREMENT PRIMARY KEY,
        autor_id INT NOT NULL,
        nombre VARCHAR(255) NOT NULL,
        disponible TINYINT(1) NOT NULL DEFAULT 1,
        FOREIGN KEY (autor_id) REFERENCES autores(id_autor) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

    $db->exec("CREATE TABLE IF NOT EXISTS prestamos (
        id_prestamo INT AUTO_INCREMENT PRIMARY KEY,
        usuario_id INT NOT NULL,
        libro_id INT NOT NULL,
        fecha_prestamo DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
        fecha_devolucion DATETIME DEFAULT NULL,
        FOREIGN KEY (usuario_id) REFERENCES usuarios(id_usuario) ON DELETE CASCADE,
        FOREIGN KEY (libro_id) REFERENCES libros(id_libro) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
}
