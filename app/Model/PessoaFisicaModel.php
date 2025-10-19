<?php

namespace App\Model;

use Core\Library\ModelMain;
use Core\Library\Validator;
use Core\Library\Session;

class PessoaFisicaModel extends ModelMain
{
    protected $table = "pessoa_fisica";
    protected $primaryKey = "pessoa_fisica_id";

    // Regra de validação para o NOME COMPLETO
    public $validationRules = [
        "nome"  => [
            "label" => 'Nome Completo',
            "rules" => 'required|min:5|max:255' 
        ]
    ];

    /**
     * Busca um CPF no banco de dados.
     *
     * @param string $cpf O CPF a ser pesquisado (somente números).
     * @return mixed Retorna os dados do usuário se encontrado, caso contrário, false.
     */
    public function getCpf(string $cpf)
    {
        return $this->db->table($this->table)->where('cpf', $cpf)->first();
    }

    /**
     * Atualiza o nome de uma pessoa física.
     *
     * @param int    $pessoaFisicaId O ID da pessoa a ser atualizada.
     * @param string $nomeCompleto   O nome completo a ser salvo.
     * @return bool                  Retorna true em caso de sucesso, false em caso de falha.
     */
    public function updateNome(int $pessoaFisicaId, string $nomeCompleto): bool
    {
        $dados = ['nome' => $nomeCompleto];

        // Valida os dados usando as regras da classe
        if (Validator::make($dados, $this->validationRules)) {
            // O Validator já salva os erros na sessão
            return false;
        }

        try {
            // Executa o update no banco
            $this->db->where($this->primaryKey, $pessoaFisicaId)->update($dados);
            return true;
        } catch (\Exception $e) {
            Session::set('form_errors', ['database' => 'Ocorreu um erro ao salvar o nome.']);
            return false;
        }
    }

    /**
     * Conta o número total de pessoas físicas
     */
    public function countAll()
    {
        return $this->db->table($this->table)->findCount();
    }
}
