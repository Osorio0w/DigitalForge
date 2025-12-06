<?php
session_start();

// Destruir todas las variables de sesión
$_SESSION = array();

// Destruir la sesión
session_destroy();

// Redirigir al login con mensaje de éxito
session_start();
$_SESSION['success'] = 'Has cerrado sesión exitosamente.';
header('Location: login.php');
exit();
?>