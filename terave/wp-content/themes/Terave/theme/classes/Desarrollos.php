<?php
// classes/Desarrollos.php

class Desarrollos {
    private $wpdb;

    public function __construct() {
        global $wpdb;
        $this->wpdb = $wpdb;

        add_action('init', [$this, 'init_desarrollos']);
        add_shortcode('desarrollos_formulario', [$this, 'desarrollos_formulario_shortcode']);
    }

    public function init_desarrollos() {
        // Aquí podrías inicializar algo relacionado con los desarrollos
    }

    // Método para obtener todos los desarrollos
    public function get_all_desarrollos() {
        $tabla_desarrollos = 'desarrollos';
        $query = "SELECT * FROM $tabla_desarrollos";
        return $this->wpdb->get_results($query);
    }

    // Método para agregar un nuevo desarrollo
    public function add_desarrollo($nombre, $numero_lotes, $lotes_disponibles, $lotes_utilizados, $medidas) {
        $tabla_desarrollos = 'desarrollos';
        $this->wpdb->insert($tabla_desarrollos, [
            'nombre' => $nombre,
            'numero_lotes' => $numero_lotes,
            'lotes_disponibles' => $lotes_disponibles,
            'lotes_utilizados' => $lotes_utilizados,
            'medidas' => $medidas
        ]);
        return $this->wpdb->insert_id;
    }

    // Método para actualizar un desarrollo
    public function update_desarrollo($id, $nombre, $numero_lotes, $lotes_disponibles, $lotes_utilizados, $medidas) {
        $tabla_desarrollos = 'desarrollos';
        $this->wpdb->update(
            $tabla_desarrollos,
            [
                'nombre' => $nombre,
                'numero_lotes' => $numero_lotes,
                'lotes_disponibles' => $lotes_disponibles,
                'lotes_utilizados' => $lotes_utilizados,
                'medidas' => $medidas
            ],
            ['id' => $id]
        );
    }

    // Método para eliminar un desarrollo
    public function delete_desarrollo($id) {
        $tabla_desarrollos = 'desarrollos';
        $this->wpdb->delete($tabla_desarrollos, ['id' => $id]);
    }

    // Shortcode para mostrar el formulario de desarrollos
    public function desarrollos_formulario_shortcode() {
        ob_start();

        // Si el formulario es enviado, procesar la información
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['nombre'], $_POST['numero_lotes'], $_POST['lotes_disponibles'], $_POST['lotes_utilizados'], $_POST['medidas'], $_POST['accion'])) {
            $nombre = sanitize_text_field($_POST['nombre']);
            $numero_lotes = intval($_POST['numero_lotes']);
            $lotes_disponibles = intval($_POST['lotes_disponibles']);
            $lotes_utilizados = intval($_POST['lotes_utilizados']);
            $medidas = sanitize_text_field($_POST['medidas']);
            
            if ($_POST['accion'] === 'add') {
                // Insertar el nuevo desarrollo
                $this->add_desarrollo($nombre, $numero_lotes, $lotes_disponibles, $lotes_utilizados, $medidas);
                echo "<p>Desarrollo añadido correctamente.</p>";
            }

            // Si el formulario es de edición
            if ($_POST['accion'] === 'edit' && isset($_POST['desarrollo_id'])) {
                $desarrollo_id = intval($_POST['desarrollo_id']);
                $this->update_desarrollo($desarrollo_id, $nombre, $numero_lotes, $lotes_disponibles, $lotes_utilizados, $medidas);
                echo "<p>Desarrollo actualizado correctamente.</p>";
            }
        }

        // Mostrar formulario de desarrollo
        echo '
        <form method="POST">
            <label for="nombre">Nombre del Desarrollo:</label>
            <input type="text" name="nombre" required>
            
            <label for="numero_lotes">Número de Lotes:</label>
            <input type="number" name="numero_lotes" required>
            
            <label for="lotes_disponibles">Lotes Disponibles:</label>
            <input type="number" name="lotes_disponibles" required>
            
            <label for="lotes_utilizados">Lotes Utilizados:</label>
            <input type="number" name="lotes_utilizados" required>
            
            <label for="medidas">Medidas:</label>
            <input type="text" name="medidas" required>
            
            <input type="hidden" name="accion" value="add">
            <button type="submit">Añadir Desarrollo</button>
        </form>
        ';

        // Mostrar la lista de desarrollos
        $desarrollos = $this->get_all_desarrollos();
        if (!empty($desarrollos)) {
            echo '<h2>Listado de Desarrollos</h2>';
            echo '<ul>';
            foreach ($desarrollos as $desarrollo) {
                echo "<li>ID: {$desarrollo->id} - Nombre: {$desarrollo->nombre} - Lotes: {$desarrollo->numero_lotes} - Disponibles: {$desarrollo->lotes_disponibles} - Utilizados: {$desarrollo->lotes_utilizados} - Medidas: {$desarrollo->medidas}
                      <button class='editar-desarrollo' data-id='{$desarrollo->id}' data-nombre='{$desarrollo->nombre}' data-numero_lotes='{$desarrollo->numero_lotes}' data-lotes_disponibles='{$desarrollo->lotes_disponibles}' data-lotes_utilizados='{$desarrollo->lotes_utilizados}' data-medidas='{$desarrollo->medidas}'>Editar</button>
                      <form method='POST' style='display:inline;' onsubmit='return confirm(\"¿Seguro que deseas eliminar este desarrollo?\");'>
                          <input type='hidden' name='desarrollo_id' value='{$desarrollo->id}'>
                          <input type='hidden' name='accion' value='delete'>
                          <button type='submit' style='color:red;'>Eliminar</button>
                      </form>
                      </li>";
            }
            echo '</ul>';
        } else {
            echo "<p>No hay desarrollos registrados.</p>";
        }

        return ob_get_clean();
    }
}

// Iniciar la clase
new Desarrollos();
