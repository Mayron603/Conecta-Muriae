<?php

namespace App\Model;

use Core\Library\ModelMain;

class TermoUsoModel extends ModelMain
{
    protected $table = 'termodeuso';
    protected $primaryKey = 'id';

    /**
     * Busca os IDs de todos os termos de uso que estão ativos no sistema.
     *
     * @return array Retorna um array contendo os IDs dos termos ativos.
     */
    public function getTermosAtivosIds(): array
    {
        // A condição para um termo ser considerado ativo é statusRegistro = 1
        $resultados = $this->db->table($this->table)
                             ->where('statusRegistro', 1)
                             ->findAll();

        if (empty($resultados)) {
            return [];
        }

        // Utiliza array_column para extrair de forma segura e eficiente os IDs
        return array_column($resultados, 'id');
    }
}
