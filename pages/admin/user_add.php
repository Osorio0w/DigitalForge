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
    $apellido = trim($_POST['apellido']);
    $correo = trim($_POST['correo']);
    $password = password_hash($_POST['contrasena'], PASSWORD_DEFAULT);

    $stmt = $conn->prepare("
        INSERT INTO usuarios (nombre, apellido, correo, contrasena, rol)
        VALUES (:n, :a, :c, :p, 0)
    ");

    $stmt->execute([
        'n' => $nombre,
        'a' => $apellido,
        'c' => $correo,
        'p' => $password
    ]);

    $_SESSION['success'] = "Usuario creado correctamente.";
    header("Location: users.php");
    exit;
}

include '../../includes/header.php';
?>

<div class="container mt-5">

    <h1 class="fw-bold text-primary mb-4">Agregar Usuario</h1>

    <div class="card shadow-sm">
        <div class="card-body">

            <form action="" method="POST">

                <div class="mb-3">
                    <label class="form-label fw-bold">Nombre</label>
                    <input type="text" name="nombre" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Apellido</label>
                    <input type="text" name="apellido" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Correo</label>
                    <input type="email" name="correo" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Contraseña</label>
                    <input type="password" name="contrasena" class="form-control" required>
                </div>

                <button class="btn btn-primary">Guardar</button>
                <a href="users.php" class="btn btn-secondary">Volver</a>

            </form>

        </div>
    </div>

</div>

<?php include '../../includes/footer.php'; ?>
