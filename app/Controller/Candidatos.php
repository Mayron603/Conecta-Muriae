<?php

namespace App\Controller;

use Core\Library\Session;
use Core\Library\Redirect;
use Core\Library\Files;
use Core\Library\Validator;
use App\Model\PessoaFisicaModel;
use App\Model\VagaCurriculumModel;
use App\Model\VagaModel;
use App\Model\CurriculumModel;
use App\Model\CidadeModel;
use App\Model\CurriculumEscolaridadeModel;
use App\Model\CurriculumExperienciaModel;
use App\Model\CurriculumQualificacaoModel;
use App\Model\CargoModel;
use App\Model\UsuarioModel;
use App\Model\NotificacaoModel;
use App\Service\NotificationService;

class Candidatos extends CandidatoBaseController
{

    public function __construct()
    {
        parent::__construct();
    }


    public function index()
    {
        $vagaCurriculumModel = $this->loadModel("VagaCurriculum");
        
        $candidaturas = [];
        if ($this->curriculumId) {
            $candidaturas = $vagaCurriculumModel->getCandidaturasPorCurriculumId($this->curriculumId);
        }

        $this->viewData['stats'] = [
            'candidaturas' => count($candidaturas)
        ];

        $candidaturasRecentes = [];
        if (!empty($candidaturas)) {
            $vagaModel = $this->loadModel("Vaga");

            foreach (array_slice($candidaturas, 0, 5) as $candidatura) {
                $vaga = $vagaModel->findCompletoById($candidatura['vaga_id']);
                
                $candidaturasRecentes[] = [
                    'candidatura' => $candidatura,
                    'vaga'        => $vaga,
                ];
            }
        }

        $this->viewData['candidaturasRecentes'] = $candidaturasRecentes;

        $this->loadView("candidatos/index", $this->viewData, false);
    }

    public function configuracoes()
    {
        $this->loadView("candidatos/configuracoes", $this->viewData, false);
    }

    public function perfil()
    {
        $this->loadView("candidatos/perfil", $this->viewData, false);
    }
    
