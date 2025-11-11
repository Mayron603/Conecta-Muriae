<?php

namespace App\Model;

use Core\Library\ModelMain;

class CurriculumModel extends ModelMain
{
    protected $table = "curriculum";
    protected $primaryKey = "curriculum_id";

    public $validationRules = [
        'dataNascimento'       => ['label' => 'Data de Nascimento', 'rules' => 'required|date'],
        'sexo'                 => ['label' => 'Sexo', 'rules' => 'required|in:M,F'],
        'celular'              => ['label' => 'Celular', 'rules' => 'required'],
        'cep'                  => ['label' => 'CEP', 'rules' => 'required'],
        'logradouro'           => ['label' => 'Logradouro', 'rules' => 'required'],
        'numero'               => ['label' => 'Número', 'rules' => 'required'],
        'bairro'               => ['label' => 'Bairro', 'rules' => 'required'],
        'cidade_id'            => ['label' => 'Cidade', 'rules' => 'required|integer'], 
        'apresentacaoPessoal'  => ['label' => 'Apresentação Pessoal', 'rules' => 'required|min:20'],
        'pessoa_fisica_id'     => ['label' => 'ID Pessoa Física', 'rules' => 'required|integer'],
        'email'                => ['label' => 'E-mail', 'rules' => 'required|email']
    ];

    public function getByPessoaFisicaId(int $pessoaFisicaId)
    {
        return $this->db->where("pessoa_fisica_id", $pessoaFisicaId)->first();
    }

    public function getCompletoById($curriculoId)
    {
        if (empty($curriculoId)) {
            return [];
        }

        $sqlCurriculo = "SELECT c.*, pf.nome, pf.cpf, u.usuario_id 
                         FROM curriculum c
                         JOIN pessoa_fisica pf ON c.pessoa_fisica_id = pf.pessoa_fisica_id
                         JOIN usuario u ON pf.pessoa_fisica_id = u.pessoa_fisica_id
                         WHERE c.curriculum_id = ?";
        $rscCurriculo = $this->db->dbSelect($sqlCurriculo, [$curriculoId]);
        $curriculo = $this->db->dbBuscaArray($rscCurriculo);

        if (empty($curriculo)) {
            return [];
        }
        
        $sqlEscolaridades = "SELECT ce.*, e.descricao AS nivel_escolaridade
                             FROM curriculum_escolaridade ce
                             JOIN escolaridade e ON ce.escolaridade_id = e.escolaridade_id
                             WHERE ce.curriculum_curriculum_id = ?";
        $rscEscolaridades = $this->db->dbSelect($sqlEscolaridades, [$curriculoId]);
        $curriculo['escolaridades'] = $this->db->dbBuscaArrayAll($rscEscolaridades);
        
        $sqlExperiencias = "SELECT cexp.*, c.descricao AS cargo_nome
                            FROM curriculum_experiencia cexp
                            LEFT JOIN cargo c ON cexp.cargo_id = c.cargo_id
                            WHERE cexp.curriculum_id = ?";
        $rscExperiencias = $this->db->dbSelect($sqlExperiencias, [$curriculoId]);
        $curriculo['experiencias'] = $this->db->dbBuscaArrayAll($rscExperiencias);

        $sqlQualificacoes = "SELECT *
                             FROM curriculum_qualificacao
                             WHERE curriculum_id = ?";
        $rscQualificacoes = $this->db->dbSelect($sqlQualificacoes, [$curriculoId]);
        $curriculo['qualificacoes'] = $this->db->dbBuscaArrayAll($rscQualificacoes);

        return $curriculo;
    }

    public function salvar(array $dados)
    {

        unset($dados['cidade'], $dados['uf'], $dados['cpf']);

        try {
            $curriculum = $this->getByPessoaFisicaId($dados['pessoa_fisica_id']);

            if ($curriculum && isset($curriculum[$this->primaryKey])) {
                // Atualiza
                $this->db->where($this->primaryKey, $curriculum[$this->primaryKey])->update($dados);
            } else {
                // Insere
                $this->db->insert($dados);
            }
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function updateArquivoCurriculo(int $curriculumId, ?string $nomeArquivo): bool
    {
        if (empty($curriculumId)) {
            return false;
        }
        
        $dados = ['arquivo_curriculo' => $nomeArquivo];
        
        return $this->db->where($this->primaryKey, $curriculumId)->update($dados);
    }

    public function updateFoto(int $curriculumId, string $nomeFoto): bool
    {
        if (empty($curriculumId) || empty($nomeFoto)) {
            return false;
        }

        return $this->db->where($this->primaryKey, $curriculumId)->update(['foto' => $nomeFoto]);
    }
}