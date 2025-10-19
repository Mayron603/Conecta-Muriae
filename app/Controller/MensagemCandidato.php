<?php

namespace App\Controller;

use Core\Library\ControllerMain;
use Core\Library\Session;
use Core\Library\Redirect;
// USE STATEMENTS ADICIONADOS
use App\Model\PessoaFisicaModel;
use App\Model\CurriculumModel;
use App\Model\CidadeModel;
use App\Model\CurriculumEscolaridadeModel;
use App\Model\CurriculumExperienciaModel;
use App\Model\CurriculumQualificacaoModel;
use App\Model\NotificacaoModel;
use App\Model\ConversaModel;
use App\Model\MensagemModel;
use App\Model\UsuarioModel;

class MensagemCandidato extends ControllerMain
{
    // PROPRIEDADES ADICIONADAS PARA COMPATIBILIDADE
    private $user;
    private $pessoaFisicaId;
    private $curriculumId;

    public function __construct()
    {
        parent::__construct();

        // LÓGICA DE CARREGAMENTO COMPLETA, COPIADA DO CONTROLLER 'Candidatos'
        // E ADAPTADA PARA USAR '$this->viewData'

        $this->user = Session::get('usuario_logado');
        if (empty($this->user) || !in_array($this->user['tipo'], ['CN'])) {
            Redirect::page('login');
            return;
        }

        $this->pessoaFisicaId = $this->user['pessoa_fisica_id'];

        $pessoaFisicaModel = new PessoaFisicaModel();
        $pessoaFisicaDados = $pessoaFisicaModel->getById($this->pessoaFisicaId);

        // O sidebar espera a variável 'usuario'
        $this->viewData['usuario'] = $pessoaFisicaDados
            ? array_merge($this->user, $pessoaFisicaDados)
            : $this->user;

        $curriculumModel = new CurriculumModel();
        $curriculum = $curriculumModel->getByPessoaFisicaId($this->pessoaFisicaId) ?? [];
        $this->curriculumId = $curriculum['curriculum_id'] ?? null;

        if (!empty($curriculum['cidade_id'])) {
            $cidadeModel = new CidadeModel();
            $cidadeData = $cidadeModel->find($curriculum['cidade_id']);
            if ($cidadeData) {
                $curriculum['cidade'] = $cidadeData['cidade'];
                $curriculum['uf'] = $cidadeData['uf'];
            }
        }

        // Carrega dados necessários para o progresso do perfil
        if ($this->curriculumId) {
            $this->viewData['escolaridades'] = (new CurriculumEscolaridadeModel())->getByCurriculumId($this->curriculumId);
            $this->viewData['experiencias'] = (new CurriculumExperienciaModel())->getByCurriculumId($this->curriculumId);
            $this->viewData['qualificacoes'] = (new CurriculumQualificacaoModel())->getByCurriculumId($this->curriculumId);
        } else {
            $this->viewData['escolaridades'] = [];
            $this->viewData['experiencias'] = [];
            $this->viewData['qualificacoes'] = [];
        }

        $notificacaoModel = new NotificacaoModel();
        $this->viewData['unread_notifications'] = $notificacaoModel->countUnread($this->pessoaFisicaId);

        // O sidebar espera a variável 'curriculum' para foto e apresentação
        $this->viewData['curriculum'] = $curriculum;
        // O sidebar espera a variável 'progresso_perfil'
        $this->viewData['progresso_perfil'] = $this->calcularProgressoPerfil();
    }

