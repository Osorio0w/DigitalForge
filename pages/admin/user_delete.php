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

$id = intval($_GET['id']);

$stmt = $conn->prepare("DELETE FROM usuarios WHERE id = :id");
$stmt->execute(['id' => $id]);

$_SESSION['success'] = "Usuario eliminado correctamente.";
header("Location: users.php");
exit;
