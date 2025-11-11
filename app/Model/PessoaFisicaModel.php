<?php

namespace App\Model;

use Core\Library\ModelMain;

class PessoaFisicaModel extends ModelMain
{
    protected $table = "pessoa_fisica";
    protected $primaryKey = "pessoa_fisica_id";

    public $validationRules = [
        "nome"  => [
            "label" => 'Nome Completo',
            "rules" => 'required|min:5|max:255' 
        ]
    ];

    public function getCpf(string $cpf)
    {
        return $this->db->table($this->table)->where('cpf', $cpf)->first();
    }

    public function updateNome(int $pessoaFisicaId, string $nomeCompleto): bool
    {
        $dados = ['nome' => $nomeCompleto];


        try {
            $this->db->where($this->primaryKey, $pessoaFisicaId)->update($dados);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function countAll()
    {
        return $this->db->table($this->table)->findCount();
    }
}