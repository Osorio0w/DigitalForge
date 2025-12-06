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
<body>
    <!-- Navbar Principal -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <!-- Logo -->
            <a class="navbar-brand fw-bold" href="index.php">
                <i class="bi bi-shop me-2"></i>
                DigitalForge
            </a>

            <!-- Botón Hamburguesa -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- Menú Principal -->
            <div class="collapse navbar-collapse" id="navbarMain">
                <!-- Navegación Izquierda -->
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="index.php">
                            <i class="bi bi-house me-1"></i> Inicio
                        </a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
                            <i class="bi bi-grid me-1"></i> Categorías
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="###">categoria1</a></li>
                            <li><a class="dropdown-item" href="###">categoria2</a></li>
                            <li><a class="dropdown-item" href="###">3</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="###">4</a></li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="###.php">
                            <i class="bi bi-percent me-1"></i> Ofertas
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">
                            <i class="bi bi-telephone me-1"></i> Contacto
                        </a>
                    </li>
                </ul>

                <!-- Navegación Derecha -->
                <ul class="navbar-nav ms-auto">
                    <!-- Buscador -->
                    <li class="nav-item me-3">
                        <form class="d-flex">
                            <div class="input-group">
                                <input type="text" class="form-control" placeholder="Buscar productos...">
                                <button class="btn btn-light" type="submit">
                                    <i class="bi bi-search"></i>
                                </button>
                            </div>
                        </form>
                    </li>

                    <!-- Carrito de Compras -->
                    <li class="nav-item me-3">
                        <a class="nav-link position-relative" href="#">
                            <i class="bi bi-cart3"></i> Carrito
                            <?php
                            // Ejemplo de contador dinámico del carrito
                            $cart_count = 0; // Esto vendría de la base de datos
                            if ($cart_count > 0) {
                                echo '<span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">';
                                echo $cart_count;
                                echo '</span>';
                            }
                            ?>
                        </a>
                    </li>

                    <!-- Usuario/Login -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
                            <i class="bi bi-person-circle me-1"></i> Mi Cuenta
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <!-- Estado de login dinámico con PHP -->
                            <?php
                            $is_logged_in = false; // Esto vendría de tu sistema de autenticación
                            $user_name = "Usuario";
                            
                            if ($is_logged_in) {
                                echo '
                                <li><span class="dropdown-item-text fw-bold">Hola, ' . $user_name . '</span></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="#"><i class="bi bi-person me-2"></i>Mi Perfil</a></li>
                                <li><a class="dropdown-item" href="#"><i class="bi bi-bag me-2"></i>Mis Pedidos</a></li>
                                <li><a class="dropdown-item" href="#"><i class="bi bi-heart me-2"></i>Lista de Deseos</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item text-danger" href="#"><i class="bi bi-box-arrow-right me-2"></i>Cerrar Sesión</a></li>
                                ';
                            } else {
                                echo '
                                <li><a class="dropdown-item" href="#"><i class="bi bi-box-arrow-in-right me-2"></i>Iniciar Sesión</a></li>
                                <li><a class="dropdown-item" href="#"><i class="bi bi-person-plus me-2"></i>Registrarse</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="#"><i class="bi bi-question-circle me-2"></i>Ayuda</a></li>
                                ';
                            }
                            ?>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Contenido Principal -->
    <div class="container mt-4">

                <!-- Ejemplo de productos -->
                <h2>Productos Destacados</h2>
                <div class="row mt-3">
                    <div class="col-md-3 mb-4">
                        <div class="card">
                            <img src="sources/gato4.jpeg" class="card-img-top" alt="Producto">
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
                            <img src="sources/gato3.jpeg" class="card-img-top" alt="Producto">
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
                            <img src="sources/gato2.jpg" class="card-img-top" alt="Producto">
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
                            <img src="sources/gato1.jpeg" class="card-img-top" alt="Producto">
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
        </div>
    </div>
    

   <footer class="footer-custom fixed-bottom mt-5 py-4 text-white">
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