<?php

namespace App\Model;

use Core\Library\ModelMain;

class EstabelecimentoModel extends ModelMain
{
    protected $table = "estabelecimento";
    protected $primaryKey = "estabelecimento_id";

    public $validationRules = [
        "nome" => [
            "label" => 'Nome',
            "rules" => 'required|max:50'
        ],
        "endereco" => [
            "label" => 'Endereço',
            "rules" => 'max:200'
        ],
        "latitude" => [
            "label" => 'Latitude',
            "rules" => 'max:12'
        ],
        "longitude" => [
            "label" => 'Longitude',
            "rules" => 'max:12'
        ],
        "email" => [
            "label" => 'E-mail',
            "rules" => 'required|email|max:150'
        ],
        "sobre" => [
            "label" => 'Sobre a Empresa',
            "rules" => 'max:1000'
        ],
        "website" => [
            "label" => 'Website',
            "rules" => 'max:255'
        ],
        "logo" => [
            "label" => "Logo da Empresa",
            "rules" => "uploaded[logo]|max_size[logo,2048]|is_image[logo]",
            "errors" => [
                "max_size" => "O arquivo é muito grande. O máximo permitido é 2MB.",
                "is_image" => "O arquivo não é uma imagem válida."
            ]
        ]
    ];

    public function updateLogo($id, $logo)
    {
        // [CORREÇÃO DEFINITIVA]
        // A sintaxe correta, seguindo o padrão do seu projeto, 
        // exige a chamada ->table() para especificar a tabela antes do ->where().
        return $this->db->table($this->table)->where($this->primaryKey, $id)->update(['logo' => $logo]);
    }

    /**
     * Conta o número total de estabelecimentos
     */
    public function countAll()
    {
        return $this->db->table($this->table)->findCount();
    }
}
