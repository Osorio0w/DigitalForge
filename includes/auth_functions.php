<?php
/**
 * Funciones específicas de autenticación
 */

/**
 * Verifica si el usuario está logueado
 */
function checkAuth() {
    if (!isset($_SESSION['user_id'])) {
        return false;
    }
    
    // Verificar que el usuario aún existe en la BD
    require_once 'config/database.php';
    $conn = getConnection();
    
    $stmt = $conn->prepare("SELECT id, activo FROM usuarios WHERE id = :id");
    $stmt->execute(['id' => $_SESSION['user_id']]);
    $user = $stmt->fetch();
    
    if (!$user || $user['activo'] == 0) {
        // Usuario eliminado o desactivado
        session_destroy();
        return false;
    }
    
    return true;
}

/**
 * Requiere autenticación para acceder a la página
 */
function requireAuth() {
    if (!checkAuth()) {
        $_SESSION['error'] = 'Debes iniciar sesión para acceder a esta página.';
        $_SESSION['redirect_to'] = $_SERVER['REQUEST_URI'];
        header('Location: ../pages/login.php');
        exit();
    }
}

/**
 * Requiere permisos de administrador
 */
function requireAdminAuth() {
    requireAuth();
    
    if (!isset($_SESSION['es_admin']) || $_SESSION['es_admin'] !== true) {
        $_SESSION['error'] = 'No tienes permisos para acceder a esta área.';
        header('Location: ../index.php');
        exit();
    }
}

/**
 * Obtiene información del usuario actual
 */
function getCurrentUser() {
    if (!checkAuth()) {
        return null;
    }
    
    require_once 'config/database.php';
    $conn = getConnection();
    
    $stmt = $conn->prepare("
        SELECT id, nombre, apellido, correo, telefono, direccion, ciudad, pais, es_admin
        FROM usuarios 
        WHERE id = :id
    ");
    $stmt->execute(['id' => $_SESSION['user_id']]);
    
    return $stmt->fetch();
}

/**
 * Actualiza información del usuario
 */
function updateUserProfile($data) {
    require_once 'config/database.php';
    $conn = getConnection();
    
    $allowed_fields = ['nombre', 'apellido', 'telefono', 'direccion', 'ciudad', 'pais'];
    $updates = [];
    $params = ['id' => $_SESSION['user_id']];
    
    foreach ($allowed_fields as $field) {
        if (isset($data[$field])) {
            $updates[] = "$field = :$field";
            $params[$field] = sanitize($data[$field]);
        }
    }
    
    if (empty($updates)) {
        return false;
    }
    
    $sql = "UPDATE usuarios SET " . implode(', ', $updates) . " WHERE id = :id";
    $stmt = $conn->prepare($sql);
    
    return $stmt->execute($params);
}

/**
 * Cambia contraseña del usuario
 */
function changePassword($current_password, $new_password) {
    require_once 'config/database.php';
    $conn = getConnection();
    
    // Obtener hash actual
    $stmt = $conn->prepare("SELECT password FROM usuarios WHERE id = :id");
    $stmt->execute(['id' => $_SESSION['user_id']]);
    $user = $stmt->fetch();
    
    if (!$user || !verifyPassword($current_password, $user['password'])) {
        return 'La contraseña actual es incorrecta.';
    }
    
    // Validar nueva contraseña
    if (!validatePassword($new_password)) {
        return 'La nueva contraseña debe tener al menos 8 caracteres, incluyendo letras y números.';
    }
    
    // Actualizar contraseña
    $hashed_password = hashPassword($new_password);
    $stmt = $conn->prepare("UPDATE usuarios SET password = :password WHERE id = :id");
    $success = $stmt->execute([
        'password' => $hashed_password,
        'id' => $_SESSION['user_id']
    ]);
    
    return $success ? true : 'Error al actualizar la contraseña.';
}

/**
 * Verifica si un email ya está registrado
 */
function emailExists($email) {
    require_once 'config/database.php';
    $conn = getConnection();
    
    $stmt = $conn->prepare("SELECT id FROM usuarios WHERE correo = :email");
    $stmt->execute(['email' => $email]);
    
    return (bool)$stmt->fetch();
}

/**
 * Genera token de recuperación de contraseña
 */
function generatePasswordResetToken($email) {
    require_once 'config/database.php';
    $conn = getConnection();
    
    // Verificar que el email existe
    $stmt = $conn->prepare("SELECT id FROM usuarios WHERE correo = :email AND activo = 1");
    $stmt->execute(['email' => $email]);
    
    if (!$stmt->fetch()) {
        return false; // Email no existe o usuario inactivo
    }
    
    // Generar token
    $token = generateToken();
    $expiration = date('Y-m-d H:i:s', strtotime('+1 hour'));
    
    // Guardar token en BD
    $stmt = $conn->prepare("UPDATE usuarios SET token_recuperacion = :token, token_expiracion = :expiration WHERE correo = :email");
    $stmt->execute([
        'token' => $token,
        'expiration' => $expiration,
        'email' => $email
    ]);
    
    return $token;
}

/**
 * Verifica token de recuperación
 */
function verifyResetToken($token) {
    require_once 'config/database.php';
    $conn = getConnection();
    
    $stmt = $conn->prepare("
        SELECT id, correo 
        FROM usuarios 
        WHERE token_recuperacion = :token 
        AND token_expiracion > NOW()
        AND activo = 1
    ");
    $stmt->execute(['token' => $token]);
    
    return $stmt->fetch();
}

/**
 * Resetea contraseña usando token
 */
function resetPasswordWithToken($token, $new_password) {
    require_once 'config/database.php';
    $conn = getConnection();
    
    // Verificar token
    $user = verifyResetToken($token);
    if (!$user) {
        return 'Token inválido o expirado.';
    }
    
    // Validar nueva contraseña
    if (!validatePassword($new_password)) {
        return 'La contraseña debe tener al menos 8 caracteres, incluyendo letras y números.';
    }
    
    // Actualizar contraseña y limpiar token
    $hashed_password = hashPassword($new_password);
    $stmt = $conn->prepare("
        UPDATE usuarios 
        SET password = :password, 
            token_recuperacion = NULL, 
            token_expiracion = NULL 
        WHERE id = :id
    ");
    
    $success = $stmt->execute([
        'password' => $hashed_password,
        'id' => $user['id']
    ]);
    
    return $success ? true : 'Error al actualizar la contraseña.';
}

/**
 * Cierra sesión en todos los dispositivos
 */
function logoutAllDevices($user_id) {
    require_once 'config/database.php';
    $conn = getConnection();
    
    // Aquí podrías implementar un sistema de tokens de sesión
    // Por ahora, simplemente destruimos la sesión actual
    session_destroy();
    
    return true;
}

/**
 * Registra actividad del usuario
 */
function logUserActivity($action, $details = null) {
    require_once 'config/database.php';
    $conn = getConnection();
    
    $stmt = $conn->prepare("
        INSERT INTO user_activity (user_id, action, details, ip_address, user_agent)
        VALUES (:user_id, :action, :details, :ip, :agent)
    ");
    
    $stmt->execute([
        'user_id' => $_SESSION['user_id'] ?? null,
        'action' => $action,
        'details' => $details,
        'ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown',
        'agent' => $_SERVER['HTTP_USER_AGENT'] ?? 'unknown'
    ]);
}
?>