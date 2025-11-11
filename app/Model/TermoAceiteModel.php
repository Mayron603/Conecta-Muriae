<?php

namespace App\Model;

use Core\Library\ModelMain;

class TermoAceiteModel extends ModelMain
{
    protected $table = 'termodeusoaceite';

    public function registrarAceite(int $usuarioId, array $termoIds): bool
    {
        if (empty($termoIds)) {
            return true;
        }

        $dataHora = date('Y-m-d H:i:s');

        try {
            foreach ($termoIds as $termoId) {
                $dadosParaInserir = [
                    'usuario_id' => $usuarioId,
                    'termodeuso_id' => $termoId,
                    'dataHoraAceite' => $dataHora
                ];
                $this->db->table($this->table)->insert($dadosParaInserir);
            }
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
}