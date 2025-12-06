<?php
session_start();
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/config.php';

// Verificar si el usuario está logueado
$usuario_logueado = isset($_SESSION['user_id']);
$es_admin = isset($_SESSION['es_admin']) && $_SESSION['es_admin'] === true;
$nombre_usuario = isset($_SESSION['nombre']) ? $_SESSION['nombre'] : '';

// Obtener cantidad de items en carrito
$cantidad_carrito = 0;
if ($usuario_logueado) {
    $conn = getConnection();
    $stmt = $conn->prepare("SELECT SUM(cantidad) as total FROM carrito WHERE id_usuario = :user_id");
    $stmt->execute(['user_id' => $_SESSION['user_id']]);
    $result = $stmt->fetch();
    $cantidad_carrito = $result['total'] ?? 0;
} elseif (isset($_SESSION['carrito_temp'])) {
    $cantidad_carrito = count($_SESSION['carrito_temp']);
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DigitalForge - Digitalización de Negocios</title>
    
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Roboto:wght@300;400;500&display=swap" rel="stylesheet">
    
    <!-- Estilos personalizados -->
    <link rel="stylesheet" href="../public/css/styles.css">
    
    <style>
        :root {
            --primary-color: #3b56a8;
            --secondary-color: #d9e8ed;
            --dark-color: #3c3c3c;
            --accent-color: #aac9bb;
            --light-color: #ffffff;
            --success-color: #28a745;
            --warning-color: #ffc107;
            --danger-color: #dc3545;
        }
        
        body {
            font-family: 'Roboto', sans-serif;
            color: var(--dark-color);
            padding-top: 76px; /* Para el navbar fijo */
        }
        
        h1, h2, h3, h4, h5, h6 {
            font-family: 'Poppins', sans-serif;
            font-weight: 600;
        }
        
        .navbar {
            background-color: var(--light-color) !important;
            box-shadow: 0 2px 10px rgba(59, 86, 168, 0.1);
            padding: 1rem 0;
            transition: all 0.3s ease;
        }
        
        .navbar-brand {
            font-family: 'Poppins', sans-serif;
            font-weight: 700;
            font-size: 1.8rem;
            color: var(--primary-color) !important;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .navbar-brand i {
            color: var(--accent-color);
        }
        
        .nav-link {
            font-weight: 500;
            color: var(--dark-color) !important;
            padding: 0.5rem 1rem !important;
            transition: color 0.3s ease;
        }
        
        .nav-link:hover {
            color: var(--primary-color) !important;
        }
        
        .nav-link.active {
            color: var(--primary-color) !important;
            font-weight: 600;
        }
        
        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            font-weight: 500;
            padding: 0.5rem 1.5rem;
            transition: all 0.3s ease;
        }
        
        .btn-primary:hover {
            background-color: #2a428a;
            border-color: #2a428a;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(59, 86, 168, 0.2);
        }
        
        .btn-outline-primary {
            color: var(--primary-color);
            border-color: var(--primary-color);
            font-weight: 500;
        }
        
        .btn-outline-primary:hover {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }
        
        .badge-cart {
            position: absolute;
            top: -5px;
            right: -5px;
            background-color: var(--danger-color);
            color: white;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            font-size: 0.7rem;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .dropdown-menu {
            border: none;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            border-radius: 10px;
        }
        
        .dropdown-item {
            padding: 0.5rem 1.5rem;
            font-weight: 500;
        }
        
        .dropdown-item:hover {
            background-color: var(--secondary-color);
            color: var(--primary-color);
        }
        
        .user-avatar {
            width: 35px;
            height: 35px;
            background-color: var(--accent-color);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            font-size: 0.9rem;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .navbar-collapse {
                background-color: white;
                padding: 1rem;
                border-radius: 10px;
                margin-top: 10px;
                box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            }
        }
        
        /* Alertas */
        .alert {
            border-radius: 10px;
            border: none;
            box-shadow: 0 3px 10px rgba(0,0,0,0.1);
        }
        
        .alert-success {
            background-color: rgba(40, 167, 69, 0.1);
            color: var(--success-color);
            border-left: 4px solid var(--success-color);
        }
        
        .alert-danger {
            background-color: rgba(220, 53, 69, 0.1);
            color: var(--danger-color);
            border-left: 4px solid var(--danger-color);
        }
        
        .alert-warning {
            background-color: rgba(255, 193, 7, 0.1);
            color: var(--warning-color);
            border-left: 4px solid var(--warning-color);
        }
        
        .alert-info {
            background-color: rgba(59, 86, 168, 0.1);
            color: var(--primary-color);
            border-left: 4px solid var(--primary-color);
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light fixed-top">
        <div class="container">
            <!-- Logo -->
            <a class="navbar-brand" href="../index.php">
                <i class="fas fa-code-branch"></i>
                <span>DigitalForge</span>
            </a>
            
            <!-- Botón mobile -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <!-- Menú -->
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : ''; ?>" href="../index.php">
                            <i class="fas fa-home me-1"></i> Inicio
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'products.php' ? 'active' : ''; ?>" href="../pages/products.php">
                            <i class="fas fa-laptop-code me-1"></i> Servicios
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">
                            <i class="fas fa-users me-1"></i> Nosotros
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">
                            <i class="fas fa-envelope me-1"></i> Contacto
                        </a>
                    </li>
                    
                    <?php if ($es_admin): ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="adminDropdown" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-crown me-1"></i> Admin
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="../pages/admin/dashboard.php"><i class="fas fa-tachometer-alt me-2"></i> Dashboard</a></li>
                            <li><a class="dropdown-item" href="../pages/admin/products.php"><i class="fas fa-box me-2"></i> Productos</a></li>
                            <li><a class="dropdown-item" href="../pages/admin/users.php"><i class="fas fa-users me-2"></i> Usuarios</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="../pages/admin/orders.php"><i class="fas fa-receipt me-2"></i> Órdenes</a></li>
                        </ul>
                    </li>
                    <?php endif; ?>
                </ul>
                
                <!-- Carrito y Usuario -->
                <ul class="navbar-nav">
                    <!-- Carrito -->
                    <li class="nav-item">
                        <a class="nav-link position-relative" href="../pages/cart.php">
                            <i class="fas fa-shopping-cart"></i>
                            <?php if ($cantidad_carrito > 0): ?>
                                <span class="badge-cart"><?php echo $cantidad_carrito; ?></span>
                            <?php endif; ?>
                        </a>
                    </li>
                    
                    <!-- Usuario -->
                    <?php if ($usuario_logueado): ?>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
                                <div class="user-avatar me-2">
                                    <?php echo strtoupper(substr($nombre_usuario, 0, 1)); ?>
                                </div>
                                <span><?php echo htmlspecialchars($nombre_usuario); ?></span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="../pages/profile.php"><i class="fas fa-user me-2"></i> Mi Perfil</a></li>
                                <li><a class="dropdown-item" href="../pages/orders.php"><i class="fas fa-receipt me-2"></i> Mis Pedidos</a></li>
                                <li><a class="dropdown-item" href="../pages/favorites.php"><i class="fas fa-heart me-2"></i> Favoritos</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item text-danger" href="../pages/logout.php"><i class="fas fa-sign-out-alt me-2"></i> Cerrar Sesión</a></li>
                            </ul>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link" href="../pages/login.php">
                                <i class="fas fa-sign-in-alt me-1"></i> Iniciar Sesión
                            </a>
                        </li>
                        <li class="nav-item ms-2">
                            <a class="btn btn-primary" href="../pages/register.php">
                                <i class="fas fa-user-plus me-1"></i> Registrarse
                            </a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Contenedor principal -->
    <main class="container-fluid px-0">
        <!-- Mostrar mensajes de sesión -->
        <?php if (isset($_SESSION['success'])): ?>
            <div class="container mt-4">
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?php echo $_SESSION['success']; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            </div>
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>
        
        <?php if (isset($_SESSION['error'])): ?>
            <div class="container mt-4">
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?php echo $_SESSION['error']; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            </div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>
        
        <?php if (isset($_SESSION['warning'])): ?>
            <div class="container mt-4">
                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                    <?php echo $_SESSION['warning']; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            </div>
            <?php unset($_SESSION['warning']); ?>
        <?php endif; ?>
        
        <?php if (isset($_SESSION['info'])): ?>
            <div class="container mt-4">
                <div class="alert alert-info alert-dismissible fade show" role="alert">
                    <?php echo $_SESSION['info']; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            </div>
            <?php unset($_SESSION['info']); ?>
        <?php endif; ?>