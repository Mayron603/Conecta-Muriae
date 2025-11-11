<?php

if (!function_exists('validaCPF')) {
    /**
     * @param string 
     * @return bool 
     */
    function validaCPF($cpf) {
        // Remove caracteres não numéricos
        $cpf = preg_replace( '/[^0-9]/', '', $cpf);
        
        // Verifica se tem 11 dígitos
        if (strlen($cpf) != 11) {
            return false;
        }
        
        // Verifica se todos os dígitos são iguaiss
        if (preg_match('/(\d)\1{10}/', $cpf)) {
            return false;
        }

        // Calcula
        for ($t = 9; $t < 11; $t++) {
            for ($d = 0, $c = 0; $c < $t; $c++) {
                $d += $cpf[$c] * (($t + 1) - $c);
            }
            $d = ((10 * $d) % 11) % 10;
            if ($cpf[$c] != $d) {
                return false;
            }
        }
        return true;
    }
}
