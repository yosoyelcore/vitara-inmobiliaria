<?php
/*
Template Name: Registro de Ventas
*/

get_header(); // Llama al encabezado de WordPress
get_footer(); // Llama al pie de página de WordPress
$clientes_class = new Clientes();
$desarrollos_class = new Desarrollos();
// Ocultar el header y footer
echo '<style>
    header, footer {
        display: none;
    }
</style>';

// Menú personalizado
echo '
<nav class="custom-menu" style="background-color: #333; color: #fff; padding: 15px;">
    <img src="tu-imagen-logo.png" alt="Logo" style="height: 50px;">
    <ul style="list-style: none; padding: 0; display: flex; gap: 20px;">
        <li><a href="/" style="color: #fff; text-decoration: none;">Inicio</a></li>
        <li><a href="/clientes" style="color: #fff; text-decoration: none;">Clientes</a></li>
        <li><a href="/ventas" style="color: #fff; text-decoration: none;">Ventas</a></li>
        <li><a href="/desarrollos" style="color: #fff; text-decoration: none;">Desarrollos</a></li>
    </ul>
</nav>
';

// Formulario de registro de ventas
echo '
<div class="registro-venta" style="margin: 20px; padding: 20px; background-color: #f9f9f9; border-radius: 8px;">
    <h2 style="font-size: 24px; margin-bottom: 20px;">Registrar Venta</h2>
    <form method="POST" class="space-y-4" id="form-venta">
        
        <!-- Selección de Cliente -->
        <div>
            <label for="cliente" class="block text-sm font-medium text-gray-700">Cliente:</label>
            <select name="cliente" id="cliente" required class="mt-1 block w-full p-2 border border-gray-300 rounded">
                <option value="">Seleccionar Cliente</option>';
                foreach ($clientes_class->get_all_clientes() as $cliente) {
                    echo "<option value='{$cliente->id}'>{$cliente->nombre} {$cliente->apellido}</option>";
                }
echo '
            </select>
        </div>
        
        <!-- Selección de Desarrollo -->
        <div>
            <label for="desarrollo" class="block text-sm font-medium text-gray-700">Desarrollo:</label>
            <select name="desarrollo" id="desarrollo" required class="mt-1 block w-full p-2 border border-gray-300 rounded">
                <option value="">Seleccionar Desarrollo</option>';
                foreach ($desarrollos_class->get_all_desarrollos() as $desarrollo) {
                    echo "<option value='{$desarrollo->id}'>{$desarrollo->nombre}</option>";
                }
echo '
            </select>
        </div>
        
        <!-- Selección de Lote Disponible (rellenado por AJAX) -->
        <div>
            <label for="lote" class="block text-sm font-medium text-gray-700">Lote:</label>
            <select name="lote" id="lote" required class="mt-1 block w-full p-2 border border-gray-300 rounded">
                <option value="">Seleccionar Lote Disponible</option>
            </select>
        </div>
        
        <!-- Precio del Lote -->
        <div>
            <label for="precio" class="block text-sm font-medium text-gray-700">Precio del Lote:</label>
            <input type="number" name="precio" id="precio" required class="mt-1 block w-full p-2 border border-gray-300 rounded" min="0" step="0.01">
        </div>
        
        <!-- Manzana -->
        <div>
            <label for="manzana" class="block text-sm font-medium text-gray-700">Manzana:</label>
            <input type="text" name="manzana" id="manzana" required class="mt-1 block w-full p-2 border border-gray-300 rounded">
        </div>
        
        <!-- Pago Realizado -->
        <div>
            <label for="pagado" class="block text-sm font-medium text-gray-700">Pago Realizado:</label>
            <input type="number" name="pagado" id="pagado" required class="mt-1 block w-full p-2 border border-gray-300 rounded" min="0" step="0.01">
        </div>
        
        <!-- Fecha de Pago -->
        <div>
            <label for="fecha_pago" class="block text-sm font-medium text-gray-700">Fecha de Pago:</label>
            <input type="date" name="fecha_pago" id="fecha_pago" required class="mt-1 block w-full p-2 border border-gray-300 rounded">
        </div>
        
        <!-- Plazos -->
        <div>
            <label for="plazos" class="block text-sm font-medium text-gray-700">Número de Plazos:</label>
            <input type="number" name="plazos" id="plazos" required class="mt-1 block w-full p-2 border border-gray-300 rounded" min="1">
        </div>
        
        <!-- Parcialidad -->
        <div>
            <label for="parcialidad" class="block text-sm font-medium text-gray-700">Parcialidad Actual:</label>
            <input type="number" name="parcialidad" id="parcialidad" required class="mt-1 block w-full p-2 border border-gray-300 rounded" min="0">
        </div>
        
        <button type="submit" class="bg-purple-600 text-white py-2 px-4 rounded hover:bg-green-600 transition">Registrar Venta</button>
    </form>
</div>

';
?>

<script>
    // Actualiza el valor máximo del pago realizado basado en el precio del lote
    document.getElementById('precio').addEventListener('input', function() {
        document.getElementById('pagado').max = this.value;
    });
</script>

<script>
    document.getElementById('desarrollo').addEventListener('change', function() {
    var desarrolloId = this.value;
    var loteSelect = document.getElementById('lote');

    // Limpiar la lista de lotes
    loteSelect.innerHTML = '<option value="">Cargando lotes...</option>';

    // Hacer la solicitud AJAX para obtener los lotes disponibles
    var xhr = new XMLHttpRequest();
    xhr.open('POST', '<?php echo admin_url('admin-ajax.php'); ?>', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    
    xhr.onload = function() {
        if (xhr.status === 200) {
            var lotes = JSON.parse(xhr.responseText);
            loteSelect.innerHTML = '<option value="">Seleccionar Lote Disponible</option>';
            lotes.forEach(function(lote) {
                loteSelect.innerHTML += '<option value="' + lote.id + '">Lote ' + lote.numero + ' - Manzana ' + lote.manzana + '</option>';
            });
        }
    };

    xhr.send('action=get_lotes_disponibles&desarrollo_id=' + desarrolloId);
});

</script>
