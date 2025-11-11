<?php

namespace App\Model;

use Core\Library\ModelMain;

class CargoModel extends ModelMain
{
    protected $table = "cargo";
    protected $primaryKey = "cargo_id";

    public $validationRules = [
        "descricao" => [
            "label" => 'Descrição',
            "rules" => 'required|max:50'
        ]
    ];

    /**
     * @return array
     */
    public function listarTodos()
    {
        return $this->db->table($this->table)->orderBy('descricao', 'ASC')->findAll();
    }
}
