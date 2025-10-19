<?php

namespace App\Controller;

use Core\Library\ControllerMain;
use Core\Library\Session;
use Core\Library\Redirect;
use Core\Library\Files;
use App\Model\PessoaFisicaModel;
use App\Model\VagaCurriculumModel;
use App\Model\VagaModel;
use App\Model\EstabelecimentoModel;
use App\Model\CurriculumModel;
use App\Model\CidadeModel;
use App\Model\CurriculumEscolaridadeModel;
use App\Model\CurriculumExperienciaModel;
use App\Model\CurriculumQualificacaoModel;
use App\Model\TelefoneModel;
use App\Model\EscolaridadeModel;
use App\Model\CargoModel;
use App\Model\UsuarioModel;
use App\Model\NotificacaoModel;
use App\Service\NotificationService;

class Candidatos extends ControllerMain
{
    private $dados;
    private $pessoaFisicaId;
    private $curriculumId;

    public function __construct()
    {
        $this->auxiliarConstruct();

        $usuarioLogado = Session::get('usuario_logado');

        if (empty($usuarioLogado) || !isset($usuarioLogado['pessoa_fisica_id'])) {
            Redirect::page('login');
            return;
        }

        $this->pessoaFisicaId = $usuarioLogado['pessoa_fisica_id'];

        $pessoaFisicaModel = new PessoaFisicaModel();
        $pessoaFisicaDados = $pessoaFisicaModel->getById($this->pessoaFisicaId);

        $this->dados['usuario'] = $pessoaFisicaDados
            ? array_merge($usuarioLogado, $pessoaFisicaDados)
            : $usuarioLogado;

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

        if ($this->curriculumId) {
            $this->dados['escolaridades'] = (new CurriculumEscolaridadeModel())->getByCurriculumId($this->curriculumId);
            $this->dados['experiencias'] = (new CurriculumExperienciaModel())->getByCurriculumId($this->curriculumId);
            $this->dados['qualificacoes'] = (new CurriculumQualificacaoModel())->getByCurriculumId($this->curriculumId);
        } else {
            $this->dados['escolaridades'] = [];
            $this->dados['experiencias'] = [];
            $this->dados['qualificacoes'] = [];
        }

        $notificacaoModel = new NotificacaoModel();
        $this->dados['unread_notifications'] = $notificacaoModel->countUnread($this->pessoaFisicaId);

        $this->dados['curriculum'] = $curriculum;
        $this->dados['progresso_perfil'] = $this->calcularProgressoPerfil();
    }

    private function calcularProgressoPerfil(): int
    {
        $progresso = 0;
        $totalCampos = 6; 

        if (!empty($this->dados['curriculum']['foto']) && $this->dados['curriculum']['foto'] !== 'default.png') {
            $progresso++;
        }
        if (!empty($this->dados['curriculum']['apresentacaoPessoal'])) {
            $progresso++;
        }
        if (!empty($this->dados['curriculum']['cidade_id'])) { 
            $progresso++;
        }
        if (!empty($this->dados['escolaridades'])) {
            $progresso++;
        }
        if (!empty($this->dados['experiencias'])) {
            $progresso++;
        }
        if (!empty($this->dados['qualificacoes'])) {
            $progresso++;
        }

        return ($totalCampos > 0) ? round(($progresso / $totalCampos) * 100) : 0;
    }

    public function index()
    {
        $vagaCurriculumModel = new VagaCurriculumModel();
        $candidaturas = $vagaCurriculumModel->getCandidaturasPorPessoaFisicaId($this->pessoaFisicaId);

        $this->dados['stats'] = [
            'candidaturas' => count($candidaturas)
        ];

        $candidaturasRecentes = [];
        if (!empty($candidaturas)) {
            $vagaModel = new VagaModel();

            foreach (array_slice($candidaturas, 0, 5) as $candidatura) {
                $vaga = $vagaModel->findCompletoById($candidatura['vaga_id']);
                
                $candidaturasRecentes[] = [
                    'candidatura' => $candidatura,
                    'vaga'        => $vaga,
                ];
            }
        }

        $this->dados['candidaturasRecentes'] = $candidaturasRecentes;

        $this->loadView("candidatos/index", $this->dados, false);
    }

