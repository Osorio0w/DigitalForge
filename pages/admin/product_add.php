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

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $nombre = trim($_POST['nombre']);
    $precio = trim($_POST['precio']);

    // Manejo de imagen
    $imagen = null;

    if (!empty($_FILES['imagen']['name'])) {
        $archivo = $_FILES['imagen']['name'];
        $tmp = $_FILES['imagen']['tmp_name'];
        $folder = "../../public/images/";

        $ext = strtolower(pathinfo($archivo, PATHINFO_EXTENSION));
        $nuevoNombre = uniqid("img_") . "." . $ext;

        move_uploaded_file($tmp, $folder . $nuevoNombre);
        $imagen = $nuevoNombre;
    }

    // Insertar en BD
    $stmt = $conn->prepare("INSERT INTO productos (nombre, precio, imagen) VALUES (:n, :p, :i)");
    $stmt->execute([
        'n' => $nombre,
        'p' => $precio,
        'i' => $imagen
    ]);

    $_SESSION['success'] = "Producto agregado correctamente.";
    header("Location: products.php");
    exit;
}

include '../../includes/header.php';
?>

<div class="container mt-5">
    <h1 class="fw-bold text-primary mb-4">Agregar Producto</h1>

    <div class="card shadow-sm">
        <div class="card-body">
            <form action="" method="POST" enctype="multipart/form-data">

                <div class="mb-3">
                    <label class="form-label fw-bold">Nombre del Producto</label>
                    <input type="text" name="nombre" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Precio</label>
                    <input type="number" name="precio" class="form-control" required step="0.01">
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Imagen del Producto</label>
                    <input type="file" name="imagen" class="form-control" accept="image/*">
                </div>

                <button class="btn btn-primary">Guardar Producto</button>
                <a href="products.php" class="btn btn-secondary">Volver</a>
            </form>
        </div>
    </div>
</div>

<?php include '../../includes/footer.php'; ?>
