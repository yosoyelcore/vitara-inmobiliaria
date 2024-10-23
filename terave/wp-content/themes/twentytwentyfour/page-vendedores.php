<?php
/*
Template Name: Página Vendedores
*/

get_header(); // Llama al encabezado de WordPress

// Asegúrate de que la clase Vendedores esté disponible
if (class_exists('Vendedores')) {
    // Crear una instancia de la clase Vendedores
    $vendedores_class = new Vendedores();
    
    // Si el formulario de añadir vendedor ha sido enviado
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['nombre'], $_POST['apellido'], $_POST['telefono'], $_POST['accion'])) {
        $nombre = sanitize_text_field($_POST['nombre']);
        $apellido = sanitize_text_field($_POST['apellido']);
        $telefono = sanitize_text_field($_POST['telefono']);
        
        if ($_POST['accion'] === 'add') {
            // Verificar si el vendedor ya existe antes de agregarlo
            $vendedores_existentes = $vendedores_class->get_all_vendedores();
            $vendedor_duplicado = false;
            foreach ($vendedores_existentes as $vendedor) {
                if ($vendedor->nombre === $nombre && $vendedor->apellido === $apellido && $vendedor->telefono === $telefono) {
                    $vendedor_duplicado = true;
                    break;
                }
            }

            if ($vendedor_duplicado) {
                echo "<p style='color:red;'>Este vendedor ya existe.</p>";
            } else {
                // Insertar el nuevo vendedor si no es duplicado
                $vendedores_class->add_vendedor($nombre, $apellido, $telefono);
                echo "<p>Vendedor añadido correctamente.</p>";
            }
        }
    }

    // Si el formulario de edición ha sido enviado
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['vendedor_id'], $_POST['nombre_edit'], $_POST['apellido_edit'], $_POST['telefono_edit'])) {
        $vendedor_id = intval($_POST['vendedor_id']);
        $nombre_edit = sanitize_text_field($_POST['nombre_edit']);
        $apellido_edit = sanitize_text_field($_POST['apellido_edit']);
        $telefono_edit = sanitize_text_field($_POST['telefono_edit']);
        
        // Actualizar el vendedor
        $vendedores_class->update_vendedor($vendedor_id, $nombre_edit, $apellido_edit, $telefono_edit);
        echo "<p>Vendedor actualizado correctamente.</p>";
    }

    // Si el formulario de eliminar ha sido enviado
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion']) && $_POST['accion'] === 'delete') {
        $vendedor_id = intval($_POST['vendedor_id']);
        $vendedores_class->delete_vendedor($vendedor_id);
        echo "<p>Vendedor eliminado correctamente.</p>";
    }

    // Mostrar el botón para agregar un nuevo vendedor
    echo '<button id="mostrar-formulario">Añadir Vendedor</button>';

    // Formulario de añadir vendedor (oculto por defecto)
    echo '
    <div id="formulario-vendedor" style="display:none;">
        <form method="POST">
            <label for="nombre">Nombre:</label>
            <input type="text" name="nombre" required>
            
            <label for="apellido">Apellido:</label>
            <input type="text" name="apellido" required>
            
            <label for="telefono">Teléfono:</label>
            <input type="text" name="telefono" required>
            
            <input type="hidden" name="accion" value="add">
            <button type="submit">Añadir Vendedor</button>
        </form>
    </div>
    ';

    // Mostrar la lista de vendedores con opciones de editar y eliminar
    $vendedores = $vendedores_class->get_all_vendedores();
    if (!empty($vendedores)) {
        echo '<h2>Listado de Vendedores</h2>';
        echo '<ul>';
        foreach ($vendedores as $vendedor) {
            echo "<li>ID: {$vendedor->id} - Nombre: {$vendedor->nombre} {$vendedor->apellido} - Teléfono: {$vendedor->telefono} 
                  <button class='editar-vendedor' data-id='{$vendedor->id}' data-nombre='{$vendedor->nombre}' data-apellido='{$vendedor->apellido}' data-telefono='{$vendedor->telefono}'>Editar</button>
                  <form method='POST' style='display:inline;' onsubmit='return confirm(\"¿Seguro que deseas eliminar a este vendedor?\");'>
                      <input type='hidden' name='vendedor_id' value='{$vendedor->id}'>
                      <input type='hidden' name='accion' value='delete'>
                      <button type='submit' style='background-color:red; color:white;'>Eliminar</button>
                  </form>
                  </li>";
        }
        echo '</ul>';
    } else {
        echo "<p>No hay vendedores registrados.</p>";
    }

    // Formulario de edición de vendedor (oculto por defecto)
    echo '
    <div id="formulario-editar" style="display:none;">
        <h3>Editar Vendedor</h3>
        <form method="POST">
            <input type="hidden" name="vendedor_id" id="vendedor_id">
            
            <label for="nombre_edit">Nombre:</label>
            <input type="text" name="nombre_edit" id="nombre_edit" required>
            
            <label for="apellido_edit">Apellido:</label>
            <input type="text" name="apellido_edit" id="apellido_edit" required>
            
            <label for="telefono_edit">Teléfono:</label>
            <input type="text" name="telefono_edit" id="telefono_edit" required>
            
            <button type="submit">Guardar cambios</button>
        </form>
    </div>
    ';
}

get_footer(); // Llama al pie de página de WordPress
?>



<script>
    document.addEventListener('DOMContentLoaded', function() {
        const boton = document.getElementById('mostrar-formulario');
        const formulario = document.getElementById('formulario-vendedor');
        
        boton.addEventListener('click', function() {
            if (formulario.style.display === 'none') {
                formulario.style.display = 'block';
            } else {
                formulario.style.display = 'none';
            }
        });
    });
</script>


<script>
    document.addEventListener('DOMContentLoaded', function() {
        const botonAgregar = document.getElementById('mostrar-formulario');
        const formularioAgregar = document.getElementById('formulario-vendedor');
        const formularioEditar = document.getElementById('formulario-editar');
        
        // Mostrar/ocultar el formulario de agregar vendedor
        botonAgregar.addEventListener('click', function() {
            if (formularioAgregar.style.display === 'none') {
                formularioAgregar.style.display = 'block';
            } else {
                formularioAgregar.style.display = 'none';
            }
        });

        // Mostrar el formulario de edición con los datos del vendedor
        const botonesEditar = document.querySelectorAll('.editar-vendedor');
        botonesEditar.forEach(function(boton) {
            boton.addEventListener('click', function() {
                const id = boton.getAttribute('data-id');
                const nombre = boton.getAttribute('data-nombre');
                const apellido = boton.getAttribute('data-apellido');
                const telefono = boton.getAttribute('data-telefono');
                
                // Llenar los campos del formulario de edición
                document.getElementById('vendedor_id').value = id;
                document.getElementById('nombre_edit').value = nombre;
                document.getElementById('apellido_edit').value = apellido;
                document.getElementById('telefono_edit').value = telefono;
                
                // Mostrar el formulario de edición
                formularioEditar.style.display = 'block';
            });
        });
    });
</script>


