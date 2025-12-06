    </main> <!-- Cierre del main del header -->

    <!-- Footer -->
    <footer class="bg-dark text-white py-5 mt-5">
        <div class="container">
            <div class="row">
                <!-- Logo y descripción -->
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="footer-brand">
                        <h3 class="mb-3">
                            <i class="fas fa-code-branch me-2" style="color: var(--accent-color);"></i>
                            DigitalForge
                        </h3>
                        <p class="text-light mb-4">
                            Transformamos tu negocio con soluciones digitales innovadoras. 
                            Creación de sitios web, tiendas online, aplicaciones móviles y más.
                        </p>
                        <div class="social-icons">
                            <a href="#" class="text-white me-3"><i class="fab fa-facebook-f"></i></a>
                            <a href="#" class="text-white me-3"><i class="fab fa-twitter"></i></a>
                            <a href="#" class="text-white me-3"><i class="fab fa-instagram"></i></a>
                            <a href="#" class="text-white"><i class="fab fa-linkedin-in"></i></a>
                        </div>
                    </div>
                </div>
                
                <!-- Enlaces rápidos -->
                <div class="col-lg-2 col-md-6 mb-4">
                    <h5 class="mb-3">Enlaces Rápidos</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="../index.php" class="text-light text-decoration-none">Inicio</a></li>
                        <li class="mb-2"><a href="../pages/products.php" class="text-light text-decoration-none">Servicios</a></li>
                        <li class="mb-2"><a href="#" class="text-light text-decoration-none">Nosotros</a></li>
                        <li class="mb-2"><a href="#" class="text-light text-decoration-none">Contacto</a></li>
                        <li class="mb-2"><a href="#" class="text-light text-decoration-none">Blog</a></li>
                    </ul>
                </div>
                
                <!-- Servicios -->
                <div class="col-lg-3 col-md-6 mb-4">
                    <h5 class="mb-3">Servicios</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="#" class="text-light text-decoration-none">Desarrollo Web</a></li>
                        <li class="mb-2"><a href="#" class="text-light text-decoration-none">E-commerce</a></li>
                        <li class="mb-2"><a href="#" class="text-light text-decoration-none">Apps Móviles</a></li>
                        <li class="mb-2"><a href="#" class="text-light text-decoration-none">Marketing Digital</a></li>
                        <li class="mb-2"><a href="#" class="text-light text-decoration-none">Consultoría</a></li>
                    </ul>
                </div>
                
                <!-- Contacto -->
                <div class="col-lg-3 col-md-6 mb-4">
                    <h5 class="mb-3">Contacto</h5>
                    <ul class="list-unstyled">
                        <li class="mb-3">
                            <i class="fas fa-map-marker-alt me-2" style="color: var(--accent-color);"></i>
                            <span class="text-light">Caracas, Venezuela</span>
                        </li>
                        <li class="mb-3">
                            <i class="fas fa-phone me-2" style="color: var(--accent-color);"></i>
                            <span class="text-light">+58 412 123 4567</span>
                        </li>
                        <li class="mb-3">
                            <i class="fas fa-envelope me-2" style="color: var(--accent-color);"></i>
                            <span class="text-light">info@digitalforge.com</span>
                        </li>
                        <li class="mb-3">
                            <i class="fas fa-clock me-2" style="color: var(--accent-color);"></i>
                            <span class="text-light">Lun-Vie: 9:00 - 18:00</span>
                        </li>
                    </ul>
                </div>
            </div>
            
            <hr class="bg-light my-4">
            
            <!-- Copyright -->
            <div class="row">
                <div class="col-md-6">
                    <p class="mb-0">
                        &copy; <?php echo date('Y'); ?> DigitalForge. Todos los derechos reservados.
                    </p>
                </div>
                <div class="col-md-6 text-md-end">
                    <p class="mb-0">
                        <a href="#" class="text-light text-decoration-none me-3">Política de Privacidad</a>
                        <a href="#" class="text-light text-decoration-none">Términos de Servicio</a>
                    </p>
                </div>
            </div>
        </div>
    </footer>
    
    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="../public/js/main.js"></script>
    
    <script>
        // Scripts básicos
        $(document).ready(function() {
            // Cerrar alertas automáticamente después de 5 segundos
            setTimeout(function() {
                $('.alert').alert('close');
            }, 5000);
            
            // Validación de formularios
            $('form').on('submit', function() {
                $(this).find(':submit').prop('disabled', true);
            });
            
            // Tooltips
            $('[data-bs-toggle="tooltip"]').tooltip();
            
            // Contador de productos en carrito
            function actualizarContadorCarrito() {
                $.ajax({
                    url: '../includes/ajax_carrito.php',
                    method: 'GET',
                    success: function(response) {
                        var data = JSON.parse(response);
                        if (data.cantidad > 0) {
                            $('.badge-cart').text(data.cantidad).show();
                        } else {
                            $('.badge-cart').hide();
                        }
                    }
                });
            }
            
            // Actualizar cada 30 segundos si hay usuario logueado
            <?php if ($usuario_logueado): ?>
            setInterval(actualizarContadorCarrito, 30000);
            <?php endif; ?>
        });
    </script>
</body>
</html>