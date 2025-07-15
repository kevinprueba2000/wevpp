<?php
require_once __DIR__ . '/../config/config.php';

// Verificar si es administrador
if (!isLoggedIn() || !isAdmin()) {
    redirect(SITE_URL . '/auth/login.php');
}

// Simular configuración actual
$settings = [
    'site_name' => 'AlquimiaTechnologic',
    'site_description' => 'Especialistas en software personalizado, aceites esenciales y figuras artesanales',
    'contact_email' => 'kevinmoyolema13@gmail.com',
    'contact_phone' => '+593 983015307',
    'whatsapp_number' => '+593 983015307',
    'address' => 'Ecuador',
    'google_maps_embed' => 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d5533.426740605852!2d-78.59865183764917!3d-1.3011012547784577!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x91d3833075ab6891%3A0xc5bed5e18459cf30!2sALQUIMIA%20ESENCIAL!5e0!3m2!1ses!2sec!4v1752461988725!5m2!1ses!2sec',
    'facebook_url' => 'https://facebook.com/alquimiatechnologic',
    'instagram_url' => 'https://instagram.com/alquimiatechnologic',
    'whatsapp_url' => 'https://wa.me/593983015307',
    'maintenance_mode' => false,
    'allow_registration' => true,
    'email_notifications' => true
];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Configuración - Admin <?php echo SITE_NAME; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="../assets/css/admin.css" rel="stylesheet">
</head>
<body>
    <div class="wrapper">
        <!-- Sidebar -->
        <nav id="sidebar" class="sidebar">
            <div class="sidebar-header">
                <h3>
                    <i class="fas fa-flask"></i>
                    Admin Panel
                </h3>
            </div>
            
            <ul class="list-unstyled components">
                <li>
                    <a href="dashboard.php">
                        <i class="fas fa-tachometer-alt"></i>
                        Dashboard
                    </a>
                </li>
                <li>
                    <a href="products.php">
                        <i class="fas fa-box"></i>
                        Productos
                    </a>
                </li>
                <li>
                    <a href="categories.php">
                        <i class="fas fa-tags"></i>
                        Categorías
                    </a>
                </li>
                <li>
                    <a href="orders.php">
                        <i class="fas fa-shopping-cart"></i>
                        Pedidos
                    </a>
                </li>
                <li>
                    <a href="users.php">
                        <i class="fas fa-users"></i>
                        Usuarios
                    </a>
                </li>
                <li>
                    <a href="messages.php">
                        <i class="fas fa-envelope"></i>
                        Mensajes
                    </a>
                </li>
                <li class="active">
                    <a href="settings.php">
                        <i class="fas fa-cog"></i>
                        Configuración
                    </a>
                </li>
            </ul>
            
            <div class="sidebar-footer">
                <a href="../index.php" class="btn btn-outline-light btn-sm">
                    <i class="fas fa-eye me-2"></i>Ver Sitio
                </a>
                <a href="../auth/logout.php" class="btn btn-outline-danger btn-sm">
                    <i class="fas fa-sign-out-alt me-2"></i>Salir
                </a>
            </div>
        </nav>

        <!-- Page Content -->
        <div id="content">
            <!-- Top Navigation -->
            <nav class="navbar navbar-expand-lg navbar-light bg-light">
                <div class="container-fluid">
                    <button type="button" id="sidebarCollapse" class="btn btn-primary">
                        <i class="fas fa-bars"></i>
                    </button>
                    
                    <div class="ms-auto">
                        <div class="dropdown">
                            <button class="btn btn-outline-primary dropdown-toggle" type="button" id="userDropdown" data-bs-toggle="dropdown">
                                <i class="fas fa-user me-2"></i>
                                <?php echo $_SESSION['user_name']; ?>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="profile.php">Mi Perfil</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="../auth/logout.php">Cerrar Sesión</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </nav>

            <!-- Main Content -->
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h1 class="h3 mb-0">Configuración del Sistema</h1>
                            <button class="btn btn-primary" onclick="saveSettings()">
                                <i class="fas fa-save me-2"></i>Guardar Cambios
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Settings Tabs -->
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <ul class="nav nav-tabs card-header-tabs" id="settingsTabs" role="tablist">
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link active" id="general-tab" data-bs-toggle="tab" data-bs-target="#general" type="button" role="tab">
                                            <i class="fas fa-cog me-2"></i>General
                                        </button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" id="contact-tab" data-bs-toggle="tab" data-bs-target="#contact" type="button" role="tab">
                                            <i class="fas fa-address-book me-2"></i>Contacto
                                        </button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" id="social-tab" data-bs-toggle="tab" data-bs-target="#social" type="button" role="tab">
                                            <i class="fas fa-share-alt me-2"></i>Redes Sociales
                                        </button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" id="system-tab" data-bs-toggle="tab" data-bs-target="#system" type="button" role="tab">
                                            <i class="fas fa-server me-2"></i>Sistema
                                        </button>
                                    </li>
                                </ul>
                            </div>
                            <div class="card-body">
                                <div class="tab-content" id="settingsTabContent">
                                    <!-- General Settings -->
                                    <div class="tab-pane fade show active" id="general" role="tabpanel">
                                        <form id="generalForm">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label class="form-label">Nombre del Sitio</label>
                                                        <input type="text" class="form-control" name="site_name" value="<?php echo $settings['site_name']; ?>" required>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label class="form-label">Descripción del Sitio</label>
                                                        <textarea class="form-control" name="site_description" rows="3"><?php echo $settings['site_description']; ?></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label class="form-label">Logo URL</label>
                                                        <input type="url" class="form-control" name="logo_url" value="https://images.unsplash.com/photo-1560472354-b33ff0c44a43?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=80">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label class="form-label">Favicon URL</label>
                                                        <input type="url" class="form-control" name="favicon_url" value="https://images.unsplash.com/photo-1560472354-b33ff0c44a43?ixlib=rb-4.0.3&auto=format&fit=crop&w=32&q=80">
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>

                                    <!-- Contact Settings -->
                                    <div class="tab-pane fade" id="contact" role="tabpanel">
                                        <form id="contactForm">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label class="form-label">Email de Contacto</label>
                                                        <input type="email" class="form-control" name="contact_email" value="<?php echo $settings['contact_email']; ?>" required>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label class="form-label">Teléfono de Contacto</label>
                                                        <input type="tel" class="form-control" name="contact_phone" value="<?php echo $settings['contact_phone']; ?>" required>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label class="form-label">Número de WhatsApp</label>
                                                        <input type="tel" class="form-control" name="whatsapp_number" value="<?php echo $settings['whatsapp_number']; ?>" required>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label class="form-label">Dirección</label>
                                                        <input type="text" class="form-control" name="address" value="<?php echo $settings['address']; ?>" required>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div class="mb-3">
                                                <label class="form-label">Google Maps Embed URL</label>
                                                <textarea class="form-control" name="google_maps_embed" rows="3"><?php echo $settings['google_maps_embed']; ?></textarea>
                                                <small class="text-muted">URL completa del iframe de Google Maps</small>
                                            </div>
                                        </form>
                                    </div>

                                    <!-- Social Media Settings -->
                                    <div class="tab-pane fade" id="social" role="tabpanel">
                                        <form id="socialForm">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label class="form-label">Facebook URL</label>
                                                        <input type="url" class="form-control" name="facebook_url" value="<?php echo $settings['facebook_url']; ?>">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label class="form-label">Instagram URL</label>
                                                        <input type="url" class="form-control" name="instagram_url" value="<?php echo $settings['instagram_url']; ?>">
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label class="form-label">WhatsApp URL</label>
                                                        <input type="url" class="form-control" name="whatsapp_url" value="<?php echo $settings['whatsapp_url']; ?>">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label class="form-label">YouTube URL</label>
                                                        <input type="url" class="form-control" name="youtube_url" value="">
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label class="form-label">Twitter URL</label>
                                                        <input type="url" class="form-control" name="twitter_url" value="">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label class="form-label">LinkedIn URL</label>
                                                        <input type="url" class="form-control" name="linkedin_url" value="">
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>

                                    <!-- System Settings -->
                                    <div class="tab-pane fade" id="system" role="tabpanel">
                                        <form id="systemForm">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <div class="form-check form-switch">
                                                            <input class="form-check-input" type="checkbox" name="maintenance_mode" id="maintenanceMode" <?php echo $settings['maintenance_mode'] ? 'checked' : ''; ?>>
                                                            <label class="form-check-label" for="maintenanceMode">
                                                                Modo Mantenimiento
                                                            </label>
                                                        </div>
                                                        <small class="text-muted">Activa el modo mantenimiento para realizar actualizaciones</small>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <div class="form-check form-switch">
                                                            <input class="form-check-input" type="checkbox" name="allow_registration" id="allowRegistration" <?php echo $settings['allow_registration'] ? 'checked' : ''; ?>>
                                                            <label class="form-check-label" for="allowRegistration">
                                                                Permitir Registro de Usuarios
                                                            </label>
                                                        </div>
                                                        <small class="text-muted">Permite que nuevos usuarios se registren</small>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <div class="form-check form-switch">
                                                            <input class="form-check-input" type="checkbox" name="email_notifications" id="emailNotifications" <?php echo $settings['email_notifications'] ? 'checked' : ''; ?>>
                                                            <label class="form-check-label" for="emailNotifications">
                                                                Notificaciones por Email
                                                            </label>
                                                        </div>
                                                        <small class="text-muted">Enviar notificaciones por email</small>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <div class="form-check form-switch">
                                                            <input class="form-check-input" type="checkbox" name="debug_mode" id="debugMode">
                                                            <label class="form-check-label" for="debugMode">
                                                                Modo Debug
                                                            </label>
                                                        </div>
                                                        <small class="text-muted">Activa el modo debug para desarrollo</small>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label class="form-label">Zona Horaria</label>
                                                        <select class="form-select" name="timezone">
                                                            <option value="America/Guayaquil" selected>America/Guayaquil (Ecuador)</option>
                                                            <option value="UTC">UTC</option>
                                                            <option value="America/New_York">America/New_York</option>
                                                            <option value="Europe/Madrid">Europe/Madrid</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label class="form-label">Idioma por Defecto</label>
                                                        <select class="form-select" name="default_language">
                                                            <option value="es" selected>Español</option>
                                                            <option value="en">English</option>
                                                            <option value="fr">Français</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Backup Section -->
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0">
                                    <i class="fas fa-database me-2"></i>
                                    Respaldo y Mantenimiento
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="d-grid">
                                            <button class="btn btn-outline-primary" onclick="createBackup()">
                                                <i class="fas fa-download me-2"></i>Crear Respaldo
                                            </button>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="d-grid">
                                            <button class="btn btn-outline-success" onclick="clearCache()">
                                                <i class="fas fa-broom me-2"></i>Limpiar Caché
                                            </button>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="d-grid">
                                            <button class="btn btn-outline-warning" onclick="optimizeDatabase()">
                                                <i class="fas fa-tools me-2"></i>Optimizar Base de Datos
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/js/admin.js"></script>
    
    <script>
        // Settings management functions
        function saveSettings() {
            // Collect all form data
            const generalForm = document.getElementById('generalForm');
            const contactForm = document.getElementById('contactForm');
            const socialForm = document.getElementById('socialForm');
            const systemForm = document.getElementById('systemForm');
            
            const formData = new FormData();
            
            // Add form data from all tabs
            [generalForm, contactForm, socialForm, systemForm].forEach(form => {
                const data = new FormData(form);
                for (let [key, value] of data.entries()) {
                    formData.append(key, value);
                }
            });
            
            // Simulate saving settings
            alert('Configuración guardada correctamente');
            
            // Show success message
            const alertDiv = document.createElement('div');
            alertDiv.className = 'alert alert-success alert-dismissible fade show';
            alertDiv.innerHTML = `
                <i class="fas fa-check-circle me-2"></i>
                Configuración guardada correctamente
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;
            
            document.querySelector('.container-fluid').insertBefore(alertDiv, document.querySelector('.row'));
            
            // Remove alert after 3 seconds
            setTimeout(() => {
                alertDiv.remove();
            }, 3000);
        }
        
        function createBackup() {
            // Simulate creating backup
            alert('Respaldo creado correctamente');
        }
        
        function clearCache() {
            // Simulate clearing cache
            alert('Caché limpiado correctamente');
        }
        
        function optimizeDatabase() {
            // Simulate database optimization
            alert('Base de datos optimizada correctamente');
        }
        
        // Auto-save functionality
        let autoSaveTimer;
        document.querySelectorAll('input, textarea, select').forEach(element => {
            element.addEventListener('change', () => {
                clearTimeout(autoSaveTimer);
                autoSaveTimer = setTimeout(() => {
                    console.log('Auto-saving settings...');
                }, 2000);
            });
        });
    </script>
</body>
</html> 