    public function configuracoes()
    {
        $this->loadView("candidatos/configuracoes", $this->dados, false);
    }

    public function perfil()
    {
        $this->loadView("candidatos/perfil", $this->dados, false);
    }
    
    public function salvarConfiguracoes()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            Redirect::page('candidatos/configuracoes');
            return;
        }

        $nomeCompleto = trim($_POST['nome_completo'] ?? '');
        $pessoaFisicaModel = new PessoaFisicaModel();

        if ($pessoaFisicaModel->updateNome($this->pessoaFisicaId, $nomeCompleto)) {
            $partesNome = explode(' ', $nomeCompleto, 2);
            $usuarioLogado = Session::get('usuario_logado');
            $usuarioLogado['nome'] = $partesNome[0] ?? '';
            $usuarioLogado['sobrenome'] = $partesNome[1] ?? '';
            Session::set('usuario_logado', $usuarioLogado);
            Session::set('mensagem_sucesso', 'Seus dados foram atualizados com sucesso!');
        } else {
            $errors = Session::get('form_errors');
            if (!empty($errors)) {
                $errorMessages = [];
                foreach ($errors as $field => $message) {
                    $errorMessages[] = $message;
                }
                Session::set('mensagem_erro', implode(' ', $errorMessages));
                Session::destroy('form_errors');
            } else {
                Session::set('mensagem_erro', 'Ocorreu um erro ao salvar suas informações.');
            }
        }
        
        Redirect::page('candidatos/configuracoes');
    }

    public function alterarSenha()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            Redirect::page('candidatos/configuracoes');
            return;
        }

        Session::destroy('mensagem_erro');
        Session::destroy('mensagem_sucesso');

        $senhaAtual = $_POST['senha_atual'] ?? '';
        $novaSenha = $_POST['nova_senha'] ?? '';
        $confirmaSenha = $_POST['confirma_senha'] ?? '';

        if (empty($senhaAtual) || empty($novaSenha) || empty($confirmaSenha)) {
            Session::set('mensagem_erro', 'Todos os campos de senha são obrigatórios.');
            Redirect::page('candidatos/configuracoes');
            return;
        }

        if ($novaSenha !== $confirmaSenha) {
            Session::set('mensagem_erro', 'A nova senha e a confirmação não correspondem.');
            Redirect::page('candidatos/configuracoes');
            return;
        }

        $usuarioLogado = Session::get('usuario_logado');
        if (empty($usuarioLogado['usuario_id'])) {
            Session::set('mensagem_erro', 'Sua sessão expirou. Por favor, faça login novamente.');
            Redirect::page('login');
            return;
        }
        $usuarioId = $usuarioLogado['usuario_id'];

        $usuarioModel = new UsuarioModel();
        $usuario = $usuarioModel->getById($usuarioId);

        if (!$usuario || !password_verify($senhaAtual, $usuario['senha'])) {
            Session::set('mensagem_erro', 'A senha atual está incorreta.');
            Redirect::page('candidatos/configuracoes');
            return;
        }

        if ($usuarioModel->updatePassword($usuarioId, $novaSenha)) {
            (new NotificationService())->notificaSenhaAlterada($this->pessoaFisicaId);
            Session::set('mensagem_sucesso', 'Senha alterada com sucesso!');
        } else {
            Session::set('mensagem_erro', 'Ocorreu um erro ao alterar sua senha. Tente novamente.');
        }

        Redirect::page('candidatos/configuracoes');
    }

    public function curriculo()
    {
        $this->dados['niveis_escolaridade'] = (new CurriculumEscolaridadeModel())->getNiveisEscolaridade();
        $this->dados['cargos'] = (new CargoModel())->listarTodos();

        $this->loadView("candidatos/curriculo", $this->dados, false);
    }

    public function salvarCurriculo()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $postData = $_POST;
            $postData['pessoa_fisica_id'] = $this->pessoaFisicaId;
            $postData['email'] = $this->dados['usuario']['login'];
            
            $curriculumModel = new CurriculumModel();
            if ($curriculumModel->salvar($postData)) {
                Session::set('mensagem_sucesso', 'Currículo salvo com sucesso!');
            } else {
                $errors = Session::get('errors') ?? [];
                $mensagem = 'Erro ao salvar o currículo: ';
                $mensagem .= is_array($errors) ? implode(', ', $errors) : 'Erro desconhecido.';
                Session::set('mensagem_erro', $mensagem);
            }
        }

        Redirect::page('candidatos/curriculo');
    }
    
    public function salvarFoto()
    {
        // 1. Definir o cabeçalho da resposta como JSON desde o início.
        header('Content-Type: application/json');

        // 2. Validações iniciais (sem Redirect, apenas com resposta JSON)
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Método não permitido.']);
            exit;
        }

        if (empty($this->curriculumId)) {
            echo json_encode(['success' => false, 'message' => 'Você precisa salvar seus dados principais primeiro.']);
            exit;
        }

        if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
            $curriculumModel = new CurriculumModel();
            $curriculum = $curriculumModel->find($this->curriculumId);
            $fotoAntiga = $curriculum['foto'] ?? '';

            // Mantém sua lógica de upload de arquivos
            $uploadPath = 'uploads' . DIRECTORY_SEPARATOR;
            $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
            $maxSizeMB = 2;
            $subfolder = 'fotos_perfil';

            $fileHandler = new Files($uploadPath, $allowedTypes, $maxSizeMB);
            $uploadResult = $fileHandler->upload([$_FILES['foto']], $subfolder, $fotoAntiga);

            if ($uploadResult) {
                $novoNomeFoto = $uploadResult[0];
                if ($curriculumModel->updateFoto($this->curriculumId, $novoNomeFoto)) {
                    // 3. Resposta de sucesso em JSON
                    echo json_encode(['success' => true, 'message' => 'Foto de perfil atualizada com sucesso!']);
                } else {
                    // Erro ao salvar no banco
                    $fileHandler->delete($novoNomeFoto, $subfolder);
                    echo json_encode(['success' => false, 'message' => 'Erro ao atualizar a foto no banco de dados.']);
                }
            } else {
                // Erro no upload do arquivo
                $errorMsg = Session::get('msgError');
                Session::destroy('msgError');
                echo json_encode(['success' => false, 'message' => $errorMsg ?: 'Ocorreu um erro ao enviar a imagem.']);
            }
        } else {
            // Arquivo não enviado ou com erro
            echo json_encode(['success' => false, 'message' => 'Nenhum arquivo foi enviado ou ocorreu um erro no upload.']);
        }

        // 4. Garante que nada mais seja executado
        exit;
    }

    public function salvarEscolaridade()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (empty($this->curriculumId)) {
                Session::set('mensagem_erro', 'Você precisa salvar seus dados principais primeiro.');
                Redirect::page('candidatos/curriculo');
                return;
            }

            $postData = $_POST;
            $postData['curriculum_id'] = $this->curriculumId;

            $nomeCidade = trim($postData['cidade'] ?? '');
            $uf = strtoupper(trim($postData['uf'] ?? ''));
            if (!empty($nomeCidade) && !empty($uf)) {
                $cidadeModel = new CidadeModel();
                $cidadeData = $cidadeModel->getByCidadeAndUf($nomeCidade, $uf);
                if ($cidadeData) {
                    $postData['cidade_id'] = $cidadeData['cidade_id'];
                } else {
                    $postData['cidade_id'] = $cidadeModel->insert(['cidade' => $nomeCidade, 'uf' => $uf]);
                }
            }
            unset($postData['cidade'], $postData['uf']);

            $model = new CurriculumEscolaridadeModel();
            if ($model->salvar($postData)) {
                Session::set('mensagem_sucesso', 'Formação salva com sucesso!');
            } else {
                Session::set('mensagem_erro', 'Erro ao salvar formação. Verifique os dados.');
            }
        }
        Redirect::page('candidatos/curriculo');
    }

    public function salvarExperiencia()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
             if (empty($this->curriculumId)) {
                Session::set('mensagem_erro', 'Você precisa salvar seus dados principais primeiro.');
                Redirect::page('candidatos/curriculo');
                return;
            }
            $postData = $_POST;
            $postData['curriculum_id'] = $this->curriculumId; 

            $model = new CurriculumExperienciaModel();
            if ($model->salvar($postData)) {
                Session::set('mensagem_sucesso', 'Experiência salva com sucesso!');
            } else {
                Session::set('mensagem_erro', 'Erro ao salvar experiência.');
            }
        }
        Redirect::page('candidatos/curriculo');
    }

    public function salvarQualificacao()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
             if (empty($this->curriculumId)) {
                Session::set('mensagem_erro', 'Você precisa salvar seus dados principais primeiro.');
                Redirect::page('candidatos/curriculo');
                return;
            }
            $postData = $_POST;
            $postData['curriculum_id'] = $this->curriculumId;

            $model = new CurriculumQualificacaoModel();
            if ($model->salvar($postData)) {
                Session::set('mensagem_sucesso', 'Qualificação salva com sucesso!');
            } else {
                Session::set('mensagem_erro', 'Erro ao salvar qualificação.');
            }
        }
        Redirect::page('candidatos/curriculo');
    }

    public function minhasCandidaturas()
    {
        $vagaCurriculumModel = new VagaCurriculumModel();
        $candidaturas = $vagaCurriculumModel->getCandidaturasPorPessoaFisicaId($this->pessoaFisicaId);

        $this->dados['candidaturas'] = [];

        if (!empty($candidaturas)) {
            $vagaModel = new VagaModel();

            foreach ($candidaturas as $candidatura) {
                $vaga = $vagaModel->findCompletoById($candidatura['vaga_id']);
                
                $this->dados['candidaturas'][] = [
                    'candidatura' => $candidatura,
                    'vaga'        => $vaga,
                ];
            }
        }

        $this->loadView("candidatos/minhasCandidaturas", $this->dados, false);
    }

    public function notificacoes()
    {
        $notificacaoModel = new \App\Model\NotificacaoModel();
        $this->dados['notificacoes'] = $notificacaoModel->getByPessoaFisicaId($this->pessoaFisicaId);
        $notificacaoModel->markAllAsRead($this->pessoaFisicaId);
        $this->dados['unread_notifications'] = 0;
        $this->loadView("candidatos/notificacoes", $this->dados, false);
    }

    public function excluirEscolaridade($id)
    {
        $model = new CurriculumEscolaridadeModel();
        $item = $model->find($id);

        if ($item && $item['curriculum_curriculum_id'] == $this->curriculumId) {
            if ($model->delete($id)) {
                Session::set('mensagem_sucesso', 'Formação excluída com sucesso!');
            } else {
                Session::set('mensagem_erro', 'Erro ao excluir a formação.');
            }
        } else {
            Session::set('mensagem_erro', 'Você não tem permissão para excluir este item.');
        }
        Redirect::page('candidatos/curriculo');
    }

    public function excluirExperiencia($id)
    {
        $model = new CurriculumExperienciaModel();
        $item = $model->find($id);
        
        if ($item && $item['curriculum_id'] == $this->curriculumId) { 
            if ($model->delete($id)) {
                Session::set('mensagem_sucesso', 'Experiência excluída com sucesso!');
            } else {
                Session::set('mensagem_erro', 'Erro ao excluir a experiência.');
            }
        } else {
            Session::set('mensagem_erro', 'Você não tem permissão para excluir este item.');
        }
        Redirect::page('candidatos/curriculo');
    }

    public function excluirQualificacao($id)
    {
        $model = new CurriculumQualificacaoModel();
        $item = $model->find($id);

        if ($item && $item['curriculum_id'] == $this->curriculumId) {
            if ($model->delete($id)) {
                Session::set('mensagem_sucesso', 'Qualificação excluída com sucesso!');
            } else {
                Session::set('mensagem_erro', 'Erro ao excluir a qualificação.');
            }
        } else {
            Session::set('mensagem_erro', 'Você não tem permissão para excluir este item.');
        }
        Redirect::page('candidatos/curriculo');
    }

    public function getExperiencia($id)
    {
        $model = new CurriculumExperienciaModel();
        $experiencia = $model->getById($id);

        if ($experiencia && $experiencia['curriculum_id'] == $this->curriculumId) { 
            header('Content-Type: application/json');
            echo json_encode($experiencia);
        } else {
            http_response_code(404);
            echo json_encode(['erro' => 'Experiência não encontrada ou não autorizada.']);
        }
        exit;
    }
}