<?php

namespace App\Model;

use Core\Library\ModelMain;

class TelefoneModel extends ModelMain
{
    protected $table = "telefone";
    protected $primaryKey = "telefone_id";

    public function getTelefonePrincipal(int $usuarioId)
    {
        return $this->db->where('usuario_id', $usuarioId)
                       ->first();
    }

    public function salvarTelefonePrincipal(int $usuarioId, string $numero): bool
    {
        $numeroLimpo = preg_replace('/\\D/', '', $numero);

        if (empty($numeroLimpo)) {
            return true;
        }

        try {
            $telefoneExistente = $this->getTelefonePrincipal($usuarioId);

            if ($telefoneExistente) {
                $this->db->where($this->primaryKey, $telefoneExistente[$this->primaryKey])
                         ->update(['numero' => $numeroLimpo]);
            } else {
                $this->db->insert([
                    'usuario_id' => $usuarioId,
                    'numero' => $numeroLimpo
                ]);
            }
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
}