<?php

namespace App\Model;

use Core\Library\ModelMain;

class TermoUsoModel extends ModelMain
{
    protected $table = 'termodeuso';
    protected $primaryKey = 'id';

    public function getTermosAtivosIds(): array
    {
        $resultados = $this->db->table($this->table)
                             ->where('statusRegistro', 1)
                             ->findAll();

        if (empty($resultados)) {
            return [];
        }

        return array_column($resultados, 'id');
    }
}
