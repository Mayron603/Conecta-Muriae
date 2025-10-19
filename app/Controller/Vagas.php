<?php

namespace App\Controller;

use Core\Library\ControllerMain;
use App\Model\VagaModel;
use App\Model\VagaCurriculumModel;
use App\Model\CurriculumModel;
use App\Service\NotificationService; // Importando o serviço de notificação
use Core\Library\Session;
use Core\Library\Redirect;

class Vagas extends ControllerMain
{
    public function index()
    {
        $vagaModel = new VagaModel();
        $vagas = $vagaModel->listarPublicas();

        $this->loadView('vagas', ['vagas' => $vagas]);
    }

    public function visualizar($vagaId = null)
{
    if (!$vagaId) {
        Redirect::page('vagas');
        return;
    }

    $vagaModel = new VagaModel();

    // --- CORREÇÃO APLICADA AQUI ---
    // Modificamos a busca para garantir que o usuario_id da empresa seja incluído.
    $vaga = $vagaModel->db
        ->select('v.*, e.nome as nome_fantasia, c.descricao as cargo_descricao, u.usuario_id') // Adicionado u.usuario_id
        ->table('vaga v')
        ->join('estabelecimento e', 'v.estabelecimento_id = e.estabelecimento_id')
        ->join('cargo c', 'v.cargo_id = c.cargo_id')
        ->join('usuario u', 'v.estabelecimento_id = u.estabelecimento_id') // Adicionado JOIN com a tabela usuario
        ->where('v.vaga_id', (int)$vagaId)
        ->first();
    // --- FIM DA CORREÇÃO ---

    if (empty($vaga)) {
        Session::set('flash_msg', ['mensagem' => 'Vaga não encontrada.', 'tipo' => 'error']);
        Redirect::page('vagas');
        return;
    }

    $this->loadView('vagas/visualizar', ['vaga' => $vaga], true);
}

    public function candidatar($vagaId = null)
    {
        if (!$vagaId) {
            Redirect::page('vagas');
            return;
        }

        $user = Session::get('usuario_logado');
        if (empty($user) || $user['tipo'] !== 'CN') {
            Session::set('flash_msg', ['mensagem' => 'Apenas candidatos podem se candidatar a vagas.', 'tipo' => 'error']);
            Redirect::page('login');
            return;
        }

        $curriculumModel = new CurriculumModel();
        $curriculum = $curriculumModel->getByPessoaFisicaId($user['pessoa_fisica_id']);

        if (empty($curriculum)) {
            Session::set('flash_msg', ['mensagem' => 'Você precisa cadastrar um currículo antes de se candidatar.', 'tipo' => 'warning']);
            Redirect::page('candidatos/curriculo');
            return;
        }

        $vagaCurriculumModel = new VagaCurriculumModel();
        
        $dadosCandidatura = [
            'vaga_id' => $vagaId,
            'curriculum_id' => $curriculum['curriculum_id'],
            'dateCandidatura' => date('Y-m-d H:i:s')
        ];

        if ($vagaCurriculumModel->insert($dadosCandidatura)) {
            Session::set('flash_msg', ['mensagem' => 'Candidatura realizada com sucesso!', 'tipo' => 'success']);

            // **INÍCIO DA INTEGRAÇÃO DE NOTIFICAÇÃO**
            $vagaModel = new VagaModel();
            $vaga = $vagaModel->findCompletoById((int)$vagaId);

            if ($vaga) {
                $notificationService = new NotificationService();
                $notificationService->notificaNovaCandidatura($user['pessoa_fisica_id'], $vaga);
            }
            // **FIM DA INTEGRAÇÃO DE NOTIFICAÇÃO**

        } else {
            Session::set('flash_msg', ['mensagem' => 'Você já se candidatou a esta vaga.', 'tipo' => 'error']);
        }

        Redirect::page('vagas');
    }
}
