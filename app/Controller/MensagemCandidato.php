<?php

namespace App\Controller;

use Core\Library\Session;
use Core\Library\Redirect;
use App\Model\ConversaModel;
use App\Model\MensagemModel;

class MensagemCandidato extends CandidatoBaseController
{

    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        header('Location: ' . baseUrl() . 'mensagemCandidato/chat');
        exit;
    }

    public function iniciarConversa($id_empresa)
    {
        $id_candidato = $this->usuarioLogado['usuario_id'];
        $id_empresa = (int) $id_empresa;

        if ($id_empresa <= 0) {
            header('Location: ' . baseUrl() . 'mensagemCandidato/chat');
            exit;
        }

        $conversaModel = $this->loadModel("Conversa");

        $conversa = $conversaModel->db
            ->table('conversa') 
            ->where('id_candidato', $id_candidato)
            ->where('id_empresa', $id_empresa)
            ->first();

        if ($conversa) {
            header('Location: ' . baseUrl() . 'mensagemCandidato/chat/' . $conversa['id']);
            exit;
        } else {
            $dadosNovaConversa = [
                'id_candidato' => $id_candidato,
                'id_empresa'   => $id_empresa,
                'data_criacao' => date('Y-m-d H:i:s')
            ];

            $novoId = $conversaModel->db->table('conversa')->insert($dadosNovaConversa);

            if ($novoId > 0) {
                header('Location: ' . baseUrl() . 'mensagemCandidato/chat/' . $novoId);
                exit;
            } else {
                Session::set('flash_msg', ['mensagem' => 'ERRO: Falha ao iniciar a conversa.', 'tipo' => 'error']);
                header('Location: ' . baseUrl() . 'vagas');
                exit;
            }
        }
    }

    public function chat($id_conversa = null)
    {
        $conversaModel = $this->loadModel("Conversa");
        $mensagemModel = $this->loadModel("Mensagem");
    
        $id_candidato = $this->usuarioLogado['usuario_id'];

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
                ->table('conversa')
                ->where('id', $id_conversa_ativa)
                ->where('id_candidato', $id_candidato)
                ->first();

            if ($conversaValida) {
                $mensagens = $mensagemModel->db
                    ->table('mensagem')
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

        // 9. $this->viewData
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

            $conversaModel = $this->loadModel("Conversa");
            $conversaValida = $conversaModel->db
                ->table('conversa')
                ->where('id', $id_conversa)
                ->where('id_candidato', $this->usuarioLogado['usuario_id']) 
                ->first();

            if (!$conversaValida) {
                header('Location: ' . baseUrl() . 'mensagemCandidato/chat');
                exit;
            }

            $mensagemModel = $this->loadModel("Mensagem");
            $dados = [
                'id_conversa' => $id_conversa,
                'id_remetente' => $this->usuarioLogado['usuario_id'], 
                'id_destinatario' => $_POST['id_destinatario'], 
                'mensagem' => $_POST['mensagem'],
                'data_envio' => date('Y-m-d H:i:s')
            ];

            $mensagemModel->db->table('mensagem')->insert($dados);

            header('Location: ' . baseUrl() . 'mensagemCandidato/chat/' . $id_conversa);
            exit;
        }
    }
}