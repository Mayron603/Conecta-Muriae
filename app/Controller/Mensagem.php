<?php

namespace App\Controller;

use Core\Library\ControllerMain;
use App\Model\ConversaModel;
use App\Model\MensagemModel;
use Core\Library\Session;
// Models necessários para o sidebar da empresa
use App\Model\EstabelecimentoModel;
use App\Model\UsuarioModel;


class Mensagem extends ControllerMain
{
    private $user;
    // [CORREÇÃO APLICADA AQUI] A visibilidade deve ser 'public' para ser compatível com a classe pai.
    public $viewData = [];

    public function __construct()
    {
        parent::__construct();
        $this->user = Session::get('usuario_logado');

        if (empty($this->user) || !in_array($this->user['tipo'], ['A', 'G'])) {
            header('Location: ' . baseUrl() . 'login');
            exit;
        }

        // Carrega dados para o sidebar da empresa não quebrar
        $estabelecimentoModel = new EstabelecimentoModel();
        $this->viewData['empresa'] = $estabelecimentoModel->getById($this->user['estabelecimento_id']);
        $this->viewData['usuario'] = $this->user;
    }

    public function index()
    {
        header('Location: ' . baseUrl() . 'mensagem/chat');
        exit;
    }

    public function chat($id_conversa = null)
    {
        $conversaModel = new ConversaModel();
        $mensagemModel = new MensagemModel();
        $id_empresa = $this->user['usuario_id'];

        // Busca as conversas da empresa com o nome correto do candidato
        $conversas = $conversaModel->db
            ->select('c.id as id_conversa, pf.nome as nome_candidato, c.id_candidato')
            ->table('conversa c')
            ->join('usuario u', 'c.id_candidato = u.usuario_id')
            ->join('pessoa_fisica pf', 'u.pessoa_fisica_id = pf.pessoa_fisica_id')
            ->where('c.id_empresa', $id_empresa)
            ->findAll();

        $mensagens = [];
        $id_conversa_ativa = $id_conversa;
        $nome_destinatario = 'Selecione uma conversa';

        if ($id_conversa_ativa) {
            // Valida se a empresa tem permissão para ver esta conversa
            $conversaValida = $conversaModel->db->table('conversa')
                ->where('id', $id_conversa_ativa)
                ->where('id_empresa', $id_empresa)
                ->first();
            
            if ($conversaValida) {
                // Busca as mensagens da conversa ativa
                $mensagens = $mensagemModel->db->table('mensagem')
                    ->where('id_conversa', $id_conversa_ativa)
                    ->orderBy('data_envio', 'ASC')
                    ->findAll();
                
                // Pega o nome do candidato para o cabeçalho do chat
                foreach($conversas as $c) {
                    if ($c['id_conversa'] == $id_conversa_ativa) {
                        $nome_destinatario = $c['nome_candidato'];
                        break;
                    }
                }
            } else {
                $id_conversa_ativa = null;
            }
        }

        // Junta os dados do sidebar com os dados do chat
        $dadosParaView = array_merge($this->viewData, [
            'conversas' => $conversas,
            'mensagens' => $mensagens,
            'id_conversa_ativa' => $id_conversa_ativa,
            'nome_destinatario' => $nome_destinatario
        ]);

        // Carrega a view correta da empresa
        $this->loadView('empresa/mensagens', $dadosParaView, false);
    }

    public function enviar()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id_empresa = $this->user['usuario_id'];
            $id_conversa = $_POST['id_conversa'];

            $conversaModel = new ConversaModel();
            $conversaValida = $conversaModel->db->table('conversa')
                ->where('id', $id_conversa)
                ->where('id_empresa', $id_empresa)
                ->first();

            if (!$conversaValida) {
                header('Location: ' . baseUrl() . 'mensagem/chat');
                exit;
            }

            $mensagemModel = new MensagemModel();
            $dados = [
                'id_conversa' => $id_conversa,
                'id_remetente' => $id_empresa,
                'id_destinatario' => $_POST['id_destinatario'],
                'mensagem' => trim($_POST['mensagem']),
                'data_envio' => date('Y-m-d H:i:s')
            ];

            if (!empty($dados['mensagem'])) {
                $mensagemModel->db->table('mensagem')->insert($dados);
            }

            header('Location: ' . baseUrl() . 'mensagem/chat/' . $id_conversa);
            exit;
        }
    }
}
