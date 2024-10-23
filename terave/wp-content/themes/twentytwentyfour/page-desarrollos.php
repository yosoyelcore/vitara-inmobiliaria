<?php
/*
Template Name: Página Desarrollos
*/

get_header(); // Llama al encabezado de WordPress

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
            echo "<p>Desarrollo añadido correctamente.</p>";
        }

        // Si el formulario de edición ha sido enviado
        if ($_POST['accion'] === 'edit' && isset($_POST['desarrollo_id'])) {
            $desarrollo_id = intval($_POST['desarrollo_id']);
            $desarrollos_class->update_desarrollo($desarrollo_id, $nombre, $numero_lotes, $lotes_disponibles, $lotes_utilizados, $medidas);
            echo "<p>Desarrollo actualizado correctamente.</p>";
        }
    }

    // Si el formulario de eliminar ha sido enviado
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion']) && $_POST['accion'] === 'delete') {
        $desarrollo_id = intval($_POST['desarrollo_id']);
        $desarrollos_class->delete_desarrollo($desarrollo_id);
        echo "<p>Desarrollo eliminado correctamente.</p>";
    }

    // Mostrar el formulario para añadir desarrollos
    echo do_shortcode('[desarrollos_formulario]');
}

get_footer(); // Llama al pie de página de WordPress
?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const botonesEditar = document.querySelectorAll('.editar-desarrollo');
        const formularioEditar = document.getElementById('formulario-editar');

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
    });
</script>
