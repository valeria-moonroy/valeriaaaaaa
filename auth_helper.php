<?php
/**
 * auth_helper.php
 * Helper global para manejo de autenticación y sesiones
 * Principio SOLID: Single Responsibility & DRY
 */

/**
 * Verifica si la sesión del usuario es válida
 * @return array - ['valid' => bool, 'user' => array|null, 'error' => string|null]
 */
function checkSession() {
    session_start();
    
    $response = [
        'valid' => false,
        'user' => null,
        'error' => null
    ];
    
    // Si tiene sesión activa, verifica validez en BD
    if(isset($_SESSION['id_usuario'])) {
        try {
            require_once 'db.php';
            $db = conectarDB();
            
            $sql = "SELECT id_usuario, email FROM usuarios WHERE id_usuario = :id LIMIT 1";
            $query = $db->prepare($sql);
            $query->execute([':id' => $_SESSION['id_usuario']]);
            $usuario = $query->fetch(PDO::FETCH_ASSOC);
            
            if($usuario) {
                $response['valid'] = true;
                $response['user'] = $usuario;
                return $response;
            } else {
                // Usuario no existe, destruir sesión
                session_destroy();
                $response['error'] = 'Usuario no válido';
                return $response;
            }
        } catch(Exception $e) {
            $response['error'] = 'Error de autenticación: ' . $e->getMessage();
            return $response;
        }
    }
    
    // Si tiene cookie válida, restaurar sesión
    if(isset($_COOKIE["id_usuario"])) {
        try {
            require_once 'db.php';
            $db = conectarDB();
            
            $sql = "SELECT id_usuario, email FROM usuarios WHERE id_usuario = :id LIMIT 1";
            $query = $db->prepare($sql);
            $query->execute([':id' => $_COOKIE["id_usuario"]]);
            $usuario = $query->fetch(PDO::FETCH_ASSOC);
            
            if($usuario) {
                // Restaurar sesión desde cookie válida
                $_SESSION['id_usuario'] = $usuario['id_usuario'];
                $_SESSION['username'] = $usuario['email'];
                
                $response['valid'] = true;
                $response['user'] = $usuario;
                return $response;
            } else {
                // Cookie inválida, limpiar
                setcookie("id_usuario", "", time() - 3600, "/");
                unset($_COOKIE["id_usuario"]);
                $response['error'] = 'Cookie expirada';
                return $response;
            }
        } catch(Exception $e) {
            setcookie("id_usuario", "", time() - 3600, "/");
            $response['error'] = 'Error de autenticación: ' . $e->getMessage();
            return $response;
        }
    }
    
    // No hay sesión ni cookie
    $response['error'] = 'No autenticado';
    return $response;
}

/**
 * Verifica la autenticación y redirige si no es válida
 * @param string $redirectTo - URL a redirigir si no está autenticado
 * @return array - Datos del usuario si es válido
 */
function requireAuth($redirectTo = 'index.php') {
    $auth = checkSession();
    
    if(!$auth['valid']) {
        header("Location: " . $redirectTo);
        exit();
    }
    
    return $auth['user'];
}

/**
 * Verifica autenticación sin redirigir (para index/login)
 * @return bool - true si sesión es válida
 */
function isAuthenticated() {
    $auth = checkSession();
    return $auth['valid'];
}

/**
 * Obtiene datos del usuario autenticado
 * @return array|null - Datos del usuario o null
 */
function getCurrentUser() {
    $auth = checkSession();
    return $auth['user'] ?? null;
}

/**
 * Destruye la sesión y cookies de autenticación
 */
function logout() {
    session_start();
    
    // Destruir sesión
    $_SESSION = [];
    session_destroy();
    
    // Limpiar cookies
    setcookie("id_usuario", "", time() - 3600, "/");
    unset($_COOKIE["id_usuario"]);
}
?>
