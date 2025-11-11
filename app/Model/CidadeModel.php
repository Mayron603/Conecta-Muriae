<?php

namespace App\Model;

use Core\Library\ModelMain;

class CidadeModel extends ModelMain
{
    protected $table = "cidade";
    protected $primaryKey = "cidade_id";

    public $validationRules = [
        "cidade"  => [
            "label" => 'Cidade',
            "rules" => 'required|min:3|max:200'
        ],
        "uf"  => [
            "label" => 'UF',
            "rules" => 'required|min:2|max:2'
        ],
    ];

    /**
     * @return array
     */
    public function getAll(): array
    {   
        $sql = "SELECT * FROM {$this->table} ORDER BY cidade ASC";
        $rsc = $this->db->dbSelect($sql);
        return $this->db->dbBuscaArrayAll($rsc);
    }

    /**
     * @return array
     */
    public function listaCidade()
    {   
        return $this->db
            ->orderBy("uf, cidade")
            ->findAll();
    }

    /**
     * @param string
     * @param string 
     * @return array|null
     */
    public function getByCidadeAndUf(string $cidade, string $uf)
    {
        return $this->db->where([
            'cidade' => trim($cidade),
            'uf'     => strtoupper(trim($uf))
        ])->first();
    }

    /**
     * @param string 
     * @param string 
     * @return int
     */
    public function getOrCreateCidadeId(string $cidade, string $uf): int
    {
        $cidadeData = $this->getByCidadeAndUf($cidade, $uf);
        if ($cidadeData) {
            return $cidadeData['cidade_id'];
        }

        // Insere nova cidade
        return $this->insert([
            'cidade' => trim($cidade),
            'uf' => strtoupper(trim($uf))
        ]);
    }
}
