<?php
/* 
Template Name: Calculadora
*/

get_header(); // Incluye el header

?>

<div class="calculadora-container">
    <h1>Calculadora Simple</h1>
    <form method="POST">
        <label for="num1">Número 1:</label>
        <input type="number" name="num1" required>
        
        <label for="num2">Número 2:</label>
        <input type="number" name="num2" required>
        
        <button type="submit">Calcular</button>
    </form>

    <?php
    // Verifica si se han enviado los números
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Suma los números
        $num1 = isset($_POST['num1']) ? intval($_POST['num1']) : 0;
        $num2 = isset($_POST['num2']) ? intval($_POST['num2']) : 0;
        $resultado = $num1 + $num2;
        echo "<p>Resultado: $resultado</p>";
    }
    ?>
</div>

<?php get_footer(); // Incluye el footer ?>
