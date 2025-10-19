<?php

namespace App\Model;

use Core\Library\ModelMain;
use App\Model\CurriculumModel; 

class VagaCurriculumModel extends ModelMain
{
    protected $table = "vaga_curriculum";
    protected $primaryKey = ["vaga_id", "curriculum_id"];

    public function getCandidaturasPorPessoaFisicaId(int $pessoaFisicaId): array
    {
        $curriculumModel = new CurriculumModel();
        $curriculum = $curriculumModel->getByPessoaFisicaId($pessoaFisicaId);

        if (empty($curriculum) || !isset($curriculum['curriculum_id'])) {
            return [];
        }
        $curriculumId = $curriculum['curriculum_id'];

        $sql = "SELECT * FROM {$this->table} WHERE curriculum_id = ? ORDER BY dateCandidatura DESC";
        $rsc = $this->db->dbSelect($sql, [$curriculumId]);
        $result = $this->db->dbBuscaArrayAll($rsc);

        return $result ?? [];
    }

    public function countByEstabelecimento($idEstabelecimento)
    {
        if (empty($idEstabelecimento)) {
            return 0;
        }

        $results = $this->db->table('vaga_curriculum vc')
                         ->select('COUNT(vc.vaga_id) as total')
                         ->join('vaga v', 'vc.vaga_id = v.vaga_id')
                         ->where('v.estabelecimento_id', $idEstabelecimento)
                         ->findAll();

        return !empty($results) ? (int)$results[0]['total'] : 0;
    }

    public function countByVagaId($vagaId)
    {
        if (empty($vagaId)) {
            return 0;
        }

        return $this->db->table($this->table)
                         ->where('vaga_id', $vagaId)
                         ->findCount();
    }

    public function getCandidatosPorVaga($vagaId)
    {
        if (empty($vagaId)) {
            return [];
        }

        return $this->db->table('vaga_curriculum vc')
            ->select('p.nome, c.curriculum_id, vc.dateCandidatura')
            ->join('curriculum c', 'vc.curriculum_id = c.curriculum_id')
            ->join('pessoa_fisica p', 'c.pessoa_fisica_id = p.pessoa_fisica_id')
            ->where('vc.vaga_id', $vagaId)
            ->orderBy('vc.dateCandidatura', 'DESC')
            ->findAll();
    }

    public function getCandidatosPorEstabelecimento($idEstabelecimento)
    {
        if (empty($idEstabelecimento)) {
            return [];
        }

        return $this->db->table('vaga_curriculum vc')
            ->select(
                'p.nome as candidato_nome, ' .
                'c.curriculum_id, ' .
                'v.descricao as vaga_descricao, ' .
                'v.vaga_id, ' .
                'vc.dateCandidatura'
            )
            ->join('curriculum c', 'vc.curriculum_id = c.curriculum_id')
            ->join('pessoa_fisica p', 'c.pessoa_fisica_id = p.pessoa_fisica_id')
            ->join('vaga v', 'vc.vaga_id = v.vaga_id')
            ->where('v.estabelecimento_id', $idEstabelecimento)
            ->orderBy('vc.dateCandidatura', 'DESC')
            ->findAll();
    }
}
