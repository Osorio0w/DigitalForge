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

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id <= 0) {
    $_SESSION['error'] = "ID inválido.";
    header("Location: products.php");
    exit;
}

// Obtener imagen
$stmt = $conn->prepare("SELECT imagen FROM productos WHERE id = :id");
$stmt->execute(['id' => $id]);
$producto = $stmt->fetch();

if ($producto) {
    $imagen = $producto['imagen'];
    $folder = "../../public/images/";

    if ($imagen && $imagen !== "noimage.png" && file_exists($folder . $imagen)) {
        unlink($folder . $imagen);
    }

    // Eliminar BD
    $del = $conn->prepare("DELETE FROM productos WHERE id = :id");
    $del->execute(['id' => $id]);

    $_SESSION['success'] = "Producto eliminado correctamente.";
}

header("Location: products.php");
exit;
