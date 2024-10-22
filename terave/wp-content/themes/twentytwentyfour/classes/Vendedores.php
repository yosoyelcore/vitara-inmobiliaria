<?php
// classes/Vendedores.php

class Vendedores {
    // Constructor
    public function __construct() {
        // Hook para ejecutar acciones en WordPress
        add_action('init', [$this, 'init_vendedores']);
    }

    // Método que se ejecuta al inicializar
    public function init_vendedores() {
        // Aquí puedes inicializar algo relacionado con la clase Vendedores
    }

    // Otros métodos relacionados con vendedores
    public function listar_vendedores() {
        // Aquí podrías tener la lógica para listar los vendedores
    }
}

// Iniciar la clase
new Vendedores();
