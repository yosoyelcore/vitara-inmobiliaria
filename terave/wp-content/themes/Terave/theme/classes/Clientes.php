<?php
// classes/Clientes.php

class Clientes {
    private $wpdb;

    public function __construct() {
        global $wpdb;
        $this->wpdb = $wpdb;

        add_action('init', [$this, 'init_clientes']);
        add_shortcode('clientes_formulario', [$this, 'clientes_formulario_shortcode']);
    }

    public function init_clientes() {
        // Aquí podrías inicializar algo relacionado con los clientes si es necesario
    }

    // Método para obtener todos los clientes
    public function get_all_clientes() {
        $tabla_clientes = CLIENTS_TABLE;
        $query = "SELECT * FROM $tabla_clientes";
        return $this->wpdb->get_results($query);
    }

    // Método para agregar un nuevo cliente
    public function add_cliente($nombre, $apellido, $telefono, $correo) {
        $tabla_clientes = CLIENTS_TABLE;

        // Verificar si ya existe un cliente con el mismo correo
        $cliente_existente = $this->wpdb->get_var($this->wpdb->prepare("SELECT id FROM $tabla_clientes WHERE correo = %s", $correo));
        
        if ($cliente_existente) {
            return false; // Cliente ya existe
        } else {
            $this->wpdb->insert($tabla_clientes, [
                'nombre' => $nombre,
                'apellido' => $apellido,
                'telefono' => $telefono,
                'correo' => $correo
            ]);
            return $this->wpdb->insert_id;
        }
    }

    // Método para actualizar un cliente
    public function update_cliente($id, $nombre, $apellido, $telefono, $correo) {
        $tabla_clientes = CLIENTS_TABLE;
        $this->wpdb->update(
            $tabla_clientes,
            [
                'nombre' => $nombre,
                'apellido' => $apellido,
                'telefono' => $telefono,
                'correo' => $correo
            ],
            ['id' => $id]
        );
    }

    // Método para eliminar un cliente
    public function delete_cliente($id) {
        $tabla_clientes = CLIENTS_TABLE;
        $this->wpdb->delete($tabla_clientes, ['id' => $id]);
    }

    // Shortcode para mostrar el formulario de clientes
    public function clientes_formulario_shortcode() {
        ob_start();

        // Si el formulario es enviado, procesar la información
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['nombre'], $_POST['apellido'], $_POST['telefono'], $_POST['correo'])) {
            $nombre = sanitize_text_field($_POST['nombre']);
            $apellido = sanitize_text_field($_POST['apellido']);
            $telefono = sanitize_text_field($_POST['telefono']);
            $correo = sanitize_email($_POST['correo']);
            
            // Verificar la acción (añadir o editar)
            if (isset($_POST['accion']) && $_POST['accion'] === 'add') {
                // Insertar el nuevo cliente
                if ($this->add_cliente($nombre, $apellido, $telefono, $correo)) {
                    echo "<p>Cliente añadido correctamente.</p>";
                } else {
                    echo "<p style='color:red;'>El cliente ya existe o el correo está en uso.</p>";
                }
            }

            // Si el formulario es de edición
            if (isset($_POST['accion']) && $_POST['accion'] === 'edit' && isset($_POST['cliente_id'])) {
                $cliente_id = intval($_POST['cliente_id']);
                $this->update_cliente($cliente_id, $nombre, $apellido, $telefono, $correo);
                echo "<p>Cliente actualizado correctamente.</p>";
            }
        }

        // Mostrar formulario de cliente
        echo '
        <form method="POST">
            <label for="nombre">Nombre:</label>
            <input type="text" name="nombre" required>
            
            <label for="apellido">Apellido:</label>
            <input type="text" name="apellido" required>
            
            <label for="telefono">Teléfono:</label>
            <input type="text" name="telefono" required>
            
            <label for="correo">Correo:</label>
            <input type="email" name="correo" required>
            
            <input type="hidden" name="accion" value="add">
            <button type="submit">Añadir Cliente</button>
        </form>
        ';

        // Mostrar la lista de clientes
        $clientes = $this->get_all_clientes();
        if (!empty($clientes)) {
            echo '<h2>Listado de Clientes</h2>';
            echo '<ul>';
            foreach ($clientes as $cliente) {
                echo "<li>ID: {$cliente->id} - Nombre: {$cliente->nombre} {$cliente->apellido} - Teléfono: {$cliente->telefono} - Correo: {$cliente->correo} 
                      <button class='editar-cliente' data-id='{$cliente->id}' data-nombre='{$cliente->nombre}' data-apellido='{$cliente->apellido}' data-telefono='{$cliente->telefono}' data-correo='{$cliente->correo}'>Editar</button>
                      <form method='POST' style='display:inline;' onsubmit='return confirm(\"¿Seguro que deseas eliminar a este cliente?\");'>
                          <input type='hidden' name='cliente_id' value='{$cliente->id}'>
                          <input type='hidden' name='accion' value='delete'>
                          <button type='submit' style='color:red;'>Eliminar</button>
                      </form>
                      </li>";
            }
            echo '</ul>';
        } else {
            echo "<p>No hay clientes registrados.</p>";
        }

        return ob_get_clean();
    }
}

// Iniciar la clase
new Clientes();
