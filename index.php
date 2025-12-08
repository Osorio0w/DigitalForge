<?php 
require_once 'includes/header.php'; 
?>

<!-- Contenido principal de la página -->
<section class="hero-section text-center text-white d-flex align-items-center">
    <div class="container">
        <h1 class="fw-bold mb-3 hero-title">Construye tu presencia digital</h1>
        <p class="lead hero-subtitle mb-4">
            Diseño, tecnología y creatividad — todo lo que tu negocio necesita para crecer online.
        </p>
        <a href="#productos" class="btn btn-light btn-lg fw-bold px-4 py-2">
            <i class="bi bi-arrow-right-circle me-1"></i> Ver Productos
        </a>
    </div>
</section>

<div class="container mt-5 flex-grow-1" id="productos">

    <h2 class="fw-bold mb-4" style="color: var(--color-primary);">Productos Destacados</h2>

    <?php
        require_once 'config/database.php';
        $conn = getConnection();

        // Obtener productos reales
        $stmt = $conn->prepare("SELECT * FROM productos ORDER BY id DESC LIMIT 12");
        $stmt->execute();
        $productos = $stmt->fetchAll();
    ?>

    <div class="row">

        <?php if (count($productos) == 0): ?>

            <p class="text-center text-muted">No hay productos todavía.</p>

        <?php else: ?>

            <?php foreach ($productos as $p): ?>
                <div class="col-md-3 mb-4">
                    <div class="card product-card shadow-sm border-0">

                        <img src="public/images/productos/<?php echo $p['imagen']; ?>" 
                             class="card-img-top" 
                             alt="<?php echo htmlspecialchars($p['nombre_producto']); ?>">

                        <div class="card-body text-center">
                            <h5 class="card-title fw-bold">
                                <?php echo htmlspecialchars($p['nombre_producto']); ?>
                            </h5>

                            <p class="card-text text-success fw-semibold">
                                $<?php echo number_format($p['precio'], 2); ?>
                            </p>

                            <button class="btn btn-primary btn-sm">
                                <i class="bi bi-cart-plus"></i> Agregar
                            </button>
                        </div>

                    </div>
                </div>
            <?php endforeach; ?>

        <?php endif; ?>
    </div>
</div>

<?php 
require_once 'includes/footer.php'; 
?>