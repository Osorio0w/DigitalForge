<?php
http_response_code(404);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Página no encontrada | DigitalForge</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #3b56a8;
            --secondary-color: #d9e8ed;
            --dark-color: #3c3c3c;
            --accent-color: #aac9bb;
        }
        
        body {
            background-color: var(--secondary-color);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .error-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        
        .error-content {
            text-align: center;
            max-width: 600px;
            padding: 40px;
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(59, 86, 168, 0.1);
        }
        
        .error-code {
            font-size: 8rem;
            font-weight: bold;
            color: var(--primary-color);
            line-height: 1;
            margin-bottom: 20px;
        }
        
        .error-title {
            color: var(--dark-color);
            margin-bottom: 20px;
        }
        
        .error-message {
            color: #666;
            margin-bottom: 30px;
            font-size: 1.1rem;
        }
        
        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            padding: 12px 30px;
            font-weight: 600;
        }
        
        .btn-primary:hover {
            background-color: #2a428a;
            border-color: #2a428a;
        }
    </style>
</head>
<body>
    <div class="error-container">
        <div class="error-content">
            <div class="error-code">404</div>
            <h1 class="error-title">¡Página no encontrada!</h1>
            <p class="error-message">
                Lo sentimos, la página que estás buscando no existe o ha sido movida.
                Puede que hayas seguido un enlace incorrecto o la página haya sido eliminada.
            </p>
            <div class="d-grid gap-3 d-md-flex justify-content-md-center">
                <a href="index.php" class="btn btn-primary">
                    <i class="fas fa-home me-2"></i>Volver al Inicio
                </a>
                <a href="pages/products.php" class="btn btn-outline-primary">
                    <i class="fas fa-store me-2"></i>Ver Servicios
                </a>
            </div>
            <div class="mt-4 pt-4 border-top">
                <p class="text-muted small">
                    Si crees que esto es un error, por favor <a href="contact.php">contáctanos</a>.
                </p>
            </div>
        </div>
    </div>
</body>
</html>