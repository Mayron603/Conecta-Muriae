<?php

namespace App\Controller;

use Core\Library\Session;
use App\Model\ConversaModel;
use App\Model\MensagemModel;
use App\Model\UsuarioModel;

class Mensagem extends EmpresaBaseController
{

    public function __construct()
    {
        parent::__construct();
        
    }

    public function iniciarConversa($params)
    {
        $curriculum_id = $params[0] ?? null;
        if (!$curriculum_id) {
            Session::set('flash_msg', ['mensagem' => 'ID do currículo não fornecido.', 'tipo' => 'error']);
            header('Location: ' . baseUrl() . 'empresa/candidatos');
            exit;
        }

        $curriculumModel = $this->loadModel("Curriculum");
        $curriculo = $curriculumModel->find($curriculum_id);

        if (!$curriculo || empty($curriculo['pessoa_fisica_id'])) {
            Session::set('flash_msg', ['mensagem' => 'Candidato não encontrado (Erro C).', 'tipo' => 'error']);
            header('Location: ' . baseUrl() . 'empresa/candidatos');
            exit;
        }

        $usuarioModel = $this->loadModel("Usuario");
        $usuarioCandidato = $usuarioModel->db->table('usuario')
                                ->where('pessoa_fisica_id', $curriculo['pessoa_fisica_id'])
                                ->first();

        if (!$usuarioCandidato || empty($usuarioCandidato['usuario_id'])) {
            Session::set('flash_msg', ['mensagem' => 'Candidato não encontrado (Erro U).', 'tipo' => 'error']);
            header('Location: ' . baseUrl() . 'empresa/candidatos');
            exit;
        }

        $id_candidato = $usuarioCandidato['usuario_id'];
        
        $id_usuario_empresa_logado = $this->usuarioLogado['usuario_id'];
        $id_estabelecimento = $this->estabelecimentoId; 

        $usuarios_da_empresa = $usuarioModel->db->table('usuario')->where('estabelecimento_id', $id_estabelecimento)->findAll();
        $ids_usuarios_empresa = array_column($usuarios_da_empresa, 'usuario_id');

        $conversaModel = $this->loadModel("Conversa");
        $conversa = null;

        if (!empty($ids_usuarios_empresa)) {
            $conversa = $conversaModel->db
                ->table('conversa')
                ->where('id_candidato', $id_candidato)
                ->whereIn('id_empresa', $ids_usuarios_empresa)
                ->orderBy('id', 'ASC')
                ->first();
        }

        if ($conversa) {
            header('Location: ' . baseUrl() . 'mensagem/chat/' . $conversa['id']);
            exit;
        } else {
            $dadosNovaConversa = [
                'id_candidato' => $id_candidato,
                'id_empresa'   => $id_usuario_empresa_logado,
                'data_criacao' => date('Y-m-d H:i:s')
            ];

            $novoId = $conversaModel->db->table('conversa')->insert($dadosNovaConversa);

            if ($novoId > 0) {
                header('Location: ' . baseUrl() . 'mensagem/chat/' . $novoId);
                exit;
            } else {
                Session::set('flash_msg', ['mensagem' => 'Falha ao iniciar a conversa.', 'tipo' => 'error']);
                header('Location: ' . baseUrl() . 'empresa/candidatos');
                exit;
            }
        }
    }

    public function index()
    {
        header('Location: ' . baseUrl() . 'mensagem/chat');
        exit;
    }

    public function chat($id_conversa = null)
    {
        $conversaModel = $this->loadModel("Conversa");
        $mensagemModel = $this->loadModel("Mensagem");
        
        $id_empresa = $this->usuarioLogado['usuario_id'];

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
            $conversaValida = $conversaModel->db->table('conversa')
                ->where('id', $id_conversa_ativa)
                ->where('id_empresa', $id_empresa)
                ->first();
            
            if ($conversaValida) {
                $mensagens = $mensagemModel->db->table('mensagem')
                    ->where('id_conversa', $id_conversa_ativa)
                    ->orderBy('data_envio', 'ASC')
                    ->findAll();
                
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

        $dadosParaView = array_merge($this->viewData, [
            'conversas' => $conversas,
            'mensagens' => $mensagens,
            'id_conversa_ativa' => $id_conversa_ativa,
            'nome_destinatario' => $nome_destinatario
        ]);

        $this->loadView('empresa/mensagens', $dadosParaView, false);
    }

    public function enviar()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id_empresa = $this->usuarioLogado['usuario_id'];
            $id_conversa = $_POST['id_conversa'];

            $conversaModel = $this->loadModel("Conversa");
            $conversaValida = $conversaModel->db->table('conversa')
                ->where('id', $id_conversa)
                ->where('id_empresa', $id_empresa)
                ->first();

            if (!$conversaValida) {
                header('Location: ' . baseUrl() . 'mensagem/chat');
                exit;
            }

            $mensagemModel = $this->loadModel("Mensagem");
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