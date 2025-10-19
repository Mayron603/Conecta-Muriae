<?php

namespace App\Model;

use Core\Library\ModelMain;

class TermoAceiteModel extends ModelMain
{
    protected $table = 'termodeusoaceite';

    /**
     * Registra o aceite dos termos por um usuário.
     *
     * @param int $usuarioId
     * @param array $termoIds
     * @return bool
     */
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
                // Agora esta chamada vai funcionar perfeitamente
                $this->db->table($this->table)->insert($dadosParaInserir);
            }
            return true;
        } catch (\Exception $e) {
            // Se algo ainda der errado, podemos registrar o erro futuramente.
            // error_log("Erro ao salvar aceite: " . $e->getMessage());
            return false;
        }
    }
}