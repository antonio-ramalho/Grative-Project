<?php
// src/Controllers/OscController.php

class OscController {
    
    // Método responsável por mostrar a tela de cadastro
    public function mostrarFormulario() {
        // Aqui dentro chamamos a view!
        require_once __DIR__ . '/../views/cadastro_osc.html';
    }

}