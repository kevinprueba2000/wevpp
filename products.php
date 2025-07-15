<?php
require_once 'config/config.php';
require_once 'classes/Product.php';
require_once 'classes/Category.php';

$product = new Product();
$category = new Category();

// Obtener parámetros de búsqueda y filtros
$search = isset($_GET['search']) ? cleanInput($_GET['search']) : '';
$categoryFilter = isset($_GET['category']) ? (int)$_GET['category'] : 0;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 12;
$offset = ($page - 1) * $limit;

// Obtener productos según filtros
if ($search) {
    $products = $product->searchProducts($search, $limit);
} elseif ($categoryFilter) {
    $products = $product->getProductsByCategory($categoryFilter, $limit);
} else {
    $products = $product->getAllProducts($limit, $offset);
}

$categories = $category->getAllCategories();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Productos - <?php echo SITE_NAME; ?></title>
    <meta name="description" content="Explora nuestra amplia gama de productos únicos: software personalizado, aceites esenciales, figuras artesanales y suscripciones premium.">
    
    <!-- Bootstrap & FontAwesome -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    <!-- Custom CSS -->
    <link href="assets/css/style.css" rel="stylesheet">
    
    <!-- AOS Animation Library -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary fixed-top">
        <div class="container">
            <a class="navbar-brand fw-bold" href="index.php">
                <i class="fas fa-flask me-2"></i>
                AlquimiaTechnologic
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">
                            <i class="fas fa-home me-1"></i>Inicio
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="products.php">
                            <i class="fas fa-boxes me-1"></i>Productos
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="about.php">
                            <i class="fas fa-info-circle me-1"></i>Nosotros
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="contact.php">
                            <i class="fas fa-envelope me-1"></i>Contacto
                        </a>
                    </li>
                </ul>
                
                <ul class="navbar-nav">
                    <li class="nav-item me-3">
                        <a class="nav-link position-relative" href="cart.php">
                            <i class="fas fa-shopping-cart fs-5"></i>
                            <span class="badge bg-warning text-dark position-absolute" id="cart-count">0</span>
                        </a>
                    </li>
                    <?php if (isLoggedIn()): ?>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
                                <i class="fas fa-user-circle me-1"></i>
                                <?php echo $_SESSION['user_name']; ?>
                            </a>
                            <ul class="dropdown-menu shadow-lg border-0">
                                <li><a class="dropdown-item" href="profile.php">
                                    <i class="fas fa-user me-2"></i>Mi Perfil
                                </a></li>
                                <li><a class="dropdown-item" href="orders.php">
                                    <i class="fas fa-shopping-bag me-2"></i>Mis Pedidos
                                </a></li>
                                <?php if (isAdmin()): ?>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item" href="admin/dashboard.php">
                                        <i class="fas fa-tachometer-alt me-2"></i>Panel Admin
                                    </a></li>
                                <?php endif; ?>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="auth/logout.php">
                                    <i class="fas fa-sign-out-alt me-2"></i>Cerrar Sesión
                                </a></li>
                            </ul>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link" href="auth/login.php">
                                <i class="fas fa-sign-in-alt me-1"></i>Iniciar Sesión
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="auth/register.php">
                                <i class="fas fa-user-plus me-1"></i>Registrarse
                            </a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section" style="min-height: 60vh; padding: 120px 0 80px;">
        <div class="particles" id="particles"></div>
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-8 mx-auto text-center hero-content">
                    <h1 class="hero-title mb-4" data-aos="fade-up">
                        Descubre Productos <span class="text-warning">Únicos</span>
                    </h1>
                    <p class="hero-subtitle mb-4" data-aos="fade-up" data-aos-delay="200">
                        🌟 Explora nuestra colección cuidadosamente seleccionada de productos innovadores, 
                        desde software personalizado hasta aceites esenciales premium.
                    </p>
                    
                    <!-- Search Bar -->
                    <div class="row justify-content-center" data-aos="fade-up" data-aos-delay="400">
                        <div class="col-md-8">
                            <form method="GET" action="products.php" class="position-relative">
                                <div class="input-group input-group-lg">
                                    <input type="text" name="search" class="form-control border-0 shadow-lg" 
                                           placeholder="¿Qué estás buscando hoy?" 
                                           value="<?php echo htmlspecialchars($search); ?>"
                                           style="border-radius: 50px 0 0 50px; padding-left: 2rem;">
                                    <button class="btn btn-warning px-4" type="submit" 
                                            style="border-radius: 0 50px 50px 0;">
                                        <i class="fas fa-search me-2"></i>Buscar
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Filters Section -->
    <section class="section bg-light">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="bg-white rounded-custom shadow-lg p-4 mb-4" data-aos="fade-up">
                        <div class="row align-items-center">
                            <div class="col-md-6">
                                <h5 class="mb-3 mb-md-0">
                                    <i class="fas fa-filter me-2 text-primary"></i>
                                    Filtrar por Categoría
                                </h5>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex flex-wrap gap-2">
                                    <a href="products.php" class="btn <?php echo !$categoryFilter ? 'btn-primary' : 'btn-outline-primary'; ?> btn-sm hover-lift">
                                        <i class="fas fa-th-large me-1"></i>Todos
                                    </a>
                                    <?php foreach ($categories as $cat): ?>
                                        <a href="products.php?category=<?php echo $cat['id']; ?>" 
                                           class="btn <?php echo $categoryFilter == $cat['id'] ? 'btn-primary' : 'btn-outline-primary'; ?> btn-sm hover-lift">
                                            <?php echo $cat['name']; ?>
                                        </a>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Products Section -->
    <section class="section">
        <div class="container">
            <!-- Results Header -->
            <div class="row mb-4">
                <div class="col-md-6">
                    <h2 class="text-gradient fw-bold" data-aos="fade-right">
                        <?php if ($search): ?>
                            Resultados para: "<?php echo htmlspecialchars($search); ?>"
                        <?php elseif ($categoryFilter): ?>
                            <?php 
                            $selectedCategory = array_filter($categories, function($cat) use ($categoryFilter) {
                                return $cat['id'] == $categoryFilter;
                            });
                            $selectedCategory = reset($selectedCategory);
                            echo $selectedCategory['name'] ?? 'Categoría';
                            ?>
                        <?php else: ?>
                            Todos los Productos
                        <?php endif; ?>
                    </h2>
                    <p class="text-muted">
                        <i class="fas fa-box me-2"></i>
                        <?php echo count($products); ?> productos encontrados
                    </p>
                </div>
                <div class="col-md-6 text-md-end">
                    <div class="btn-group" role="group" data-aos="fade-left">
                        <button type="button" class="btn btn-outline-primary active" id="grid-view">
                            <i class="fas fa-th-large"></i>
                        </button>
                        <button type="button" class="btn btn-outline-primary" id="list-view">
                            <i class="fas fa-list"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Products Grid -->
            <div class="row" id="products-container">
                <?php if (empty($products)): ?>
                    <div class="col-12 text-center py-5" data-aos="fade-up">
                        <div class="mb-4">
                            <i class="fas fa-search text-muted" style="font-size: 4rem;"></i>
                        </div>
                        <h3 class="text-muted mb-3">No se encontraron productos</h3>
                        <p class="text-muted mb-4">Intenta con otros términos de búsqueda o explora nuestras categorías</p>
                        <a href="products.php" class="btn btn-primary hover-lift">
                            <i class="fas fa-arrow-left me-2"></i>Ver Todos los Productos
                        </a>
                    </div>
                <?php else: ?>
                    <?php 
                    $delay = 100;
                    foreach ($products as $prod): 
                    ?>
                        <div class="col-lg-3 col-md-6 mb-4 product-item" data-aos="fade-up" data-aos-delay="<?php echo $delay; ?>">
                            <div class="product-card h-100 hover-lift">
                                <div class="product-image">
                                    <img src="<?php echo $prod['image'] ?: 'https://images.unsplash.com/photo-1560472354-b33ff0c44a43?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=80'; ?>" 
                                         alt="<?php echo htmlspecialchars($prod['name']); ?>" 
                                         class="img-fluid">
                                    <div class="product-overlay">
                                        <button class="btn btn-light btn-sm me-2 hover-glow" onclick="addToCart(<?php echo $prod['id']; ?>)" title="Agregar al carrito">
                                            <i class="fas fa-shopping-cart"></i>
                                        </button>
                                        <button class="btn btn-light btn-sm me-2 hover-glow" onclick="quickView(<?php echo $prod['id']; ?>)" title="Vista rápida">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button class="btn btn-light btn-sm hover-glow" onclick="toggleWishlist(<?php echo $prod['id']; ?>)" title="Agregar a favoritos">
                                            <i class="fas fa-heart"></i>
                                        </button>
                                    </div>
                                    <?php if ($prod['discount_percentage'] > 0): ?>
                                        <span class="badge bg-danger position-absolute top-0 start-0 m-2 pulse">
                                            -<?php echo $prod['discount_percentage']; ?>%
                                        </span>
                                    <?php endif; ?>
                                    <?php if ($prod['featured']): ?>
                                        <span class="badge bg-warning position-absolute top-0 end-0 m-2">
                                            <i class="fas fa-star"></i> Destacado
                                        </span>
                                    <?php endif; ?>
                                </div>
                                <div class="card-body">
                                    <div class="mb-2">
                                        <span class="badge bg-primary opacity-75 text-white">
                                            <?php 
                                            $productCategory = array_filter($categories, function($cat) use ($prod) {
                                                return $cat['id'] == $prod['category_id'];
                                            });
                                            $productCategory = reset($productCategory);
                                            echo $productCategory['name'] ?? 'General';
                                            ?>
                                        </span>
                                    </div>
                                    <h5 class="card-title mb-2"><?php echo htmlspecialchars($prod['name']); ?></h5>
                                    <p class="card-text text-muted small mb-3">
                                        <?php echo substr(htmlspecialchars($prod['description']), 0, 100); ?>...
                                    </p>
                                    
                                    <!-- Rating -->
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="rating me-2">
                                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                                <i class="fas fa-star text-warning"></i>
                                            <?php endfor; ?>
                                        </div>
                                        <small class="text-muted">(4.8)</small>
                                    </div>
                                    
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <div class="price">
                                            <?php if ($prod['discount_percentage'] > 0): ?>
                                                <span class="text-decoration-line-through text-muted me-2">
                                                    $<?php echo number_format($prod['price'], 0, ',', '.'); ?>
                                                </span>
                                                <span class="text-success fw-bold">
                                                    $<?php echo number_format($prod['price'] * (1 - $prod['discount_percentage'] / 100), 0, ',', '.'); ?>
                                                </span>
                                            <?php else: ?>
                                                <span class="fw-bold text-primary">$<?php echo number_format($prod['price'], 0, ',', '.'); ?></span>
                                            <?php endif; ?>
                                        </div>
                                        <small class="text-muted">
                                            <i class="fas fa-truck me-1"></i>
                                            Envío gratis
                                        </small>
                                    </div>
                                    
                                    <div class="d-grid gap-2">
                                        <button class="btn btn-primary hover-lift" onclick="addToCart(<?php echo $prod['id']; ?>)">
                                            <i class="fas fa-cart-plus me-2"></i>
                                            Agregar al Carrito
                                        </button>
                                        <a href="https://wa.me/593983015307?text=Hola%2C%20quiero%20comprar%20el%20producto:%20<?php echo urlencode($prod['name']); ?>%20-%20$<?php echo number_format($prod['discount_percentage'] > 0 ? $prod['price'] * (1 - $prod['discount_percentage'] / 100) : $prod['price'], 0, ',', '.'); ?>%20-%20AlquimiaTechnologic" 
                                           class="btn btn-success btn-sm hover-lift" target="_blank">
                                            <i class="fab fa-whatsapp me-2"></i>
                                            Comprar por WhatsApp
                                        </a>
                                        <button class="btn btn-outline-primary btn-sm hover-lift" onclick="quickView(<?php echo $prod['id']; ?>)">
                                            <i class="fas fa-eye me-2"></i>
                                            Vista Rápida
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php $delay += 50; ?>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <!-- Load More Button -->
            <?php if (count($products) >= $limit): ?>
                <div class="text-center mt-5" data-aos="fade-up">
                    <button class="btn btn-outline-primary btn-lg hover-lift" id="loadMore">
                        <i class="fas fa-plus me-2"></i>
                        Cargar Más Productos
                    </button>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <!-- Quick View Modal -->
    <div class="modal fade" id="quickViewModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header border-0">
                    <h5 class="modal-title">Vista Rápida</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="quickViewContent">
                    <!-- Content will be loaded here -->
                </div>
            </div>
        </div>
    </div>

    <!-- Newsletter Section -->
    <section class="section bg-primary text-white">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-8 mx-auto text-center">
                    <h2 class="fw-bold mb-3" data-aos="fade-up">
                        <i class="fas fa-bell me-3"></i>
                        ¡No te pierdas nuestras ofertas!
                    </h2>
                    <p class="mb-4" data-aos="fade-up" data-aos-delay="200">
                        Suscríbete y recibe notificaciones sobre nuevos productos, descuentos exclusivos y contenido especial.
                    </p>
                    <form class="row g-3 justify-content-center" data-aos="fade-up" data-aos-delay="400">
                        <div class="col-md-6">
                            <input type="email" class="form-control form-control-lg" placeholder="Tu email aquí...">
                        </div>
                        <div class="col-md-3">
                            <button type="submit" class="btn btn-warning btn-lg w-100 hover-lift">
                                <i class="fas fa-paper-plane me-2"></i>
                                Suscribirse
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-dark text-light">
        <div class="container">
            <div class="row py-5">
                <div class="col-lg-4 mb-4">
                    <h5 class="fw-bold mb-3">
                        <i class="fas fa-flask me-2"></i>
                        AlquimiaTechnologic
                    </h5>
                    <p class="text-muted">
                        Transformando tu día a día con productos únicos y servicios innovadores 
                        que elevan tu estilo de vida.
                    </p>
                    <div class="social-links">
                        <a href="#"><i class="fab fa-facebook-f"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-instagram"></i></a>
                        <a href="#"><i class="fab fa-linkedin-in"></i></a>
                    </div>
                </div>
                <div class="col-lg-2 col-md-6 mb-4">
                    <h6 class="fw-bold mb-3">Productos</h6>
                    <ul class="list-unstyled">
                        <li><a href="#" class="text-muted">Software</a></li>
                        <li><a href="#" class="text-muted">Aceites Esenciales</a></li>
                        <li><a href="#" class="text-muted">Figuras de Yeso</a></li>
                        <li><a href="#" class="text-muted">Suscripciones</a></li>
                    </ul>
                </div>
                <div class="col-lg-2 col-md-6 mb-4">
                    <h6 class="fw-bold mb-3">Empresa</h6>
                    <ul class="list-unstyled">
                        <li><a href="#" class="text-muted">Sobre Nosotros</a></li>
                        <li><a href="#" class="text-muted">Contacto</a></li>
                        <li><a href="#" class="text-muted">Blog</a></li>
                        <li><a href="#" class="text-muted">Careers</a></li>
                    </ul>
                </div>
                <div class="col-lg-2 col-md-6 mb-4">
                    <h6 class="fw-bold mb-3">Soporte</h6>
                    <ul class="list-unstyled">
                        <li><a href="#" class="text-muted">Centro de Ayuda</a></li>
                        <li><a href="#" class="text-muted">Política de Privacidad</a></li>
                        <li><a href="#" class="text-muted">Términos</a></li>
                        <li><a href="#" class="text-muted">FAQ</a></li>
                    </ul>
                </div>
                <div class="col-lg-2 col-md-6 mb-4">
                    <h6 class="fw-bold mb-3">Contacto</h6>
                    <ul class="list-unstyled text-muted">
                        <li><i class="fas fa-map-marker-alt me-2"></i>Bogotá, Colombia</li>
                        <li><i class="fas fa-phone me-2"></i>+57 123 456 7890</li>
                        <li><i class="fas fa-envelope me-2"></i>kevinmoyolema13@gmail.com</li>
                    </ul>
                </div>
            </div>
            <hr class="my-4">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <p class="text-muted mb-0">&copy; 2025 AlquimiaTechnologic. Desarrollado por AlquimiaTechnologic. Todos los derechos reservados.</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <p class="text-muted mb-0">Hecho con <i class="fas fa-heart text-danger"></i> en Colombia</p>
                </div>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script src="assets/js/main.js"></script>
    
    <script>
        // Initialize AOS
        AOS.init({
            duration: 800,
            once: true,
            offset: 100
        });

        // Particles effect
        function createParticles() {
            const particles = document.getElementById('particles');
            const particleCount = 30;

            for (let i = 0; i < particleCount; i++) {
                const particle = document.createElement('div');
                particle.className = 'particle';
                particle.style.left = Math.random() * 100 + '%';
                particle.style.width = Math.random() * 4 + 2 + 'px';
                particle.style.height = particle.style.width;
                particle.style.animationDelay = Math.random() * 15 + 's';
                particle.style.animationDuration = (Math.random() * 10 + 10) + 's';
                particles.appendChild(particle);
            }
        }

        // View toggle
        document.getElementById('grid-view').addEventListener('click', function() {
            document.querySelectorAll('.product-item').forEach(item => {
                item.className = 'col-lg-3 col-md-6 mb-4 product-item';
            });
            this.classList.add('active');
            document.getElementById('list-view').classList.remove('active');
        });

        document.getElementById('list-view').addEventListener('click', function() {
            document.querySelectorAll('.product-item').forEach(item => {
                item.className = 'col-12 mb-4 product-item';
            });
            this.classList.add('active');
            document.getElementById('grid-view').classList.remove('active');
        });

        // Quick view function
        function quickView(productId) {
            // Simulate loading product data
            const modal = new bootstrap.Modal(document.getElementById('quickViewModal'));
            document.getElementById('quickViewContent').innerHTML = `
                <div class="row">
                    <div class="col-md-6">
                        <img src="https://images.unsplash.com/photo-1560472354-b33ff0c44a43?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=80" 
                             class="img-fluid rounded" alt="Producto">
                    </div>
                    <div class="col-md-6">
                        <h4>Producto Ejemplo</h4>
                        <p class="text-muted">Descripción del producto...</p>
                        <div class="price mb-3">
                            <span class="h4 text-primary">$99.000</span>
                        </div>
                        <button class="btn btn-primary w-100" onclick="addToCart(${productId})">
                            <i class="fas fa-cart-plus me-2"></i>
                            Agregar al Carrito
                        </button>
                    </div>
                </div>
            `;
            modal.show();
        }

        // Wishlist toggle
        function toggleWishlist(productId) {
            // Simulate wishlist toggle
            console.log('Toggle wishlist for product:', productId);
        }

        // Initialize particles
        createParticles();

        // Add pulse animation to discount badges
        document.querySelectorAll('.pulse').forEach(badge => {
            badge.style.animation = 'pulse 2s infinite';
        });
    </script>
</body>
</html> 