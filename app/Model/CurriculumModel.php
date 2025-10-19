<?php

namespace App\Model;

use Core\Library\ModelMain;
use Core\Library\Validator;
use App\Model\CidadeModel;
use Core\Library\Session;

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
        'cidade'               => ['label' => 'Cidade', 'rules' => 'required|max:200'],
        'uf'                   => ['label' => 'UF', 'rules' => 'required|size:2'],
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

        // 1. Dados principais do currículo
        $sqlCurriculo = "SELECT c.*, pf.nome, pf.cpf
                         FROM curriculum c
                         JOIN pessoa_fisica pf ON c.pessoa_fisica_id = pf.pessoa_fisica_id
                         WHERE c.curriculum_id = ?";
        $rscCurriculo = $this->db->dbSelect($sqlCurriculo, [$curriculoId]);
        $curriculo = $this->db->dbBuscaArray($rscCurriculo);

        if (empty($curriculo)) {
            return [];
        }

        // 2. Escolaridades
        $sqlEscolaridades = "SELECT ce.*, e.descricao AS nivel_escolaridade
                             FROM curriculum_escolaridade ce
                             JOIN escolaridade e ON ce.escolaridade_id = e.escolaridade_id
                             WHERE ce.curriculum_curriculum_id = ?";
        $rscEscolaridades = $this->db->dbSelect($sqlEscolaridades, [$curriculoId]);
        $curriculo['escolaridades'] = $this->db->dbBuscaArrayAll($rscEscolaridades);

        // 3. Experiências
        $sqlExperiencias = "SELECT cexp.*, c.descricao AS cargo_nome
                            FROM curriculum_experiencia cexp
                            LEFT JOIN cargo c ON cexp.cargo_id = c.cargo_id
                            WHERE cexp.curriculum_id = ?";
        $rscExperiencias = $this->db->dbSelect($sqlExperiencias, [$curriculoId]);
        $curriculo['experiencias'] = $this->db->dbBuscaArrayAll($rscExperiencias);

        // 4. Qualificações
        $sqlQualificacoes = "SELECT *
                             FROM curriculum_qualificacao
                             WHERE curriculum_id = ?";
        $rscQualificacoes = $this->db->dbSelect($sqlQualificacoes, [$curriculoId]);
        $curriculo['qualificacoes'] = $this->db->dbBuscaArrayAll($rscQualificacoes);

        return $curriculo;
    }

    /**
     * Cria ou atualiza um currículo com a lógica de cidade/uf
     */
    public function salvar(array $dados)
    {
        // --- Validação Primeiro ---
        if (Validator::make($dados, $this->validationRules)) {
            return false;
        }

        // --- Lógica de Cidade ---
        $cidadeNome = trim($dados['cidade']);
        $uf = strtoupper(trim($dados['uf']));

        $cidadeModel = new CidadeModel();
        $cidadeData = $cidadeModel->getByCidadeAndUf($cidadeNome, $uf);
        $cidadeId = null;

        if ($cidadeData) {
            $cidadeId = $cidadeData['cidade_id'];
        } else {
            $cidadeId = $cidadeModel->insert([
                'cidade' => $cidadeNome,
                'uf'     => $uf
            ]);
        }

        if (empty($cidadeId)) {
            Session::set('errors', ['cidade' => 'Não foi possível encontrar ou criar a cidade especificada.']);
            return false;
        }

        $dados['cidade_id'] = $cidadeId;
        unset($dados['cidade'], $dados['uf'], $dados['cpf']);

        // --- Inserir ou atualizar ---
        $curriculum = $this->getByPessoaFisicaId($dados['pessoa_fisica_id']);
        $dataToSave = array_intersect_key($dados, $this->validationRules);

        if ($curriculum && isset($curriculum[$this->primaryKey])) {
            $this->db->where($this->primaryKey, $curriculum[$this->primaryKey])->update($dados);
        } else {
            $this->db->insert($dados);
        }

        return true;
    }

    /**
     * Atualiza apenas a foto do perfil sem rodar a validação completa.
     */
    public function updateFoto(int $curriculumId, string $nomeFoto): bool
    {
        if (empty($curriculumId) || empty($nomeFoto)) {
            return false;
        }

        // Atualiza diretamente no banco de dados
        return $this->db->where($this->primaryKey, $curriculumId)->update(['foto' => $nomeFoto]);
    }
}
