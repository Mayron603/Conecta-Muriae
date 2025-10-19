<?php

namespace App\Model;

use Core\Library\ModelMain;

class MensagemModel extends ModelMain
{
    protected $table = "mensagem";
    protected $primaryKey = "id";

    /**
     * Busca todas as mensagens de uma conversa, ordenadas por data.
     *
     * @param int $id_conversa O ID da conversa para buscar as mensagens.
     * @return array Uma lista de mensagens.
     */
    public function findMensagensByConversa(int $id_conversa): array
    {
        // Verifica se o ID da conversa é válido para evitar erros
        if ($id_conversa <= 0) {
            return [];
        }

        // Constrói a consulta para buscar as mensagens
        return $this->db->table($this->table)
            ->where('id_conversa', $id_conversa)
            ->orderBy('data_envio', 'ASC')
            ->findAll();
    }
}

