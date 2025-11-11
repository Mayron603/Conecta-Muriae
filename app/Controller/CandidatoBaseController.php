<?php

namespace App\Controller;

use Core\Library\ControllerMain;
use Core\Library\Session;
use Core\Library\Redirect;
use App\Model\PessoaFisicaModel;
use App\Model\CurriculumModel;
use App\Model\CidadeModel;
use App\Model\CurriculumEscolaridadeModel;
use App\Model\CurriculumExperienciaModel;
use App\Model\CurriculumQualificacaoModel;
use App\Model\NotificacaoModel;

/**
 * Classe base para todos os controllers
 */
class CandidatoBaseController extends ControllerMain
{
    protected $usuarioLogado;
    protected $pessoaFisicaId;
    protected $curriculumId;

    public function __construct()
    {
        // 1. CHAMA O CONSTRUTOR PAI (ControllerMain)
        parent::__construct();

        // 2. PEGA O USUÁRIO DA SESSÃO
        $this->usuarioLogado = Session::get('usuario_logado');

        // 3. AUTENTICAÇÃO
        if (empty($this->usuarioLogado) || !isset($this->usuarioLogado['pessoa_fisica_id'])) {
            Redirect::page('login');
            exit; 
        }

        $this->pessoaFisicaId = $this->usuarioLogado['pessoa_fisica_id'];

        // 4. CARREGA OS MODELS E DADOS 
        $pessoaFisicaModel = $this->loadModel("PessoaFisica");
        $curriculumModel = $this->loadModel("Curriculum");
        $cidadeModel = $this->loadModel("Cidade");
        $notificacaoModel = $this->loadModel("Notificacao");
        $escolaridadeModel = $this->loadModel("CurriculumEscolaridade");
        $experienciaModel = $this->loadModel("CurriculumExperiencia");
        $qualificacaoModel = $this->loadModel("CurriculumQualificacao");
        
        // 5. $this->viewData
        $pessoaFisicaDados = $pessoaFisicaModel->getById($this->pessoaFisicaId);

        $this->viewData['usuario'] = $pessoaFisicaDados
            ? array_merge($this->usuarioLogado, $pessoaFisicaDados)
            : $this->usuarioLogado;

        // Carrega o currículo
        $curriculum = $curriculumModel->getByPessoaFisicaId($this->pessoaFisicaId) ?? [];
        $this->curriculumId = $curriculum['curriculum_id'] ?? null;

        // dados da cidade
        if (!empty($curriculum['cidade_id'])) {
            $cidadeData = $cidadeModel->find($curriculum['cidade_id']);
            if ($cidadeData) {
                $curriculum['cidade'] = $cidadeData['cidade'];
                $curriculum['uf'] = $cidadeData['uf'];
            }
        }
        $this->viewData['curriculum'] = $curriculum;

        // cálculo de progresso
        if ($this->curriculumId) {
            $this->viewData['escolaridades'] = $escolaridadeModel->getByCurriculumId($this->curriculumId);
            $this->viewData['experiencias'] = $experienciaModel->getByCurriculumId($this->curriculumId);
            $this->viewData['qualificacoes'] = $qualificacaoModel->getByCurriculumId($this->curriculumId);
        } else {
            $this->viewData['escolaridades'] = [];
            $this->viewData['experiencias'] = [];
            $this->viewData['qualificacoes'] = [];
        }

        // Carrega Notificações e Progresso
        $this->viewData['unread_notifications'] = $notificacaoModel->countUnread($this->pessoaFisicaId);
        $this->viewData['progresso_perfil'] = $this->calcularProgressoPerfil();
    }


    // progresso do perfil
    private function calcularProgressoPerfil(): int
    {
        $progresso = 0;
        $totalCampos = 6; 

        // $this->viewData
        if (!empty($this->viewData['curriculum']['foto']) && $this->viewData['curriculum']['foto'] !== 'default.png') {
            $progresso++;
        }
        if (!empty($this->viewData['curriculum']['apresentacaoPessoal'])) {
            $progresso++;
        }
        if (!empty($this->viewData['curriculum']['cidade_id'])) { 
            $progresso++;
        }
        if (!empty($this->viewData['escolaridades'])) {
            $progresso++;
        }
        if (!empty($this->viewData['experiencias'])) {
            $progresso++;
        }
        if (!empty($this->viewData['qualificacoes'])) {
            $progresso++;
        }

        return ($totalCampos > 0) ? round(($progresso / $totalCampos) * 100) : 0;
    }
}