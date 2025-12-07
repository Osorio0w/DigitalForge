<?php
require_once '../../includes/auth_functions.php';
require_once '../../includes/functions.php';
require_once '../../config/database.php';


require_login();

// Verificar rol de administrador
if (!is_admin()) {
    header("Location: ../404.php");
    exit;
}

$conn = getConnection();


// CONTADORES PARA EL DASHBOARD


// Total de usuarios
$totalUsuarios = $conn->query("SELECT COUNT(*) FROM usuarios")->fetchColumn();

// Total de administradores
$totalAdmins = $conn->query("SELECT COUNT(*) FROM usuarios WHERE rol = 1")->fetchColumn();

// Total de productos
$totalProductos = $conn->query("SELECT COUNT(*) FROM productos")->fetchColumn();

// Total de facturas (si no existe la tabla, quedará en 0)
try {
    $totalFacturas = $conn->query("SELECT COUNT(*) FROM facturas")->fetchColumn();
} catch (Exception $e) {
    $totalFacturas = 0;
}

include '../../includes/header.php';
?>

<div class="container mt-5">
    <h1 class="mb-4 text-primary fw-bold">Panel de Administrador</h1>

    <div class="row g-4">

        <!-- Tarjeta 1 Productos -->
        <div class="col-md-3">
            <div class="card shadow-sm border-0">
                <div class="card-body text-center">
                    <h5 class="card-title fw-bold">Productos</h5>
                    <h2 class="fw-bold text-primary">
                        <?= $totalProductos ?>
                    </h2>
                    <p class="text-muted">Registrados en sistema</p>
                    <a href="products.php" class="btn btn-primary w-100">Administrar</a>
                </div>
            </div>
        </div>

        <!-- Tarjeta 2 Usuarios -->
        <div class="col-md-3">
            <div class="card shadow-sm border-0">
                <div class="card-body text-center">
                    <h5 class="card-title fw-bold">Usuarios</h5>
                    <h2 class="fw-bold text-primary">
                        <?= $totalUsuarios ?>
                    </h2>
                    <p class="text-muted">Usuarios totales</p>
                    <a href="users.php" class="btn btn-primary w-100">Administrar</a>
                </div>
            </div>
        </div>

        <!-- Tarjeta 3 Administradores -->
        <div class="col-md-3">
            <div class="card shadow-sm border-0">
                <div class="card-body text-center">
                    <h5 class="card-title fw-bold">Administradores</h5>
                    <h2 class="fw-bold text-primary">
                        <?= $totalAdmins ?>
                    </h2>
                    <p class="text-muted">Con privilegios altos</p>
                    <a class="btn btn-secondary w-100 disabled">Gestión limitada</a>
                </div>
            </div>
        </div>

        <!-- Tarjeta 4 Facturas -->
        <div class="col-md-3">
            <div class="card shadow-sm border-0">
                <div class="card-body text-center">
                    <h5 class="card-title fw-bold">Facturas</h5>
                    <h2 class="fw-bold text-primary">
                        <?= $totalFacturas ?>
                    </h2>
                    <p class="text-muted">Historial de compras</p>
                    <a href="#" class="btn btn-primary w-100 disabled">Próximamente</a>
                </div>
            </div>
        </div>

    </div>
</div>

<?php include '../../includes/footer.php'; ?>
