<?php
/**
 * Funciones helper para DigitalForge
 */

/**
 * Redirige a una URL específica
 */
function redirect($url) {
    header("Location: $url");
    exit();
}

/**
 * Sanitiza datos de entrada
 */
function sanitize($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    return $data;
}

/**
 * Valida formato de email
 */
function validateEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

/**
 * Valida fortaleza de contraseña
 */
function validatePassword($password) {
    // Mínimo 8 caracteres, al menos una letra y un número
    return preg_match('/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d!@#$%^&*()_+\-=\[\]{};\':"\\|,.<>\/?]{8,}$/', $password);
}

/**
 * Genera hash seguro para contraseña
 */
function hashPassword($password) {
    return password_hash($password, PASSWORD_DEFAULT);
}

/**
 * Verifica contraseña
 */
function verifyPassword($password, $hash) {
    return password_verify($password, $hash);
}

/**
 * Formatea precio con separadores de miles
 */
function formatPrice($price, $currency = 'USD') {
    $symbols = [
        'USD' => '$',
        'EUR' => '€',
        'VES' => 'Bs.'
    ];
    
    $symbol = $symbols[$currency] ?? '$';
    return $symbol . number_format($price, 2, ',', '.');
}

/**
 * Genera token aleatorio
 */
function generateToken($length = 32) {
    return bin2hex(random_bytes($length));
}

/**
 * Obtiene la URL base de la aplicación
 */
function base_url($path = '') {
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
    $host = $_SERVER['HTTP_HOST'];
    $base = str_replace(basename($_SERVER['SCRIPT_NAME']), '', $_SERVER['SCRIPT_NAME']);
    
    return $protocol . '://' . $host . $base . $path;
}

/**
 * Muestra mensaje de alerta
 */
function setMessage($type, $message) {
    $_SESSION[$type] = $message;
}

/**
 * Obtiene el nombre del mes en español
 */
function getSpanishMonth($month) {
    $months = [
        1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril',
        5 => 'Mayo', 6 => 'Junio', 7 => 'Julio', 8 => 'Agosto',
        9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre'
    ];
    
    return $months[$month] ?? '';
}

/**
 * Calcula el total del carrito
 */
function calculateCartTotal($user_id) {
    require_once 'config/database.php';
    $conn = getConnection();
    
    $stmt = $conn->prepare("
        SELECT SUM(p.precio_producto * c.cantidad) as total
        FROM carrito c
        JOIN productos p ON c.id_producto = p.id
        WHERE c.id_usuario = :user_id
    ");
    $stmt->execute(['user_id' => $user_id]);
    $result = $stmt->fetch();
    
    return $result['total'] ?? 0;
}

/**
 * Verifica si el usuario está logueado
 */
function isLoggedIn() {
    return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
}

/**
 * Verifica si el usuario es administrador
 */
function isAdmin() {
    return isset($_SESSION['es_admin']) && $_SESSION['es_admin'] === true;
}

/**
 * Requiere login para acceder a la página
 */
function requireLogin() {
    if (!isLoggedIn()) {
        $_SESSION['error'] = 'Debes iniciar sesión para acceder a esta página.';
        redirect('login.php');
    }
}

/**
 * Requiere permisos de administrador
 */
function requireAdmin() {
    requireLogin();
    
    if (!isAdmin()) {
        $_SESSION['error'] = 'No tienes permisos para acceder a esta área.';
        redirect('../index.php');
    }
}

/**
 * Limpia datos antiguos de formularios
 */
function old($field, $default = '') {
    return $_SESSION['old_data'][$field] ?? $default;
}

/**
 * Guarda datos antiguos para repoblar formularios
 */
function saveOldData($data) {
    $_SESSION['old_data'] = $data;
}

/**
 * Limpia datos antiguos
 */
function clearOldData() {
    unset($_SESSION['old_data']);
}

/**
 * Genera slug para URLs amigables
 */
function generateSlug($text) {
    $text = strtolower($text);
    $text = preg_replace('/[^a-z0-9]+/', '-', $text);
    $text = trim($text, '-');
    return $text;
}
?>