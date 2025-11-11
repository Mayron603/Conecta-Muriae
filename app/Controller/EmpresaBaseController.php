<?php

namespace App\Controller;

use Core\Library\ControllerMain;
use Core\Library\Session;
use Core\Library\Redirect;
use App\Model\EstabelecimentoModel;
use App\Model\VagaModel;
use App\Model\VagaCurriculumModel;

/**
 * Classe base para todos os controllers
 */
class EmpresaBaseController extends ControllerMain
{
    protected $usuarioLogado;
    protected $estabelecimentoId;

    public function __construct()
    {
        // 1. CHAMA O CONSTRUTOR PAI
        parent::__construct();

        // 2. PEGA O USUÁRIO DA SESSÃO
        $this->usuarioLogado = Session::get('usuario_logado');

        // 3. AUTENTICAÇÃO
        if (empty($this->usuarioLogado) || !in_array($this->usuarioLogado['tipo'], ['A', 'G'])) {
            Session::set('flash_msg', ['mensagem' => 'Acesso não autorizado.', 'tipo' => 'error']);
            Redirect::page('login');
            exit; 
        }

        // 4. VERIFICA O ID DO ESTABELECIMENTO
        if (!isset($this->usuarioLogado['estabelecimento_id'])) {
            if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
                header('Content-Type: application/json');
                http_response_code(401); // Unauthorized
                echo json_encode(['success' => false, 'message' => 'ERRO DE SESSÃO: O ID do estabelecimento não foi encontrado. Por favor, faça login novamente.']);
                exit;
            } else {
                Session::set('flash_msg', ['mensagem' => 'Erro de sessão. Por favor, faça login novamente.', 'tipo' => 'error']);
                Redirect::page('login');
                exit;
            }
        }

        $this->estabelecimentoId = $this->usuarioLogado['estabelecimento_id'];

        // 5. CARREGA OS MODELS E DADOS
        $estabelecimentoModel = $this->loadModel("Estabelecimento");
        $vagaModel = $this->loadModel("Vaga");
        $vagaCurriculumModel = $this->loadModel("VagaCurriculum");

        $estabelecimentoDados = $estabelecimentoModel->getById($this->estabelecimentoId);

        // 6. $this->viewData
        $this->viewData['usuario'] = $estabelecimentoDados
            ? array_merge($this->usuarioLogado, $estabelecimentoDados)
            : $this->usuarioLogado;

        $totalVagas = $vagaModel->countByEstabelecimento($this->estabelecimentoId, 11);
        $totalCandidatos = $vagaCurriculumModel->countByEstabelecimento($this->estabelecimentoId);

        $this->viewData['stats'] = [
            'vagas' => $totalVagas,
            'candidatos' => $totalCandidatos
        ];
    }
}