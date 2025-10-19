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
     * Busca todos os cargos cadastrados, em ordem alfabética.
     *
     * @return array
     */
    public function listarTodos()
    {
        // Usa o Query Builder interno para buscar e ordenar os cargos
        return $this->db->table($this->table)->orderBy('descricao', 'ASC')->findAll();
    }
}
