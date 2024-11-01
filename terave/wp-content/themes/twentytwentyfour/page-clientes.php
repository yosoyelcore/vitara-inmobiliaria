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
                echo "<p class='success-message'>Cliente añadido correctamente.</p>";
            } else {
                echo "<p class='error-message'>El cliente ya existe o el correo está en uso.</p>";
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
        echo "<p class='success-message'>Cliente actualizado correctamente.</p>";
    }

    // Si el formulario de eliminar ha sido enviado
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion']) && $_POST['accion'] === 'delete') {
        $cliente_id = intval($_POST['cliente_id']);
        $clientes_class->delete_cliente($cliente_id);
        echo "<p class='success-message'>Cliente eliminado correctamente.</p>";
    }

    // Mostrar el botón para agregar un nuevo cliente
    echo '<button id="mostrar-formulario" class="btn btn-primary">Añadir Cliente</button>';

    // Formulario de añadir cliente (oculto por defecto)
    echo '
    <div id="formulario-cliente" style="display:none;" class="form-container">
        <form method="POST">
            <div>
                <label for="nombre">Nombre:</label>
                <input type="text" name="nombre" required>
            </div>
            
            <div>
                <label for="apellido">Apellido:</label>
                <input type="text" name="apellido" required>
            </div>
            
            <div>
                <label for="telefono">Teléfono:</label>
                <input type="text" name="telefono" required>
            </div>
            
            <div>
                <label for="correo">Correo:</label>
                <input type="email" name="correo" required>
            </div>
            
            <input type="hidden" name="accion" value="add">
            <button type="submit" class="btn btn-primary">Añadir Cliente</button>
        </form>
    </div>
    ';

    // Mostrar la barra de búsqueda
    echo '
    <div class="search-container">
        <input type="text" id="buscar" placeholder="Buscar cliente...">
    </div>
    ';

    // Mostrar la lista de clientes en formato de tabla
    $clientes = $clientes_class->get_all_clientes();
    if (!empty($clientes)) {
        echo '<h2 class="section-title">Listado de Clientes</h2>';
        echo '<table id="clientes-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Teléfono</th>
                        <th>Correo</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>';
        foreach ($clientes as $cliente) {
            echo "<tr>
                    <td>{$cliente->id}</td>
                    <td>{$cliente->nombre} {$cliente->apellido}</td>
                    <td>{$cliente->telefono}</td>
                    <td>{$cliente->correo}</td>
                    <td>
                        <button class='editar-cliente btn btn-secondary' 
                        data-id='{$cliente->id}' data-nombre='{$cliente->nombre}' data-apellido='{$cliente->apellido}' data-telefono='{$cliente->telefono}' data-correo='{$cliente->correo}'>Editar</button>
                        <form method='POST' style='display:inline;' onsubmit='return confirm(\"¿Seguro que deseas eliminar a este cliente?\");'>
                            <input type='hidden' name='cliente_id' value='{$cliente->id}'>
                            <input type='hidden' name='accion' value='delete'>
                            <button type='submit' class='btn btn-danger'>Eliminar</button>
                        </form>
                    </td>
                  </tr>";
        }
        echo '</tbody></table>';
    } else {
        echo "<p>No hay clientes registrados.</p>";
    }

    // Formulario de edición de cliente (oculto por defecto)
    echo '
    <div id="formulario-editar" style="display:none;" class="form-container">
        <h3>Editar Cliente</h3>
        <form method="POST">
            <input type="hidden" name="cliente_id" id="cliente_id">
            
            <div>
                <label for="nombre_edit">Nombre:</label>
                <input type="text" name="nombre_edit" id="nombre_edit" required>
            </div>
            
            <div>
                <label for="apellido_edit">Apellido:</label>
                <input type="text" name="apellido_edit" id="apellido_edit" required>
            </div>
            
            <div>
                <label for="telefono_edit">Teléfono:</label>
                <input type="text" name="telefono_edit" id="telefono_edit" required>
            </div>
            
            <div>
                <label for="correo_edit">Correo:</label>
                <input type="email" name="correo_edit" id="correo_edit" required>
            </div>
            
            <button type="submit" class="btn btn-primary">Guardar cambios</button>
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
            formularioAgregar.style.display = formularioAgregar.style.display === 'none' ? 'block' : 'none';
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

        // Filtro de búsqueda
        const inputBusqueda = document.getElementById('buscar');
        const tablaClientes = document.getElementById('clientes-table');
        inputBusqueda.addEventListener('keyup', function() {
            const filter = inputBusqueda.value.toLowerCase();
            const rows = tablaClientes.getElementsByTagName('tr');
            
            Array.from(rows).forEach(function(row, index) {
                if (index === 0) return; // Saltar la cabecera
                
                const nombre = row.cells[1].textContent.toLowerCase();
                if (nombre.indexOf(filter) > -1) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    });
</script>

<style>
    .btn {
        padding: 10px 20px;
        border-radius: 5px;
        cursor: pointer;
    }

    .btn-primary {
        background-color: #4a5568;
        color: white;
        border: none;
        transition: background-color 0.3s;
    }

    .btn-primary:hover {
        background-color: #2d3748;
    }

    .btn-secondary {
        background-color: #68d391;
        color: white;
        border: none;
        transition: background-color 0.3s;
    }

    .btn-secondary:hover {
        background-color: #48bb78;
    }

    .btn-danger {
        background-color: #e53e3e;
        color: white;
        border: none;
        transition: background-color 0.3s;
    }

    .btn-danger:hover {
        background-color: #c53030;
    }

    .form-container {
        background-color: #edf2f7;
        padding: 20px;
        margin-top: 20px;
        border-radius: 5px;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
    }

    table thead {
        background-color: #4a5568;
        color: white;
    }

    table th, table td {
        padding: 10px;
        border: 1px solid #ddd;
        text-align: left;
    }

    table tbody tr:nth-child(even) {
        background-color: #f7fafc;
    }

    .search-container {
        margin-top: 20px;
    }

    .search-container input {
        padding: 10px;
        width: 100%;
        border: 1px solid #ccc;
        border-radius: 5px;
    }

    .success-message {
        color: green;
        margin-top: 20px;
    }

    .error-message {
        color: red;
        margin-top: 20px;
    }

    .section-title {
        font-size: 24px;
        font-weight: bold;
        color: #4a5568;
        margin-top: 20px;
    }
/*     #header {
       display: none;
    }
    #footer {
       display: none;
    } */

     body {
        background-color: #f7fafc;
        max-width: 75%;
        display: block;
        margin-left: auto;
        margin-right: auto;
        margin-top: 10%;

    }
    hr {
        display: none;
    }
</style>
