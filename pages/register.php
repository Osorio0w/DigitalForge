<?php
require_once '../includes/header.php';
require_once '../includes/functions.php';

// Si ya está logueado, redirigir al inicio
if (isLoggedIn()) {
    redirect('../index.php');
}

// Procesar formulario de registro
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitizar datos
    $nombre = sanitize($_POST['nombre']);
    $apellido = sanitize($_POST['apellido']);
    $email = sanitize($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $telefono = sanitize($_POST['telefono']);
    $acepto_terminos = isset($_POST['acepto_terminos']);
    
    // Validaciones
    $errors = [];
    
    // Validar nombre
    if (empty($nombre)) {
        $errors['nombre'] = 'El nombre es obligatorio.';
    } elseif (strlen($nombre) < 2) {
        $errors['nombre'] = 'El nombre debe tener al menos 2 caracteres.';
    }
    
    // Validar apellido
    if (empty($apellido)) {
        $errors['apellido'] = 'El apellido es obligatorio.';
    }
    
    // Validar email
    if (empty($email)) {
        $errors['email'] = 'El correo electrónico es obligatorio.';
    } elseif (!validateEmail($email)) {
        $errors['email'] = 'El correo electrónico no es válido.';
    }
    
    // Validar contraseña
    if (empty($password)) {
        $errors['password'] = 'La contraseña es obligatoria.';
    } elseif (!validatePassword($password)) {
        $errors['password'] = 'La contraseña debe tener al menos 8 caracteres, incluyendo letras y números.';
    }
    
    // Validar confirmación de contraseña
    if (empty($confirm_password)) {
        $errors['confirm_password'] = 'Debes confirmar tu contraseña.';
    } elseif ($password !== $confirm_password) {
        $errors['confirm_password'] = 'Las contraseñas no coinciden.';
    }
    
    // Validar términos
    if (!$acepto_terminos) {
        $errors['acepto_terminos'] = 'Debes aceptar los términos y condiciones.';
    }
    
    // Si no hay errores, registrar usuario
    if (empty($errors)) {
        require_once '../config/database.php';
        $conn = getConnection();
        
        // Verificar si el email ya existe
        $stmt = $conn->prepare("SELECT id FROM usuarios WHERE correo = :email");
        $stmt->execute(['email' => $email]);
        
        if ($stmt->fetch()) {
            $errors['email'] = 'Este correo electrónico ya está registrado.';
        } else {
            // Hash de la contraseña
            $hashed_password = hashPassword($password);
            
            // Insertar usuario
            $stmt = $conn->prepare("
                INSERT INTO usuarios (nombre, apellido, correo, password, telefono, fecha_registro)
                VALUES (:nombre, :apellido, :correo, :password, :telefono, NOW())
            ");
            
            $success = $stmt->execute([
                'nombre' => $nombre,
                'apellido' => $apellido,
                'correo' => $email,
                'password' => $hashed_password,
                'telefono' => $telefono ?: null
            ]);
            
            if ($success) {
                // Obtener ID del nuevo usuario
                $user_id = $conn->lastInsertId();
                
                // Iniciar sesión automáticamente
                $_SESSION['user_id'] = $user_id;
                $_SESSION['nombre'] = $nombre;
                $_SESSION['apellido'] = $apellido;
                $_SESSION['correo'] = $email;
                $_SESSION['es_admin'] = false;
                
                $_SESSION['success'] = '¡Cuenta creada exitosamente! Bienvenido a DigitalForge.';
                redirect('../index.php');
            } else {
                $errors['general'] = 'Error al crear la cuenta. Por favor, intenta nuevamente.';
            }
        }
    }
    
    // Guardar datos antiguos para repoblar
    if (!empty($errors)) {
        saveOldData($_POST);
    }
}
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-7">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-5">
                    <!-- Logo y título -->
                    <div class="text-center mb-4">
                        <div class="mb-3">
                            <i class="fas fa-user-plus fa-3x" style="color: var(--primary-color);"></i>
                        </div>
                        <h2 class="fw-bold" style="color: var(--primary-color);">Crear Cuenta</h2>
                        <p class="text-muted">Regístrate para acceder a todos nuestros servicios</p>
                    </div>
                    
                    <!-- Mostrar errores generales -->
                    <?php if (isset($errors['general'])): ?>
                        <div class="alert alert-danger"><?php echo $errors['general']; ?></div>
                    <?php endif; ?>
                    
                    <!-- Formulario -->
                    <form method="POST" action="">
                        <div class="row">
                            <!-- Nombre -->
                            <div class="col-md-6 mb-4">
                                <label for="nombre" class="form-label fw-semibold">Nombre *</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0">
                                        <i class="fas fa-user text-muted"></i>
                                    </span>
                                    <input type="text" 
                                           class="form-control <?php echo isset($errors['nombre']) ? 'is-invalid' : ''; ?>"
                                           id="nombre" 
                                           name="nombre" 
                                           value="<?php echo old('nombre'); ?>"
                                           placeholder="Tu nombre"
                                           required>
                                    <?php if (isset($errors['nombre'])): ?>
                                        <div class="invalid-feedback"><?php echo $errors['nombre']; ?></div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            
                            <!-- Apellido -->
                            <div class="col-md-6 mb-4">
                                <label for="apellido" class="form-label fw-semibold">Apellido *</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0">
                                        <i class="fas fa-user text-muted"></i>
                                    </span>
                                    <input type="text" 
                                           class="form-control <?php echo isset($errors['apellido']) ? 'is-invalid' : ''; ?>"
                                           id="apellido" 
                                           name="apellido" 
                                           value="<?php echo old('apellido'); ?>"
                                           placeholder="Tu apellido"
                                           required>
                                    <?php if (isset($errors['apellido'])): ?>
                                        <div class="invalid-feedback"><?php echo $errors['apellido']; ?></div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Email -->
                        <div class="mb-4">
                            <label for="email" class="form-label fw-semibold">Correo Electrónico *</label>
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
                        
                        <!-- Teléfono (opcional) -->
                        <div class="mb-4">
                            <label for="telefono" class="form-label fw-semibold">Teléfono <span class="text-muted">(Opcional)</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0">
                                    <i class="fas fa-phone text-muted"></i>
                                </span>
                                <input type="tel" 
                                       class="form-control"
                                       id="telefono" 
                                       name="telefono" 
                                       value="<?php echo old('telefono'); ?>"
                                       placeholder="+58 412 123 4567">
                            </div>
                        </div>
                        
                        <div class="row">
                            <!-- Contraseña -->
                            <div class="col-md-6 mb-4">
                                <label for="password" class="form-label fw-semibold">Contraseña *</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0">
                                        <i class="fas fa-lock text-muted"></i>
                                    </span>
                                    <input type="password" 
                                           class="form-control <?php echo isset($errors['password']) ? 'is-invalid' : ''; ?>"
                                           id="password" 
                                           name="password" 
                                           placeholder="Mínimo 8 caracteres"
                                           required>
                                    <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <?php if (isset($errors['password'])): ?>
                                        <div class="invalid-feedback"><?php echo $errors['password']; ?></div>
                                    <?php endif; ?>
                                </div>
                                <small class="text-muted">Debe incluir letras y números</small>
                            </div>
                            
                            <!-- Confirmar Contraseña -->
                            <div class="col-md-6 mb-4">
                                <label for="confirm_password" class="form-label fw-semibold">Confirmar Contraseña *</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0">
                                        <i class="fas fa-lock text-muted"></i>
                                    </span>
                                    <input type="password" 
                                           class="form-control <?php echo isset($errors['confirm_password']) ? 'is-invalid' : ''; ?>"
                                           id="confirm_password" 
                                           name="confirm_password" 
                                           placeholder="Repite tu contraseña"
                                           required>
                                    <button class="btn btn-outline-secondary" type="button" id="toggleConfirmPassword">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <?php if (isset($errors['confirm_password'])): ?>
                                        <div class="invalid-feedback"><?php echo $errors['confirm_password']; ?></div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Términos y condiciones -->
                        <div class="mb-4">
                            <div class="form-check <?php echo isset($errors['acepto_terminos']) ? 'is-invalid' : ''; ?>">
                                <input class="form-check-input" type="checkbox" id="acepto_terminos" name="acepto_terminos" <?php echo old('acepto_terminos') ? 'checked' : ''; ?>>
                                <label class="form-check-label" for="acepto_terminos">
                                    Acepto los <a href="#" class="text-decoration-none" style="color: var(--primary-color);">términos y condiciones</a> y la <a href="#" class="text-decoration-none" style="color: var(--primary-color);">política de privacidad</a> *
                                </label>
                                <?php if (isset($errors['acepto_terminos'])): ?>
                                    <div class="invalid-feedback d-block"><?php echo $errors['acepto_terminos']; ?></div>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <!-- Botón de envío -->
                        <div class="d-grid mb-4">
                            <button type="submit" class="btn btn-primary btn-lg fw-semibold">
                                <i class="fas fa-user-plus me-2"></i>Crear Cuenta
                            </button>
                        </div>
                        
                        <!-- Enlace a login -->
                        <div class="text-center">
                            <p class="text-muted mb-0">¿Ya tienes una cuenta?</p>
                            <a href="login.php" class="fw-semibold text-decoration-none" style="color: var(--primary-color);">
                                Inicia sesión aquí
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
    
    // Mostrar/ocultar confirmación de contraseña
    document.getElementById('toggleConfirmPassword').addEventListener('click', function() {
        const confirmInput = document.getElementById('confirm_password');
        const icon = this.querySelector('i');
        
        if (confirmInput.type === 'password') {
            confirmInput.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            confirmInput.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    });
    
    // Validar contraseña en tiempo real
    document.getElementById('password').addEventListener('input', function() {
        const password = this.value;
        const feedback = this.nextElementSibling?.nextElementSibling;
        
        if (password.length > 0 && password.length < 8) {
            if (feedback) feedback.textContent = 'La contraseña debe tener al menos 8 caracteres';
        } else if (!/(?=.*[A-Za-z])(?=.*\d)/.test(password) && password.length >= 8) {
            if (feedback) feedback.textContent = 'Debe incluir letras y números';
        }
    });
    
    // Auto-focus en el primer campo
    document.getElementById('nombre').focus();
</script>

<?php 
clearOldData();
require_once '../includes/footer.php'; 
?>