<?php

namespace App\Model;

use Core\Library\ModelMain;

class VagaModel extends ModelMain
{
    protected $table = "vaga";
    protected $primaryKey = "vaga_id";

    public $validationRules = [
        "cargo_id" => [
            "label" => 'Cargo',
            "rules" => 'required|integer'
        ],
        "descricao" => [
            "label" => 'Descrição',
            "rules" => 'required|max:60'
        ],
        "sobreaVaga" => [
            "label" => 'Sobre a Vaga',
            "rules" => 'required'
        ],
        "modalidade" => [
            "label" => 'Modalidade',
            "rules" => 'required|integer'
        ],
        "vinculo" => [
            "label" => 'Vínculo',
            "rules" => 'required|integer'
        ],
        "dtInicio" => [
            "label" => 'Data de Início',
            "rules" => 'required|date'
        ],
        "dtFim" => [
            "label" => 'Data de Fim',
            "rules" => 'required|date'
        ],
        "estabelecimento_id" => [
            "label" => 'Estabelecimento',
            "rules" => 'required|integer'
        ],
        "statusVaga" => [
            "label" => 'Status da Vaga',
            "rules" => 'required|integer'
        ]
    ];

    public function listarPublicas()
    {
        return $this->db->table($this->table . ' v')
            ->select(
                'v.*, ' .
                'c.descricao as cargo_descricao, ' .
                'e.nome as nome_fantasia'
            )
            ->join('cargo c', 'v.cargo_id = c.cargo_id')
            ->join('estabelecimento e', 'v.estabelecimento_id = e.estabelecimento_id')
            ->where('v.statusVaga', 11)
            ->orderBy('v.dtInicio', 'DESC')
            ->findAll();
    }

     public function findCompletoById($vagaId)
    {
        if (empty($vagaId)) {
            return null;
        }

        return $this->db->table($this->table . ' v')
            ->select(
                'v.*, ' . 
                'c.descricao as cargo_descricao, ' .
                'e.nome as nome_fantasia, ' .
                'u.usuario_id' // A seleção já estava correta
            )
            ->join('cargo c', 'v.cargo_id = c.cargo_id')
            ->join('estabelecimento e', 'v.estabelecimento_id = e.estabelecimento_id')
            // [CORREÇÃO APLICADA AQUI] Trocado 'EM' por 'A' para corresponder ao seu banco de dados
            ->join('usuario u', 'e.estabelecimento_id = u.estabelecimento_id AND u.tipo = \'A\'', 'LEFT')
            ->where('v.vaga_id', $vagaId)
            ->first();
    }
    public function getByEstabelecimento($idEstabelecimento)
    {
        if (empty($idEstabelecimento)) {
            return [];
        }

        return $this->db->table($this->table . ' v')
            ->select(
                'v.*, ' .
                'c.descricao as cargo_descricao, ' .
                'e.nome as nome_fantasia'
            )
            ->join('cargo c', 'v.cargo_id = c.cargo_id')
            ->join('estabelecimento e', 'v.estabelecimento_id = e.estabelecimento_id')
            ->where('v.estabelecimento_id', $idEstabelecimento)
            ->orderBy('v.dtInicio', 'DESC')
            ->findAll();
    }

    public function getById($id)
    {
        return $this->db->where($this->primaryKey, $id)->first();
    }

    public function countAtivas()
    {
        return $this->db->table($this->table)
            ->where('statusVaga', 11)
            ->findCount();
    }

    public function countByEstabelecimento($idEstabelecimento, $statusVaga = null)
    {
        if (empty($idEstabelecimento)) {
            return 0;
        }

        $this->db->table($this->table)->where('estabelecimento_id', $idEstabelecimento);

        if ($statusVaga !== null) {
            $this->db->where('statusVaga', $statusVaga);
        }

        return $this->db->findCount();
    }
    
    public function findRecentesByEstabelecimento($idEstabelecimento, $limit = 5)
    {
        if (empty($idEstabelecimento)) {
            return [];
        }

        return $this->db->table($this->table . ' v')
            ->select(
                'v.*, ' .
                'c.descricao as cargo_descricao'
            )
            ->join('cargo c', 'v.cargo_id = c.cargo_id')
            ->where('v.estabelecimento_id', $idEstabelecimento)
            ->where('v.statusVaga', 11)
            ->orderBy('v.vaga_id', 'DESC')
            ->limit($limit)
            ->findAll();
    }
}
