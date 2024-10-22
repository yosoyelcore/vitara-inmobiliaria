<?php
// classes/Vendedores.php

class Vendedores {
    private $wpdb;

    public function __construct() {
        global $wpdb;
        $this->wpdb = $wpdb;

        add_action('init', [$this, 'init_vendedores']);
        add_shortcode('vendedores_formulario', [$this, 'vendedores_formulario_shortcode']);
    }

    public function init_vendedores() {
        // Aquí podrías inicializar algo relacionado con los vendedores
    }

    // Método para obtener todos los vendedores
    public function get_all_vendedores() {
        $tabla_vendedores = 'vendedores';
        $query = "SELECT * FROM $tabla_vendedores";
        $resultados = $this->wpdb->get_results($query);
        return $resultados;
    }

    // Método para agregar un nuevo vendedor
    public function add_vendedor($nombre, $apellido, $telefono) {
        $tabla_vendedores = 'vendedores';
        $this->wpdb->insert($tabla_vendedores, [
            'nombre' => $nombre,
            'apellido' => $apellido,
            'telefono' => $telefono
        ]);
        return $this->wpdb->insert_id;
    }

    // Shortcode para mostrar el formulario y listado de vendedores
    public function vendedores_formulario_shortcode() {
        ob_start();

        // Si el formulario es enviado, procesar la información
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['nombre'], $_POST['apellido'], $_POST['telefono'])) {
            $nombre = sanitize_text_field($_POST['nombre']);
            $apellido = sanitize_text_field($_POST['apellido']);
            $telefono = sanitize_text_field($_POST['telefono']);

            // Insertar el nuevo vendedor
            $this->add_vendedor($nombre, $apellido, $telefono);
            echo "<p>Vendedor añadido correctamente.</p>";
        }

        // Mostrar el formulario para añadir vendedor
        echo '
        <form method="POST">
            <label for="nombre">Nombre:</label>
            <input type="text" name="nombre" required>
            
            <label for="apellido">Apellido:</label>
            <input type="text" name="apellido" required>
            
            <label for="telefono">Teléfono:</label>
            <input type="text" name="telefono" required>
            
            <button type="submit">Añadir Vendedor</button>
        </form>
        ';

        // Mostrar la lista de vendedores
        $vendedores = $this->get_all_vendedores();
        if (!empty($vendedores)) {
            echo '<h2>Listado de Vendedores</h2>';
            echo '<ul>';
            foreach ($vendedores as $vendedor) {
                echo "<li>ID: {$vendedor->id} - Nombre: {$vendedor->nombre} {$vendedor->apellido} - Teléfono: {$vendedor->telefono}</li>";
            }
            echo '</ul>';
        } else {
            echo "<p>No hay vendedores registrados.</p>";
        }

        return ob_get_clean();
    }
}

// Iniciar la clase
new Vendedores();
