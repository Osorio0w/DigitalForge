<?php 
require_once 'includes/header.php'; 
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DigitalForge</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat&family=Unbounded:wght@500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
</head>

<body class="d-flex flex-column min-vh-100">

    <!-- NAVBAR VIENE DE includes/header.php -->

    <!-- Contenido Principal -->
    <div class="container mt-4 flex-grow-1">

        <!-- Ejemplo de productos -->
        <h2>Productos Destacados</h2>

        <div class="row mt-3">

            <div class="col-md-3 mb-4">
                <div class="card">
                    <img src="public/images/productos/gato4.jpeg" class="card-img-top" alt="Producto">
                    <div class="card-body">
                        <h5 class="card-title">Producto 1</h5>
                        <p class="card-text">$99.00</p>
                        <button class="btn btn-primary btn-sm">
                            <i class="bi bi-cart-plus"></i> Agregar
                        </button>
                    </div>
                </div>
            </div>

            <div class="col-md-3 mb-4">
                <div class="card">
                    <img src="public/images/productos/gato3.jpeg" class="card-img-top" alt="Producto">
                    <div class="card-body">
                        <h5 class="card-title">Producto 2</h5>
                        <p class="card-text">$100.00</p>
                        <button class="btn btn-primary btn-sm">
                            <i class="bi bi-cart-plus"></i> Agregar
                        </button>
                    </div>
                </div>
            </div>

            <div class="col-md-3 mb-4">
                <div class="card">
                    <img src="public/images/productos/gato2.jpg" class="card-img-top" alt="Producto">
                    <div class="card-body">
                        <h5 class="card-title">Producto 3</h5>
                        <p class="card-text">$100.00</p>
                        <button class="btn btn-primary btn-sm">
                            <i class="bi bi-cart-plus"></i> Agregar
                        </button>
                    </div>
                </div>
            </div>

            <div class="col-md-3 mb-4">
                <div class="card">
                    <img src="public/images/productos/gato1.jpeg" class="card-img-top" alt="Producto">
                    <div class="card-body">
                        <h5 class="card-title">Producto 4</h5>
                        <p class="card-text">$100.00</p>
                        <button class="btn btn-primary btn-sm">
                            <i class="bi bi-cart-plus"></i> Agregar
                        </button>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- FOOTER -->
    <footer class="footer-custom mt-5 py-4 text-white">
        <div class="container">
            <div class="row align-items-start">

                <!-- COLUMNA 1 -->
                <div class="col-md-6 mb-3">
                    <h4 class="fw-bold titulo-logo text-white">DigitalForge</h4>
                    <p class="footer-text">
                        Tu tienda digital confiable.  
                        Productos seleccionados y precios accesibles.
                    </p>
                </div>

                <!-- COLUMNA 2 -->
                <div class="col-md-6 mb-3 text-md-end">
                    <h5 class="fw-bold">Síguenos</h5>
                    <a href="#" class="footer-social"><i class="bi bi-facebook"></i></a>
                    <a href="#" class="footer-social"><i class="bi bi-instagram"></i></a>
                    <a href="#" class="footer-social"><i class="bi bi-whatsapp"></i></a>
                </div>

            </div>

            <hr class="footer-divider">

            <div class="text-center small py-4">
                © 2025 DigitalForge — Todos los derechos reservados.
            </div>
        </div>
    </footer>

    <!-- Bootstrap JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
