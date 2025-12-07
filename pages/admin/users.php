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

// Obtener todos los usuarios
$stmt = $conn->prepare("SELECT * FROM usuarios ORDER BY id DESC");
$stmt->execute();
$usuarios = $stmt->fetchAll();

include '../../includes/header.php';
?>

<div class="container mt-5">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="fw-bold text-primary">Gestión de Usuarios</h1>
        <a href="user_add.php" class="btn btn-success">
            <i class="fas fa-user-plus me-2"></i>Agregar Usuario
        </a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">

            <?php if (count($usuarios) === 0): ?>
                <p class="text-center text-muted">No hay usuarios registrados.</p>
            <?php else: ?>

                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle">
                        <thead class="table-primary">
                            <tr>
                                <th>ID</th>
                                <th>Nombre</th>
                                <th>Correo</th>
                                <th>Rol</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php foreach ($usuarios as $u): ?>
                                <tr>
                                    <td><?= $u['id'] ?></td>
                                    <td><?= htmlspecialchars($u['nombre'] . " " . $u['apellido']) ?></td>
                                    <td><?= htmlspecialchars($u['correo']) ?></td>

                                    <td>
                                        <?php if ($u['rol'] == 1): ?>
                                            <span class="badge bg-warning text-dark">Administrador</span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary">Usuario</span>
                                        <?php endif; ?>
                                    </td>

                                    <td>
                                        <a href="user_edit.php?id=<?= $u['id'] ?>" 
                                           class="btn btn-warning btn-sm">
                                            <i class="fas fa-edit"></i>
                                        </a>

                                        <a href="user_delete.php?id=<?= $u['id'] ?>" 
                                           onclick="return confirm('¿Eliminar usuario?')" 
                                           class="btn btn-danger btn-sm">
                                            <i class="fas fa-trash"></i>
                                        </a>

                                        <?php if ($u['rol'] == 0): ?>
                                            <a href="user_promote.php?id=<?= $u['id'] ?>" 
                                               class="btn btn-primary btn-sm">
                                                <i class="fas fa-crown"></i>
                                            </a>
                                        <?php endif; ?>

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
