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

// Obtener producto para eliminar imagen
$stmt = $conn->prepare("SELECT imagen FROM productos WHERE id = :id");
$stmt->execute(['id' => $id]);
$producto = $stmt->fetch();

if ($producto) {
    $imagen = $producto['imagen'];
    $folder = "../../public/images/";

    if ($imagen && file_exists($folder . $imagen)) {
        unlink($folder . $imagen);
    }

    // Eliminar de BD
    $del = $conn->prepare("DELETE FROM productos WHERE id = :id");
    $del->execute(['id' => $id]);

    $_SESSION['success'] = "Producto eliminado correctamente.";
}

header("Location: products.php");
exit;
