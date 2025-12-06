<?php
require_once '../includes/header.php';
require_once '../includes/functions.php';

// Si ya está logueado, redirigir al inicio
if (isLoggedIn()) {
    redirect('../index.php');
}

// Procesar formulario de login
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = sanitize($_POST['email']);
    $password = $_POST['password'];
    
    // Validaciones
    $errors = [];
    
    if (empty($email)) {
        $errors['email'] = 'El correo electrónico es obligatorio.';
    } elseif (!validateEmail($email)) {
        $errors['email'] = 'El correo electrónico no es válido.';
    }
    
    if (empty($password)) {
        $errors['password'] = 'La contraseña es obligatoria.';
    }
    
    // Si no hay errores, intentar login
    if (empty($errors)) {
        require_once '../config/database.php';
        $conn = getConnection();
        
        // Buscar usuario
        $stmt = $conn->prepare("SELECT * FROM usuarios WHERE correo = :email AND activo = 1");
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch();
        
        if ($user && verifyPassword($password, $user['password'])) {
            // Login exitoso
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['nombre'] = $user['nombre'];
            $_SESSION['apellido'] = $user['apellido'];
            $_SESSION['correo'] = $user['correo'];
            $_SESSION['es_admin'] = (bool)$user['es_admin'];
            
            // Actualizar último login
            $stmt = $conn->prepare("UPDATE usuarios SET ultimo_login = NOW() WHERE id = :id");
            $stmt->execute(['id' => $user['id']]);
            
            $_SESSION['success'] = '¡Bienvenido ' . $user['nombre'] . '! Has iniciado sesión exitosamente.';
            
            // Redirigir según rol
            if ($_SESSION['es_admin']) {
                redirect('../pages/admin/dashboard.php');
            } else {
                redirect('../index.php');
            }
        } else {
            $errors['general'] = 'Correo electrónico o contraseña incorrectos.';
        }
    }
    
    // Guardar datos antiguos para repoblar
    if (!empty($errors)) {
        saveOldData(['email' => $email]);
    }
}
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-5">
                    <!-- Logo -->
                    <div class="text-center mb-4">
                        <div class="mb-3">
                            <i class="fas fa-code-branch fa-3x" style="color: var(--primary-color);"></i>
                        </div>
                        <h2 class="fw-bold" style="color: var(--primary-color);">Iniciar Sesión</h2>
                        <p class="text-muted">Accede a tu cuenta de DigitalForge</p>
                    </div>
                    
                    <!-- Mostrar errores generales -->
                    <?php if (isset($errors['general'])): ?>
                        <div class="alert alert-danger"><?php echo $errors['general']; ?></div>
                    <?php endif; ?>
                    
                    <!-- Formulario -->
                    <form method="POST" action="">
                        <!-- Email -->
                        <div class="mb-4">
                            <label for="email" class="form-label fw-semibold">Correo Electrónico</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0">
                                    <i class="fas fa-envelope text-muted"></i>
                                </span>
                                <input type="email" 
                                       class="form-control <?php echo isset($errors['email']) ? 'is-invalid' : ''; ?>"
                                       id="email" 
                                       name="email" 
                                       value="<?php echo old('email'); ?>"
                                       placeholder="tu@email.com"
                                       required>
                                <?php if (isset($errors['email'])): ?>
                                    <div class="invalid-feedback"><?php echo $errors['email']; ?></div>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <!-- Contraseña -->
                        <div class="mb-4">
                            <label for="password" class="form-label fw-semibold">Contraseña</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0">
                                    <i class="fas fa-lock text-muted"></i>
                                </span>
                                <input type="password" 
                                       class="form-control <?php echo isset($errors['password']) ? 'is-invalid' : ''; ?>"
                                       id="password" 
                                       name="password" 
                                       placeholder="Ingresa tu contraseña"
                                       required>
                                <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <?php if (isset($errors['password'])): ?>
                                    <div class="invalid-feedback"><?php echo $errors['password']; ?></div>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <!-- Recordarme y olvidé contraseña -->
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="remember" name="remember">
                                <label class="form-check-label text-muted" for="remember">Recordarme</label>
                            </div>
                            <a href="forgot_password.php" class="text-decoration-none" style="color: var(--primary-color);">
                                ¿Olvidaste tu contraseña?
                            </a>
                        </div>
                        
                        <!-- Botón de envío -->
                        <div class="d-grid mb-4">
                            <button type="submit" class="btn btn-primary btn-lg fw-semibold">
                                <i class="fas fa-sign-in-alt me-2"></i>Iniciar Sesión
                            </button>
                        </div>
                        
                        <!-- Enlace a registro -->
                        <div class="text-center">
                            <p class="text-muted mb-0">¿No tienes una cuenta?</p>
                            <a href="register.php" class="fw-semibold text-decoration-none" style="color: var(--primary-color);">
                                Regístrate aquí
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Mostrar/ocultar contraseña
    document.getElementById('togglePassword').addEventListener('click', function() {
        const passwordInput = document.getElementById('password');
        const icon = this.querySelector('i');
        
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            passwordInput.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    });
    
    // Auto-focus en el primer campo
    document.getElementById('email').focus();
</script>

<?php 
clearOldData();
require_once '../includes/footer.php'; 
?>