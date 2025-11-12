<?php

namespace App\Model;

use Core\Library\ModelMain;

class MensagemModel extends ModelMain
{
    protected $table = "mensagem";
    protected $primaryKey = "id";

    public function findMensagensByConversa(int $id_conversa): array
    {
        if ($id_conversa <= 0) {
            return [];
        }

        return $this->db->table($this->table)
            ->where('id_conversa', $id_conversa)
            ->orderBy('data_envio', 'ASC')
            ->findAll();
    }
}

