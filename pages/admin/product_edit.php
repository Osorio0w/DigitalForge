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

// Obtener datos
$stmt = $conn->prepare("SELECT * FROM productos WHERE id = :id LIMIT 1");
$stmt->execute(['id' => $id]);
$producto = $stmt->fetch();

if (!$producto) {
    $_SESSION['error'] = "Producto no encontrado.";
    header("Location: products.php");
    exit;
}

// Guardar cambios
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $nombre = trim($_POST['nombre']);
    $precio = trim($_POST['precio']);

    $imagen = $producto['imagen'];
    $folder = "../../public/images/";

    if (!empty($_FILES['imagen']['name'])) {

        // Validar extensión
        $ext = strtolower(pathinfo($_FILES['imagen']['name'], PATHINFO_EXTENSION));
        $permitidas = ['jpg','jpeg','png'];

        if (!in_array($ext, $permitidas)) {
            $_SESSION['error'] = "Formato de imagen no válido.";
            header("Location: product_edit.php?id=".$id);
            exit;
        }

        // Eliminar imagen anterior
        if ($imagen && $imagen !== "noimage.png" && file_exists($folder . $imagen)) {
            unlink($folder . $imagen);
        }

        $nuevoNombre = uniqid("img_") . "." . $ext;
        move_uploaded_file($_FILES['imagen']['tmp_name'], $folder . $nuevoNombre);
        $imagen = $nuevoNombre;
    }

    // Actualizar BD
    $stmt = $conn->prepare("
        UPDATE productos 
        SET nombre_producto = :n, precio = :p, imagen = :i 
        WHERE id = :id
    ");
    $stmt->execute([
        'n' => $nombre_producto,
        'p' => $precio,
        'i' => $imagen,
        'id' => $id
    ]);

    $_SESSION['success'] = "Producto actualizado correctamente.";
    header("Location: products.php");
    exit;
}

include '../../includes/header.php';
?>

<div class="container mt-5">
    <h1 class="fw-bold text-primary mb-4">Editar Producto</h1>

    <div class="card shadow-sm">
        <div class="card-body">

            <form action="" method="POST" enctype="multipart/form-data">

                <div class="mb-3">
                    <label class="form-label fw-bold">Nombre del Producto</label>
                    <input type="text" name="nombre" class="form-control"
                           value="<?php echo htmlspecialchars($producto['nombre_producto']); ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Precio</label>
                    <input type="number" name="precio" class="form-control"
                           step="0.01"
                           value="<?php echo htmlspecialchars($producto['precio']); ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Imagen Actual</label><br>
                    <img src="../../public/images/<?php echo $producto['imagen']; ?>" width="120" class="rounded mb-2">
                    
                    <input type="file" name="imagen" class="form-control" accept="image/*">
                </div>

                <button class="btn btn-primary">Guardar Cambios</button>
                <a href="products.php" class="btn btn-secondary">Volver</a>

            </form>

        </div>
    </div>
</div>

<?php include '../../includes/footer.php'; ?>
