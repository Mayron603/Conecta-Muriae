<?php

namespace App\Model;

use Core\Library\ModelMain;

class CurriculumEscolaridadeModel extends ModelMain
{
    protected $table = "curriculum_escolaridade";
    protected $primaryKey = "curriculum_escolaridade_id";

    public function getByCurriculumId(int $curriculumId)
    {
        return $this->db
            ->table($this->table)
            ->select("curriculum_escolaridade.*, escolaridade.descricao as nivel_descricao")
            ->join("escolaridade", "curriculum_escolaridade.escolaridade_id = escolaridade.escolaridade_id")
            ->where("curriculum_curriculum_id", $curriculumId)
            ->orderBy("inicioAno", "DESC")
            ->findAll();
    }

    public function getNiveisEscolaridade()
    {
        return $this->db
            ->table("escolaridade")
            ->select("*")
            ->orderBy("descricao", "ASC")
            ->findAll();
    }

    public function salvar(array $data)
    {
        if (isset($data['curriculum_id'])) {
            $data['curriculum_curriculum_id'] = $data['curriculum_id'];
            unset($data['curriculum_id']);
        }

        $id = $data[$this->primaryKey] ?? null;

        if (!$id && isset($_SESSION['candidato_id']) && !isset($data['curriculum_curriculum_id'])) {
            $curriculumModel = new CurriculumModel();
            $curriculum = $curriculumModel->getByCandidatoId($_SESSION['candidato_id']);
            if ($curriculum) {
                $data['curriculum_curriculum_id'] = $curriculum['curriculum_id'];
            }
        }

        if ($id) {
            return $this->update($id, $data);
        } else {
            return $this->insert($data);
        }
    }

    public function update($id, $data)
    {
        unset($data[$this->primaryKey]);
        return (bool) $this->db
            ->table($this->table)
            ->where($this->primaryKey, $id)
            ->update($data);
    }

    public function delete($id)
    {
        return (bool) $this->db
            ->table($this->table)
            ->where($this->primaryKey, $id)
            ->delete();
    }
}