    // MÉTODO ADICIONADO, COPIADO DO CONTROLLER 'Candidatos' E ADAPTADO
    private function calcularProgressoPerfil(): int
    {
        $progresso = 0;
        $totalCampos = 6;

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

    public function index()
    {
        header('Location: ' . baseUrl() . 'mensagemCandidato/chat');
        exit;
    }

    public function iniciarConversa($id_empresa)
{
    $id_candidato = $this->user['usuario_id'];
    $id_empresa = (int) $id_empresa;

    if ($id_empresa <= 0) {
        header('Location: ' . baseUrl() . 'mensagemCandidato/chat');
        exit;
    }

    $conversaModel = new ConversaModel();

    // [CORREÇÃO] Adicionado ->table('conversa') para especificar a tabela da busca
    $conversa = $conversaModel->db
        ->table('conversa') 
        ->where('id_candidato', $id_candidato)
        ->where('id_empresa', $id_empresa)
        ->first();

    if ($conversa) {
        // Se a conversa já existe, redireciona para o chat dela
        header('Location: ' . baseUrl() . 'mensagemCandidato/chat/' . $conversa['id']);
        exit;
    } else {
        // Se não existe, cria uma nova
        $dadosNovaConversa = [
            'id_candidato' => $id_candidato,
            'id_empresa'   => $id_empresa,
            'data_criacao' => date('Y-m-d H:i:s')
        ];

        // [CORREÇÃO] Adicionado ->table('conversa') para especificar a tabela da inserção
        $novoId = $conversaModel->db->table('conversa')->insert($dadosNovaConversa);

        if ($novoId > 0) {
            // Se a criação deu certo, redireciona para o novo chat
            header('Location: ' . baseUrl() . 'mensagemCandidato/chat/' . $novoId);
            exit;
        } else {
            // Se deu erro ao criar, informa e redireciona
            Session::set('flash_msg', ['mensagem' => 'ERRO: Falha ao iniciar a conversa.', 'tipo' => 'error']);
            header('Location: ' . baseUrl() . 'vagas');
            exit;
        }
    }
}
    public function chat($id_conversa = null)
{
    $conversaModel = new ConversaModel();
    $mensagemModel = new MensagemModel();
    $id_candidato = $this->user['usuario_id'];

    $conversas = $conversaModel->db
        ->select('c.id as id_conversa, e.nome as nome_empresa, c.id_empresa')
        ->table('conversa c')
        ->join('usuario u', 'c.id_empresa = u.usuario_id')
        ->join('estabelecimento e', 'u.estabelecimento_id = e.estabelecimento_id')
        ->where('c.id_candidato', $id_candidato)
        ->findAll();

    $mensagens = [];
    $id_conversa_ativa = $id_conversa;
    $nome_destinatario = 'Selecione uma conversa';

    if ($id_conversa_ativa) {
        $conversaValida = $conversaModel->db
            ->table('conversa') // Adicionado para consistência
            ->where('id', $id_conversa_ativa)
            ->where('id_candidato', $id_candidato)
            ->first();

        if ($conversaValida) {
            // [CORREÇÃO APLICADA AQUI]
            $mensagens = $mensagemModel->db
                ->table('mensagem') // <-- LINHA ADICIONADA
                ->where('id_conversa', $id_conversa_ativa)
                ->orderBy('data_envio', 'ASC')
                ->findAll();
            
            $conversa_atual = $conversaModel->db
                ->select('e.nome as nome_empresa')
                ->table('conversa c')
                ->join('usuario u', 'c.id_empresa = u.usuario_id')
                ->join('estabelecimento e', 'u.estabelecimento_id = e.estabelecimento_id')
                ->where('c.id', $id_conversa_ativa)
                ->first();
            
            if ($conversa_atual) {
                $nome_destinatario = $conversa_atual['nome_empresa'];
            }
        } else {
            $id_conversa_ativa = null;
        }
    }

    $this->viewData = array_merge($this->viewData, [
        'conversas' => $conversas,
        'mensagens' => $mensagens,
        'id_conversa_ativa' => $id_conversa_ativa,
        'nome_destinatario' => $nome_destinatario,
        'id_candidato' => $id_candidato
    ]);

    $flash_msg = Session::get('flash_msg');
    if ($flash_msg) {
        $this->viewData['flash_msg'] = $flash_msg;
        Session::remove('flash_msg');
    }

    $this->loadView('candidatos/mensagens', $this->viewData, false);
}

    public function enviar()
{
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $id_conversa = $_POST['id_conversa'];

        $conversaModel = new ConversaModel();
        $conversaValida = $conversaModel->db
            ->table('conversa') // Adicionado para consistência
            ->where('id', $id_conversa)
            ->where('id_candidato', $this->user['usuario_id'])
            ->first();

        if (!$conversaValida) {
            header('Location: ' . baseUrl() . 'mensagemCandidato/chat');
            exit;
        }

        $mensagemModel = new MensagemModel();
        $dados = [
            'id_conversa' => $id_conversa,
            'id_remetente' => $this->user['usuario_id'],
            'id_destinatario' => $_POST['id_destinatario'], 
            'mensagem' => $_POST['mensagem'],
            'data_envio' => date('Y-m-d H:i:s')
        ];

        // [CORREÇÃO APLICADA AQUI]
        $mensagemModel->db->table('mensagem')->insert($dados); // <-- LINHA MODIFICADA

        header('Location: ' . baseUrl() . 'mensagemCandidato/chat/' . $id_conversa);
        exit;
    }
}
}