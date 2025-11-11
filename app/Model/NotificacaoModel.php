<?php

namespace App\Model;

use Core\Library\ModelMain;
use Core\Library\Database;

class NotificacaoModel extends ModelMain
{
    protected $table = 'notificacoes';
    protected $primaryKey = 'id';

    public function create($pessoaFisicaId, $titulo, $mensagem, $link)
    {
        $dados = [
            'pessoa_fisica_id' => $pessoaFisicaId,
            'titulo'           => $titulo,
            'mensagem'         => $mensagem,
            'link'             => $link,
            'lida'             => 0,
            'dataCriacao'      => date('Y-m-d H:i:s'),
            'dataUpdate'       => date('Y-m-d H:i:s')
        ];
        return parent::insert($dados);
    }

    public function getByPessoaFisicaId($pessoaFisicaId, $limit = 20)
    {
        return $this->db->where('pessoa_fisica_id', $pessoaFisicaId)
                      ->orderBy('dataCriacao', 'DESC')
                      ->limit($limit) 
                      ->findAll();
    }

    public function countUnread($pessoaFisicaId)
    {
        $result = $this->db->select('COUNT(id) as total')
                           ->where('pessoa_fisica_id', $pessoaFisicaId)
                           ->where('lida', 0)
                           ->first();
                           
        return $result['total'] ?? 0;
    }

    public function markAllAsRead($pessoaFisicaId)
    {
        $dados = [
            'lida' => 1,
            'dataUpdate' => date('Y-m-d H:i:s')
        ];

        return $this->db->where('pessoa_fisica_id', $pessoaFisicaId)
                      ->where('lida', 0)
                      ->update($dados);
    }
}
