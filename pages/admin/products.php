<?php
require_once '../../config/database.php';
require_once '../../includes/auth_functions.php';
require_once '../../includes/functions.php';

// Verificar login
require_login();

// Verificar rol admin
if (!is_admin()) {
    header("Location: ../404.php");
    exit;
}

$conn = getConnection();

// Obtener todos los productos
$stmt = $conn->prepare("SELECT id, nombre, precio, imagen FROM productos ORDER BY id DESC");
$stmt->execute();
$productos = $stmt->fetchAll();

include '../../includes/header.php';
?>

<div class="container mt-5">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="fw-bold text-primary">Gestión de Productos</h1>
        <a href="product_add.php" class="btn btn-success">
            <i class="fas fa-plus me-2"></i>Agregar Producto
        </a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">

            <?php if (empty($productos)): ?>
                
                <p class="text-center text-muted">No hay productos registrados.</p>

            <?php else: ?>

                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle">
                        <thead class="table-primary">
                            <tr>
                                <th>ID</th>
                                <th>Imagen</th>
                                <th>Nombre</th>
                                <th>Precio</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>

                        <tbody>
                        <?php foreach ($productos as $p): ?>
                            <tr>
                                <td><?= $p['id'] ?></td>

                                <td>
                                    <?php 
                                        // ruta correcta
                                        $img = "../../public/images/" . ($p['imagen'] ?: "noimage.png");

                                        // si la imagen no existe, cargamos noimage.png
                                        if (!file_exists($img)) {
                                            $img = "../../public/images/noimage.png";
                                        }
                                    ?>
                                    <img src="<?= $img ?>" width="60" height="60" class="rounded">
                                </td>

                                <td><?= htmlspecialchars($p['nombre_producto']) ?></td>

                                <td>$<?= number_format($p['precio'], 2) ?></td>

                                <td>
                                    <a href="product_edit.php?id=<?= $p['id'] ?>" 
                                       class="btn btn-warning btn-sm">
                                        <i class="fas fa-edit"></i>
                                    </a>

                                    <a href="product_delete.php?id=<?= $p['id'] ?>" 
                                       class="btn btn-danger btn-sm"
                                       onclick="return confirm('¿Eliminar este producto?')">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>

                    </table>
                </div>

            <?php endif; ?>

        </div>
    </div>

</div>

<?php include '../../includes/footer.php'; ?>
