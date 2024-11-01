<?php
// Verifica si WordPress está cargado para evitar acceso directo
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
?>

<header class="custom-header">
    <div class="container">
        <!-- Logo de la empresa -->
        <div class="logo">
            <a href="<?php echo home_url(); ?>">
                <img src="https://soyelcore.com/terave/wp-content/uploads/2024/10/images.png">
            </a>
        </div>

        <!-- Menú de navegación -->
        <nav class="main-menu">
            <?php
            wp_nav_menu(array(
                'theme_location' => 'header-menu',
                'container' => false, // Elimina el div contenedor
                'menu_class' => 'nav', // Clase personalizada para los enlaces del menú
                'fallback_cb' => false // No mostrar un menú por defecto si no se ha asignado un menú
            ));
            ?>
        </nav>
    </div>
</header>
