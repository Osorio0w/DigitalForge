<?php
require_once '../../includes/auth_functions.php';
require_once '../../includes/functions.php';

// Verificar si el usuario está logueado
require_login();

// Verificar rol de administrador
if (!is_admin()) {
    header("Location: ../404.php");
    exit;
}

include '../../includes/header.php';
?>

<div class="container mt-5">
    <h1 class="mb-4 text-primary fw-bold">Panel de Administrador</h1>

    <div class="row g-4">

        <!-- Tarjeta 1 Productos -->
        <div class="col-md-4">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h5 class="card-title fw-bold">Productos</h5>
                    <p class="card-text">Gestiona todos los productos del catálogo.</p>
                    <a href="products.php" class="btn btn-primary">Administrar</a>
                </div>
            </div>
        </div>

        <!-- Tarjeta 2 Usuarios -->
        <div class="col-md-4">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h5 class="card-title fw-bold">Usuarios</h5>
                    <p class="card-text">Modificar, eliminar o ascender usuarios.</p>
                    <a href="users.php" class="btn btn-primary">Administrar</a>
                </div>
            </div>
        </div>

        <!-- Tarjeta 3 Pedidos / Facturas -->
        <div class="col-md-4">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h5 class="card-title fw-bold">Facturas</h5>
                    <p class="card-text">Revisa el historial de compras y facturas.</p>
                    <a href="#" class="btn btn-primary disabled">Próximamente</a>
                </div>
            </div>
        </div>

    </div>
</div>

<?php include '../../includes/footer.php'; ?>
