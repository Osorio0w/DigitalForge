<?php
/**
 * Configuración global de la aplicación
 * DigitalForge E-commerce
 */

// Configuración del sitio
define('SITE_NAME', 'DigitalForge');
define('SITE_URL', 'http://localhost/digitalforge');
define('ADMIN_EMAIL', 'admin@digitalforge.com');

// Configuración de la aplicación
define('DEBUG_MODE', true);
define('MAINTENANCE_MODE', false);

// Configuración de seguridad
define('SESSION_TIMEOUT', 3600); // 1 hora en segundos
define('MAX_LOGIN_ATTEMPTS', 5);
define('PASSWORD_MIN_LENGTH', 8);

// Configuración de archivos
define('MAX_UPLOAD_SIZE', 5242880); // 5MB
define('ALLOWED_IMAGE_TYPES', ['jpg', 'jpeg', 'png', 'gif', 'webp']);

// Rutas de la aplicación
define('ROOT_PATH', dirname(dirname(__FILE__)));
define('UPLOAD_PATH', ROOT_PATH . '/public/uploads/');
define('PRODUCT_IMAGE_PATH', 'public/uploads/products/');
define('USER_IMAGE_PATH', 'public/uploads/users/');

// Configuración de paginación
define('PRODUCTS_PER_PAGE', 12);
define('ADMIN_ITEMS_PER_PAGE', 20);

// Configuración de facturación
define('IVA_PERCENT', 16); // 16% IVA

// Colores de la marca DigitalForge
define('COLOR_PRIMARY', '#3b56a8');
define('COLOR_SECONDARY', '#d9e8ed');
define('COLOR_DARK', '#3c3c3c');
define('COLOR_ACCENT', '#aac9bb');
define('COLOR_LIGHT', '#ffffff');

// Manejo de errores
if (DEBUG_MODE) {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    error_reporting(0);
    ini_set('display_errors', 0);
}

// Iniciar sesión si no está iniciada
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Comprobar modo mantenimiento
if (MAINTENANCE_MODE && !isset($_SESSION['user_id'])) {
    header('HTTP/1.1 503 Service Unavailable');
    include('maintenance.php');
    exit;
}

// Zona horaria
date_default_timezone_set('America/Caracas');

// Autocargar clases (simplificado)
function autoloadClasses($className) {
    $paths = [
        'models/' . $className . '.php',
        'controllers/' . $className . '.php',
        'includes/' . $className . '.php'
    ];
    
    foreach ($paths as $path) {
        if (file_exists($path)) {
            require_once $path;
            return;
        }
    }
}

spl_autoload_register('autoloadClasses');
?>