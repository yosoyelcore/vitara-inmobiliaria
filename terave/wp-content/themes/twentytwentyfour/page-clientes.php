<?php
/*
Template Name: Página Clientes
*/

get_header(); // Llama al encabezado de WordPress

// Asegúrate de que la clase Clientes esté disponible
if (class_exists('Clientes')) {
    // Crear una instancia de la clase Clientes
    $clientes_class = new Clientes();
    
    // Si el formulario de añadir cliente ha sido enviado
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['nombre'], $_POST['apellido'], $_POST['telefono'], $_POST['correo'], $_POST['accion'])) {
        $nombre = sanitize_text_field($_POST['nombre']);
        $apellido = sanitize_text_field($_POST['apellido']);
        $telefono = sanitize_text_field($_POST['telefono']);
        $correo = sanitize_email($_POST['correo']);
        
        if ($_POST['accion'] === 'add') {
            // Insertar el nuevo cliente si no existe un duplicado
            if ($clientes_class->add_cliente($nombre, $apellido, $telefono, $correo)) {
                echo "<p>Cliente añadido correctamente.</p>";
            } else {
                echo "<p style='color:red;'>El cliente ya existe o el correo está en uso.</p>";
            }
        }
    }

    // Si el formulario de edición ha sido enviado
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cliente_id'], $_POST['nombre_edit'], $_POST['apellido_edit'], $_POST['telefono_edit'], $_POST['correo_edit'])) {
        $cliente_id = intval($_POST['cliente_id']);
        $nombre_edit = sanitize_text_field($_POST['nombre_edit']);
        $apellido_edit = sanitize_text_field($_POST['apellido_edit']);
        $telefono_edit = sanitize_text_field($_POST['telefono_edit']);
        $correo_edit = sanitize_email($_POST['correo_edit']);
        
        // Actualizar el cliente
        $clientes_class->update_cliente($cliente_id, $nombre_edit, $apellido_edit, $telefono_edit, $correo_edit);
        echo "<p>Cliente actualizado correctamente.</p>";
    }

    // Si el formulario de eliminar ha sido enviado
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion']) && $_POST['accion'] === 'delete') {
        $cliente_id = intval($_POST['cliente_id']);
        $clientes_class->delete_cliente($cliente_id);
        echo "<p>Cliente eliminado correctamente.</p>";
    }

    // Mostrar el botón para agregar un nuevo cliente
    echo '<button id="mostrar-formulario">Añadir Cliente</button>';

    // Formulario de añadir cliente (oculto por defecto)
    echo '
    <div id="formulario-cliente" style="display:none;">
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
    </div>
    ';

    // Mostrar la lista de clientes con opciones de editar y eliminar
    $clientes = $clientes_class->get_all_clientes();
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

    // Formulario de edición de cliente (oculto por defecto)
    echo '
    <div id="formulario-editar" style="display:none;">
        <h3>Editar Cliente</h3>
        <form method="POST">
            <input type="hidden" name="cliente_id" id="cliente_id">
            
            <label for="nombre_edit">Nombre:</label>
            <input type="text" name="nombre_edit" id="nombre_edit" required>
            
            <label for="apellido_edit">Apellido:</label>
            <input type="text" name="apellido_edit" id="apellido_edit" required>
            
            <label for="telefono_edit">Teléfono:</label>
            <input type="text" name="telefono_edit" id="telefono_edit" required>
            
            <label for="correo_edit">Correo:</label>
            <input type="email" name="correo_edit" id="correo_edit" required>
            
            <button type="submit">Guardar cambios</button>
        </form>
    </div>
    ';
}

get_footer(); // Llama al pie de página de WordPress
?>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const botonAgregar = document.getElementById('mostrar-formulario');
        const formularioAgregar = document.getElementById('formulario-cliente');
        const formularioEditar = document.getElementById('formulario-editar');
        
        // Mostrar/ocultar el formulario de agregar cliente
        botonAgregar.addEventListener('click', function() {
            if (formularioAgregar.style.display === 'none') {
                formularioAgregar.style.display = 'block';
            } else {
                formularioAgregar.style.display = 'none';
            }
        });

        // Mostrar el formulario de edición con los datos del cliente
        const botonesEditar = document.querySelectorAll('.editar-cliente');
        botonesEditar.forEach(function(boton) {
            boton.addEventListener('click', function() {
                const id = boton.getAttribute('data-id');
                const nombre = boton.getAttribute('data-nombre');
                const apellido = boton.getAttribute('data-apellido');
                const telefono = boton.getAttribute('data-telefono');
                const correo = boton.getAttribute('data-correo');
                
                // Llenar los campos del formulario de edición
                document.getElementById('cliente_id').value = id;
                document.getElementById('nombre_edit').value = nombre;
                document.getElementById('apellido_edit').value = apellido;
                document.getElementById('telefono_edit').value = telefono;
                document.getElementById('correo_edit').value = correo;
                
                // Mostrar el formulario de edición
                formularioEditar.style.display = 'block';
            });
        });
    });
</script>
