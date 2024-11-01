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
                echo "<p class='error-message'>Este vendedor ya existe.</p>";
            } else {
                // Insertar el nuevo vendedor si no es duplicado
                $vendedores_class->add_vendedor($nombre, $apellido, $telefono);
                echo "<p class='success-message'>Vendedor añadido correctamente.</p>";
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
        echo "<p class='success-message'>Vendedor actualizado correctamente.</p>";
    }

    // Si el formulario de eliminar ha sido enviado
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion']) && $_POST['accion'] === 'delete') {
        $vendedor_id = intval($_POST['vendedor_id']);
        $vendedores_class->delete_vendedor($vendedor_id);
        echo "<p class='success-message'>Vendedor eliminado correctamente.</p>";
    }

    // Mostrar el botón para agregar un nuevo vendedor
    echo '<button id="mostrar-formulario" class="btn btn-primary">Añadir Vendedor</button>';

    // Formulario de añadir vendedor (oculto por defecto)
    echo '
    <div id="formulario-vendedor" style="display:none;" class="form-container">
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
            
            <input type="hidden" name="accion" value="add">
            <button type="submit" class="btn btn-primary">Añadir Vendedor</button>
        </form>
    </div>
    ';

    // Mostrar la barra de búsqueda
    echo '
    <div class="search-container">
        <input type="text" id="buscar" placeholder="Buscar vendedor...">
    </div>
    ';

    // Mostrar la lista de vendedores en formato de tabla
    $vendedores = $vendedores_class->get_all_vendedores();
    if (!empty($vendedores)) {
        echo '<h2 class="section-title">Listado de Vendedores</h2>';
        echo '<table id="vendedores-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Teléfono</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>';
        foreach ($vendedores as $vendedor) {
            echo "<tr>
                    <td>{$vendedor->id}</td>
                    <td>{$vendedor->nombre} {$vendedor->apellido}</td>
                    <td>{$vendedor->telefono}</td>
                    <td>
                        <button class='editar-vendedor btn btn-secondary' 
                        data-id='{$vendedor->id}' data-nombre='{$vendedor->nombre}' data-apellido='{$vendedor->apellido}' data-telefono='{$vendedor->telefono}'>Editar</button>
                        <form method='POST' style='display:inline;' onsubmit='return confirm(\"¿Seguro que deseas eliminar a este vendedor?\");'>
                            <input type='hidden' name='vendedor_id' value='{$vendedor->id}'>
                            <input type='hidden' name='accion' value='delete'>
                            <button type='submit' class='btn btn-danger'>Eliminar</button>
                        </form>
                    </td>
                  </tr>";
        }
        echo '</tbody></table>';
    } else {
        echo "<p>No hay vendedores registrados.</p>";
    }

    // Formulario de edición de vendedor (oculto por defecto)
    echo '
    <div id="formulario-editar" style="display:none;" class="form-container">
        <h3>Editar Vendedor</h3>
        <form method="POST">
            <input type="hidden" name="vendedor_id" id="vendedor_id">
            
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
        const formularioAgregar = document.getElementById('formulario-vendedor');
        const formularioEditar = document.getElementById('formulario-editar');
        
        // Mostrar/ocultar el formulario de agregar vendedor
        botonAgregar.addEventListener('click', function() {
            formularioAgregar.style.display = formularioAgregar.style.display === 'none' ? 'block' : 'none';
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

        // Filtro de búsqueda
        const inputBusqueda = document.getElementById('buscar');
        const tablaVendedores = document.getElementById('vendedores-table');
        inputBusqueda.addEventListener('keyup', function() {
            const filter = inputBusqueda.value.toLowerCase();
            const rows = tablaVendedores.getElementsByTagName('tr');
            
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
