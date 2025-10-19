<?php

namespace App\Model;

use Core\Library\ModelMain;

class CurriculumQualificacaoModel extends ModelMain
{
    protected $table = "curriculum_qualificacao";
    protected $primaryKey = "curriculum_qualificacao_id";

    public function getByCurriculumId(int $curriculumId)
    {
        return $this->db
            ->table($this->table)
            ->where("curriculum_id", $curriculumId)
            ->orderBy("ano", "DESC")
            ->findAll();
    }

    public function salvar(array $data)
    {
        $id = $data[$this->primaryKey] ?? null;

        if (!$id && isset($_SESSION['candidato_id'])) {
            $curriculumModel = new CurriculumModel();
            $curriculum = $curriculumModel->getByCandidatoId($_SESSION['candidato_id']);
            if ($curriculum) {
                $data['curriculum_id'] = $curriculum['curriculum_id'];
            }
        }

        if ($id) {
            unset($data[$this->primaryKey]);
            return $this->update($id, $data);
        } else {
            return $this->insert($data);
        }
    }

    public function update($id, $data)
    {
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
