<?php

namespace App\Model;

use Core\Library\ModelMain;

class ConversaModel extends ModelMain
{
    protected $table = "conversa";
    protected $primaryKey = "id";

    /**
     * Busca as conversas de um utilizador, juntando o nome do interlocutor.
     *
     * @param int $userId ID do utilizador logado (seja candidato ou empresa)
     * @param string $userType 'candidato' ou 'empresa'
     * @return array
     */
    public function findConversasByUser(int $userId, string $userType): array
    {
        if ($userType === 'candidato') {
            // Se o utilizador é um candidato, queremos o nome da empresa
            return $this->db->table($this->table . ' c')
                ->select('c.id as id_conversa, e.nome as nome_interlocutor, c.id_empresa as id_interlocutor')
                ->join('usuario u', 'c.id_empresa = u.usuario_id')
                ->join('estabelecimento e', 'u.estabelecimento_id = e.estabelecimento_id')
                ->where('c.id_candidato', $userId)
                ->orderBy('c.id', 'DESC')
                ->findAll();
        }

        if ($userType === 'empresa') {
            // Se o utilizador é uma empresa, queremos o nome do candidato
            return $this->db->table($this->table . ' c')
                ->select('c.id as id_conversa, pf.nome as nome_interlocutor, c.id_candidato as id_interlocutor')
                ->join('usuario u', 'c.id_candidato = u.usuario_id')
                ->join('pessoa_fisica pf', 'u.pessoa_fisica_id = pf.pessoa_fisica_id')
                ->where('c.id_empresa', $userId)
                ->orderBy('c.id', 'DESC')
                ->findAll();
        }

        return [];
    }
}
