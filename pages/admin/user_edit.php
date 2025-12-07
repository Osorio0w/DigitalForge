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

$stmt = $conn->prepare("SELECT * FROM usuarios WHERE id = :id");
$stmt->execute(['id' => $id]);
$u = $stmt->fetch();

if (!$u) {
    $_SESSION['error'] = "Usuario no encontrado.";
    header("Location: users.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $nombre = trim($_POST['nombre']);
    $apellido = trim($_POST['apellido']);
    $correo = trim($_POST['correo']);

    $stmt = $conn->prepare("
        UPDATE usuarios 
        SET nombre = :n, apellido = :a, correo = :c 
        WHERE id = :id
    ");

    $stmt->execute([
        'n' => $nombre,
        'a' => $apellido,
        'c' => $correo,
        'id' => $id
    ]);

    $_SESSION['success'] = "Usuario actualizado correctamente.";
    header("Location: users.php");
    exit;
}

include '../../includes/header.php';
?>

<div class="container mt-5">
    <h1 class="fw-bold text-primary mb-4">Editar Usuario</h1>

    <div class="card shadow-sm">
        <div class="card-body">

            <form action="" method="POST">

                <div class="mb-3">
                    <label class="form-label fw-bold">Nombre</label>
                    <input type="text" name="nombre" class="form-control" 
                           value="<?= htmlspecialchars($u['nombre']) ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Apellido</label>
                    <input type="text" name="apellido" class="form-control" 
                           value="<?= htmlspecialchars($u['apellido']) ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Correo</label>
                    <input type="email" name="correo" class="form-control" 
                           value="<?= htmlspecialchars($u['correo']) ?>" required>
                </div>

                <button class="btn btn-primary">Guardar Cambios</button>
                <a href="users.php" class="btn btn-secondary">Volver</a>

            </form>

        </div>
    </div>

</div>

<?php include '../../includes/footer.php'; ?>
