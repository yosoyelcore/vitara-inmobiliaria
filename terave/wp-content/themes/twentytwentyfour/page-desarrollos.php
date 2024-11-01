<?php
/*
Template Name: Página Desarrollos
*/

get_header(); // Llama al encabezado de WordPress
get_template_part('custom-menu');
// Asegúrate de que la clase Desarrollos esté disponible
if (class_exists('Desarrollos')) {
    // Crear una instancia de la clase Desarrollos
    $desarrollos_class = new Desarrollos();
    
    // Si el formulario de añadir desarrollo ha sido enviado
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['nombre'], $_POST['numero_lotes'], $_POST['lotes_disponibles'], $_POST['lotes_utilizados'], $_POST['medidas'], $_POST['accion'])) {
        $nombre = sanitize_text_field($_POST['nombre']);
        $numero_lotes = intval($_POST['numero_lotes']);
        $lotes_disponibles = intval($_POST['lotes_disponibles']);
        $lotes_utilizados = intval($_POST['lotes_utilizados']);
        $medidas = sanitize_text_field($_POST['medidas']);
        
        if ($_POST['accion'] === 'add') {
            // Insertar el nuevo desarrollo
            $desarrollos_class->add_desarrollo($nombre, $numero_lotes, $lotes_disponibles, $lotes_utilizados, $medidas);
            echo "<p class='success-message'>Desarrollo añadido correctamente.</p>";
        }

        // Si el formulario de edición ha sido enviado
        if ($_POST['accion'] === 'edit' && isset($_POST['desarrollo_id'])) {
            $desarrollo_id = intval($_POST['desarrollo_id']);
            $desarrollos_class->update_desarrollo($desarrollo_id, $nombre, $numero_lotes, $lotes_disponibles, $lotes_utilizados, $medidas);
            echo "<p class='success-message'>Desarrollo actualizado correctamente.</p>";
        }
    }

    // Si el formulario de eliminar ha sido enviado
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion']) && $_POST['accion'] === 'delete') {
        $desarrollo_id = intval($_POST['desarrollo_id']);
        $desarrollos_class->delete_desarrollo($desarrollo_id);
        echo "<p class='success-message'>Desarrollo eliminado correctamente.</p>";
    }

    // Mostrar el botón para agregar un nuevo desarrollo
    echo '<button id="mostrar-formulario" class="btn btn-primary">Añadir Desarrollo</button>';

    // Formulario de añadir desarrollo (oculto por defecto)
    echo '
    <div id="formulario-desarrollo" style="display:none;" class="form-container">
        <form method="POST">
            <div>
                <label for="nombre">Nombre:</label>
                <input type="text" name="nombre" required>
            </div>
            
            <div>
                <label for="numero_lotes">Número de Lotes:</label>
                <input type="number" name="numero_lotes" required>
            </div>
            
            <div>
                <label for="lotes_disponibles">Lotes Disponibles:</label>
                <input type="number" name="lotes_disponibles" required>
            </div>
            
            <div>
                <label for="lotes_utilizados">Lotes Utilizados:</label>
                <input type="number" name="lotes_utilizados" required>
            </div>
            
            <div>
                <label for="medidas">Medidas:</label>
                <input type="text" name="medidas" required>
            </div>
            
            <input type="hidden" name="accion" value="add">
            <button type="submit" class="btn btn-primary">Añadir Desarrollo</button>
        </form>
    </div>
    ';

    // Mostrar la barra de búsqueda
    echo '
    <div class="search-container">
        <input type="text" id="buscar" placeholder="Buscar desarrollo...">
    </div>
    ';

    // Mostrar la lista de desarrollos en formato de tabla
    $desarrollos = $desarrollos_class->get_all_desarrollos();
    if (!empty($desarrollos)) {
        echo '<h2 class="section-title">Listado de Desarrollos</h2>';
        echo '<table id="desarrollos-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Número de Lotes</th>
                        <th>Lotes Disponibles</th>
                        <th>Lotes Utilizados</th>
                        <th>Medidas</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>';
        foreach ($desarrollos as $desarrollo) {
            echo "<tr>
                    <td>{$desarrollo->id}</td>
                    <td>{$desarrollo->nombre}</td>
                    <td>{$desarrollo->numero_lotes}</td>
                    <td>{$desarrollo->lotes_disponibles}</td>
                    <td>{$desarrollo->lotes_utilizados}</td>
                    <td>{$desarrollo->medidas}</td>
                    <td>
                        <button class='editar-desarrollo btn btn-secondary' 
                        data-id='{$desarrollo->id}' data-nombre='{$desarrollo->nombre}' data-numero_lotes='{$desarrollo->numero_lotes}' data-lotes_disponibles='{$desarrollo->lotes_disponibles}' data-lotes_utilizados='{$desarrollo->lotes_utilizados}' data-medidas='{$desarrollo->medidas}'>Editar</button>
                        <form method='POST' style='display:inline;' onsubmit='return confirm(\"¿Seguro que deseas eliminar este desarrollo?\");'>
                            <input type='hidden' name='desarrollo_id' value='{$desarrollo->id}'>
                            <input type='hidden' name='accion' value='delete'>
                            <button type='submit' class='btn btn-danger'>Eliminar</button>
                        </form>
                    </td>
                  </tr>";
        }
        echo '</tbody></table>';
    } else {
        echo "<p>No hay desarrollos registrados.</p>";
    }

    // Formulario de edición de desarrollo (oculto por defecto)
    echo '
    <div id="formulario-editar" style="display:none;" class="form-container">
        <h3>Editar Desarrollo</h3>
        <form method="POST">
            <input type="hidden" name="desarrollo_id" id="desarrollo_id">
            
            <div>
                <label for="nombre_edit">Nombre:</label>
                <input type="text" name="nombre_edit" id="nombre_edit" required>
            </div>
            
            <div>
                <label for="numero_lotes_edit">Número de Lotes:</label>
                <input type="number" name="numero_lotes_edit" id="numero_lotes_edit" required>
            </div>
            
            <div>
                <label for="lotes_disponibles_edit">Lotes Disponibles:</label>
                <input type="number" name="lotes_disponibles_edit" id="lotes_disponibles_edit" required>
            </div>
            
            <div>
                <label for="lotes_utilizados_edit">Lotes Utilizados:</label>
                <input type="number" name="lotes_utilizados_edit" id="lotes_utilizados_edit" required>
            </div>
            
            <div>
                <label for="medidas_edit">Medidas:</label>
                <input type="text" name="medidas_edit" id="medidas_edit" required>
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
        const formularioAgregar = document.getElementById('formulario-desarrollo');
        const formularioEditar = document.getElementById('formulario-editar');
        
        // Mostrar/ocultar el formulario de agregar desarrollo
        botonAgregar.addEventListener('click', function() {
            formularioAgregar.style.display = formularioAgregar.style.display === 'none' ? 'block' : 'none';
        });

        // Mostrar el formulario de edición con los datos del desarrollo
        const botonesEditar = document.querySelectorAll('.editar-desarrollo');
        botonesEditar.forEach(function(boton) {
            boton.addEventListener('click', function() {
                const id = boton.getAttribute('data-id');
                const nombre = boton.getAttribute('data-nombre');
                const numeroLotes = boton.getAttribute('data-numero_lotes');
                const lotesDisponibles = boton.getAttribute('data-lotes_disponibles');
                const lotesUtilizados = boton.getAttribute('data-lotes_utilizados');
                const medidas = boton.getAttribute('data-medidas');
                
                // Llenar los campos del formulario de edición
                document.getElementById('desarrollo_id').value = id;
                document.getElementById('nombre_edit').value = nombre;
                document.getElementById('numero_lotes_edit').value = numeroLotes;
                document.getElementById('lotes_disponibles_edit').value = lotesDisponibles;
                document.getElementById('lotes_utilizados_edit').value = lotesUtilizados;
                document.getElementById('medidas_edit').value = medidas;
                
                // Mostrar el formulario de edición
                formularioEditar.style.display = 'block';
            });
        });

        // Filtro de búsqueda
        const inputBusqueda = document.getElementById('buscar');
        const tablaDesarrollos = document.getElementById('desarrollos-table');
        inputBusqueda.addEventListener('keyup', function() {
            const filter = inputBusqueda.value.toLowerCase();
            const rows = tablaDesarrollos.getElementsByTagName('tr');
            
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
