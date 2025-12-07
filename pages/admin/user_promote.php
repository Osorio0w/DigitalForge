<?php
require_once '../../includes/auth_functions.php';
require_once '../../includes/functions.php';
require_once '../../config/database.php';

require_login();
if (!is_admin()) {
    header("Location: ../404.php");
    exit;
}

$conn = getConnection();

$id = $_GET['id'] ?? 0;

$stmt = $conn->prepare("UPDATE usuarios SET rol = 1 WHERE id = :id");
$stmt->execute(['id' => $id]);

$_SESSION['success'] = "Usuario ascendido a administrador.";
header("Location: users.php");
exit;