    public function salvarConfiguracoes()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            Redirect::page('candidatos/configuracoes');
            return;
        }

        $nomeCompleto = trim($_POST['nome_completo'] ?? '');
        $pessoaFisicaModel = $this->loadModel("PessoaFisica");

        $dadosParaValidar = ['nome' => $nomeCompleto];
        if (Validator::make($dadosParaValidar, $pessoaFisicaModel->validationRules)) {
            Session::set('mensagem_erro', 'Erro ao salvar: O nome deve ter pelo menos 5 caracteres.');
            Redirect::page('candidatos/configuracoes');
            return; 
        }

        if ($pessoaFisicaModel->updateNome($this->pessoaFisicaId, $nomeCompleto)) {
            $partesNome = explode(' ', $nomeCompleto, 2);
            
            $usuarioLogado = Session::get('usuario_logado');
            $usuarioLogado['nome'] = $partesNome[0] ?? '';
            $usuarioLogado['sobrenome'] = $partesNome[1] ?? '';
            Session::set('usuario_logado', $usuarioLogado);
            
            Session::set('mensagem_sucesso', 'Seus dados foram atualizados com sucesso!');
        } else {
            Session::set('mensagem_erro', 'Ocorreu um erro de banco de dados ao salvar suas informações.');
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
        
        if (empty($this->usuarioLogado['usuario_id'])) { 
            Session::set('mensagem_erro', 'Sua sessão expirou. Por favor, faça login novamente.');
            Redirect::page('login');
            return;
        }
        $usuarioId = $this->usuarioLogado['usuario_id'];

        $usuarioModel = $this->loadModel("Usuario");
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
        $this->viewData['niveis_escolaridade'] = $this->loadModel("CurriculumEscolaridade")->getNiveisEscolaridade();
        $this->viewData['cargos'] = $this->loadModel("Cargo")->listarTodos();

        $this->loadView("candidatos/curriculo", $this->viewData, false);
    }

    public function salvarCurriculo()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $postData = $_POST;
            $postData['pessoa_fisica_id'] = $this->pessoaFisicaId;
            $postData['email'] = $this->viewData['usuario']['login']; 

            $curriculumModel = $this->loadModel("Curriculum");
            
            $cidadeNome = trim($postData['cidade'] ?? '');
            $uf = strtoupper(trim($postData['uf'] ?? ''));
            $cidadeId = null;
            
            if (empty($cidadeNome) || empty($uf)) {
                Session::set('mensagem_erro', 'Os campos Cidade e UF são obrigatórios.');
                Redirect::page('candidatos/curriculo');
                return;
            }

            $cidadeModel = $this->loadModel("Cidade");
            $cidadeData = $cidadeModel->getByCidadeAndUf($cidadeNome, $uf);

            if ($cidadeData) {
                $cidadeId = $cidadeData['cidade_id'];
            } else {
                $cidadeId = $cidadeModel->insert([
                    'cidade' => $cidadeNome,
                    'uf'     => $uf
                ]);
            }

            if (empty($cidadeId)) {
                Session::set('mensagem_erro', 'Não foi possível encontrar ou criar a cidade especificada.');
                Redirect::page('candidatos/curriculo');
                return;
            }

            $postData['cidade_id'] = $cidadeId;
            unset($postData['cidade'], $postData['uf']);

            // 2. Lógica de Validação
            if (Validator::make($postData, $curriculumModel->validationRules)) {
                Session::set('mensagem_erro', 'Erro ao salvar: Verifique os campos obrigatórios.');
                Redirect::page('candidatos/curriculo');
                return;
            }
            
            // 3. Salvar os dados "limpos"
            if ($curriculumModel->salvar($postData)) {
                Session::set('mensagem_sucesso', 'Currículo salvo com sucesso!');
            } else {
                Session::set('mensagem_erro', 'Ocorreu um erro ao salvar o currículo no banco de dados.');
            }
        }

        Redirect::page('candidatos/curriculo');
    }
    
    public function salvarFoto()
    {
        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Método não permitido.']);
            exit;
        }

        if (empty($this->curriculumId)) {
            echo json_encode(['success' => false, 'message' => 'Você precisa salvar seus dados principais primeiro.']);
            exit;
        }

        if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
            $curriculumModel = $this->loadModel("Curriculum");
            $curriculum = $curriculumModel->find($this->curriculumId);
            $fotoAntiga = $curriculum['foto'] ?? '';

            $uploadPath = 'uploads' . DIRECTORY_SEPARATOR;
            $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
            $maxSizeMB = 2;
            $subfolder = 'fotos_perfil';

            $fileHandler = new Files($uploadPath, $allowedTypes, $maxSizeMB);
            $uploadResult = $fileHandler->upload([$_FILES['foto']], $subfolder, $fotoAntiga);

            if ($uploadResult) {
                $novoNomeFoto = $uploadResult[0];
                if ($curriculumModel->updateFoto($this->curriculumId, $novoNomeFoto)) {
                    echo json_encode(['success' => true, 'message' => 'Foto de perfil atualizada com sucesso!']);
                } else {
                    $fileHandler->delete($novoNomeFoto, $subfolder);
                    echo json_encode(['success' => false, 'message' => 'Erro ao atualizar a foto no banco de dados.']);
                }
            } else {
                $errorMsg = Session::get('msgError');
                Session::destroy('msgError');
                echo json_encode(['success' => false, 'message' => $errorMsg ?: 'Ocorreu um erro ao enviar a imagem.']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Nenhum arquivo foi enviado ou ocorreu um erro no upload.']);
        }

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
                $cidadeModel = $this->loadModel("Cidade");
                $cidadeData = $cidadeModel->getByCidadeAndUf($nomeCidade, $uf);
                if ($cidadeData) {
                    $postData['cidade_id'] = $cidadeData['cidade_id'];
                } else {
                    $postData['cidade_id'] = $cidadeModel->insert(['cidade' => $nomeCidade, 'uf' => $uf]);
                }
            }
            unset($postData['cidade'], $postData['uf']);

            $model = $this->loadModel("CurriculumEscolaridade");
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

            $model = $this->loadModel("CurriculumExperiencia");
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

            $model = $this->loadModel("CurriculumQualificacao");
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
        $vagaCurriculumModel = $this->loadModel("VagaCurriculum");
        
        $candidaturas = [];
        if ($this->curriculumId) {
            $candidaturas = $vagaCurriculumModel->getCandidaturasPorCurriculumId($this->curriculumId);
        }

        $this->viewData['candidaturas'] = [];

        if (!empty($candidaturas)) {
            $vagaModel = $this->loadModel("Vaga");

            foreach ($candidaturas as $candidatura) {
                $vaga = $vagaModel->findCompletoById($candidatura['vaga_id']);
                
                $this->viewData['candidaturas'][] = [
                    'candidatura' => $candidatura,
                    'vaga'        => $vaga,
                ];
            }
        }

        $this->loadView("candidatos/minhasCandidaturas", $this->viewData, false);
    }

    public function notificacoes()
    {
        $notificacaoModel = $this->loadModel("Notificacao");
        $this->viewData['notificacoes'] = $notificacaoModel->getByPessoaFisicaId($this->pessoaFisicaId);
        $notificacaoModel->markAllAsRead($this->pessoaFisicaId);
        $this->viewData['unread_notifications'] = 0; // Zera a contagem na view
        $this->loadView("candidatos/notificacoes", $this->viewData, false);
    }

    public function excluirEscolaridade($id)
    {
        $model = $this->loadModel("CurriculumEscolaridade");
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
        $model = $this->loadModel("CurriculumExperiencia");
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
        $model = $this->loadModel("CurriculumQualificacao");
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
        $model = $this->loadModel("CurriculumExperiencia");
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


    public function uploadCurriculo()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            Redirect::page('candidatos/curriculo');
            return;
        }

        if (empty($this->curriculumId)) {
            Session::set('mensagem_erro', 'Você precisa salvar seus dados principais antes de enviar um arquivo.');
            Redirect::page('candidatos/curriculo');
            return;
        }

        if (!isset($_FILES['curriculo_arquivo']) || $_FILES['curriculo_arquivo']['error'] !== UPLOAD_ERR_OK) {
            Session::set('mensagem_erro', 'Nenhum arquivo foi enviado ou ocorreu um erro no upload.');
            Redirect::page('candidatos/curriculo');
            return;
        }

        $curriculumModel = $this->loadModel("Curriculum");
        $curriculum = $curriculumModel->find($this->curriculumId);
        $arquivoAntigo = $curriculum['arquivo_curriculo'] ?? '';

        $uploadPath = 'uploads' . DIRECTORY_SEPARATOR;
        $allowedTypes = ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document']; 
        $maxSizeMB = 5;
        $subfolder = 'curriculos';

        $fileHandler = new Files($uploadPath, $allowedTypes, $maxSizeMB);
        
        $uploadResult = $fileHandler->upload([$_FILES['curriculo_arquivo']], $subfolder, $arquivoAntigo);

        if ($uploadResult) {
            $novoNomeArquivo = $uploadResult[0];
            
            if ($curriculumModel->updateArquivoCurriculo($this->curriculumId, $novoNomeArquivo)) {
                Session::set('mensagem_sucesso', 'Arquivo do currículo atualizado com sucesso!');
            } else {
                $fileHandler->delete($novoNomeArquivo, $subfolder);
                Session::set('mensagem_erro', 'Erro ao salvar as informações do arquivo no banco de dados.');
            }
        } else {
            if (!Session::get('msgError')) {
                 Session::set('mensagem_erro', 'Ocorreu um erro ao enviar o arquivo. Verifique o tipo (PDF, DOC, DOCX) e o tamanho (máx 5MB).');
            } else {
                 Session::set('mensagem_erro', Session::get('msgError'));
                 Session::destroy('msgError');
            }
        }

        Redirect::page('candidatos/curriculo');
    }

    public function excluirCurriculo()
    {
        if (empty($this->curriculumId)) {
            Session::set('mensagem_erro', 'Currículo não encontrado.');
            Redirect::page('candidatos/curriculo');
            return;
        }

        $curriculumModel = $this->loadModel("Curriculum");
        $curriculum = $curriculumModel->find($this->curriculumId);
        $arquivoParaDeletar = $curriculum['arquivo_curriculo'] ?? '';

        if (empty($arquivoParaDeletar)) {
            Session::set('mensagem_sucesso', 'Nenhum arquivo para excluir.');
            Redirect::page('candidatos/curriculo');
            return;
        }

        $uploadPath = 'uploads' . DIRECTORY_SEPARATOR;
        $subfolder = 'curriculos';
        $fileHandler = new Files($uploadPath);

        if ($fileHandler->delete($arquivoParaDeletar, $subfolder)) {
            if ($curriculumModel->updateArquivoCurriculo($this->curriculumId, null)) {
                Session::set('mensagem_sucesso', 'Arquivo do currículo excluído com sucesso!');
            } else {
                Session::set('mensagem_erro', 'Arquivo excluído, mas ocorreu um erro ao atualizar o banco de dados.');
            }
        } else {
            Session::set('mensagem_erro', 'Ocorreu um erro ao excluir o arquivo físico.');
        }

        Redirect::page('candidatos/curriculo');
    }
}