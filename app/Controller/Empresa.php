<?php

namespace App\Controller;

use Core\Library\ControllerMain;
use Core\Library\Session;
use Core\Library\Redirect;
use App\Model\VagaModel;
use App\Model\CargoModel;
use App\Model\VagaCurriculumModel;
use App\Model\CurriculumModel;
use App\Model\EstabelecimentoModel;
use Core\Library\Files;

class Empresa extends ControllerMain
{
    private $dados = [];
    private $estabelecimentoId;

    public function __construct()
    {
        $this->auxiliarConstruct();
        
        $usuarioLogado = Session::get('usuario_logado');
        if (empty($usuarioLogado) || !in_array($usuarioLogado['tipo'], ['A', 'G'])) {
            Session::set('flash_msg', ['mensagem' => 'Acesso não autorizado.', 'tipo' => 'error']);
            Redirect::page('login');
            exit;
        }

        if (!isset($usuarioLogado['estabelecimento_id'])) {
            if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
                header('Content-Type: application/json');
                http_response_code(401); // Unauthorized
                echo json_encode(['success' => false, 'message' => 'ERRO DE SESSÃO: O ID do estabelecimento não foi encontrado na sua sessão. Por favor, faça login novamente.']);
                exit;
            } else {
                Session::set('flash_msg', ['mensagem' => 'Erro de sessão. Por favor, faça login novamente.', 'tipo' => 'error']);
                Redirect::page('login');
                exit;
            }
        }

        $this->estabelecimentoId = $usuarioLogado['estabelecimento_id'];

        $estabelecimentoModel = new EstabelecimentoModel();
        $estabelecimentoDados = $estabelecimentoModel->getById($this->estabelecimentoId);
        $this->dados['usuario'] = $estabelecimentoDados ? array_merge($usuarioLogado, $estabelecimentoDados) : $usuarioLogado;

        $vagaModel = new VagaModel();
        $vagaCurriculumModel = new VagaCurriculumModel();

        $totalVagas = $vagaModel->countByEstabelecimento($this->estabelecimentoId, 11);
        $totalCandidatos = $vagaCurriculumModel->countByEstabelecimento($this->estabelecimentoId);

        $this->dados['stats'] = [
            'vagas' => $totalVagas,
            'candidatos' => $totalCandidatos
        ];
    }

    public function salvarLogoRecortada()
    {
        header('Content-Type: application/json');
        try {
            // Garante que ROOTPATH exista e aponta para a raiz do projeto.
            if (!defined('ROOTPATH')) {
                define('ROOTPATH', dirname(__FILE__, 3) . DIRECTORY_SEPARATOR);
            }

            if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_FILES['logo'])) {
                throw new \Exception('Requisição inválida ou nenhum arquivo enviado.');
            }

            if ($_FILES['logo']['error'] !== UPLOAD_ERR_OK) {
                throw new \Exception('Erro no upload do arquivo: código ' . $_FILES['logo']['error']);
            }

            $estabelecimentoModel = new EstabelecimentoModel();
            $logoAntiga = $this->dados['usuario']['logo'] ?? '';
            $subfolder = 'logos';
            
            $uploadPath = ROOTPATH . 'public' . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR;

            $files = new Files(
                $uploadPath,
                ['image/png', 'image/jpeg', 'image/gif'], // Mimetypes permitidos
                2 // Tamanho máximo em MB
            );

            // CORREÇÃO: Passando o nome do logo antigo como terceiro parâmetro.
            // A classe Files agora se encarrega de remover o arquivo antigo, seguindo o padrão do controller Candidatos.php.
            $uploadedFiles = $files->upload([$_FILES['logo']], $subfolder, $logoAntiga);
            
            if (!$uploadedFiles) {
                $errorMsg = Session::get('msgError') ?? 'Erro desconhecido durante o upload.';
                Session::destroy('msgError');
                throw new \Exception($errorMsg);
            }

            $novoNomeLogo = $uploadedFiles[0]; // Pega o nome do primeiro (e único) arquivo enviado.

            // Atualiza o banco de dados e remove o arquivo antigo
            if ($estabelecimentoModel->updateLogo($this->estabelecimentoId, $novoNomeLogo)) {
                // A exclusão do arquivo antigo agora é tratada dentro do método upload.
                
                $usuarioLogado = Session::get('usuario_logado');
                $usuarioLogado['logo'] = $novoNomeLogo;
                Session::set('usuario_logado', $usuarioLogado);

                echo json_encode([
                    'success' => true,
                    'url' => baseUrl() . 'uploads/' . $subfolder . '/' . $novoNomeLogo,
                    'message' => 'Logo atualizada com sucesso!'
                ]);
            } else {
                // O método delete precisa do nome do arquivo e da pasta.
                $files->delete($novoNomeLogo, $subfolder); // Remove o novo arquivo se a atualização do DB falhar
                throw new \Exception('Erro ao atualizar a logo no banco de dados.');
            }

        } catch (\Throwable $e) {
            http_response_code(400); // Bad Request
            echo json_encode([
                'success' => false,
                'message' => 'ERRO INTERNO: ' . $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
        }
        return;
    }
    
      public function index()
    {
        $vagaModel = new VagaModel();
        $this->dados['vagas_ativas'] = $vagaModel->findRecentesByEstabelecimento($this->estabelecimentoId, 5);
        $this->loadView("empresa/index", $this->dados, false);
    }

    public function candidatos($params = [])
    {
        $vagaId = $params[0] ?? null;

        if ($vagaId) {
            $vagaModel = new VagaModel();
            $vaga = $vagaModel->getById($vagaId);

            if (!$vaga || $vaga['estabelecimento_id'] != $this->estabelecimentoId) {
                Session::set('flash_msg', ['mensagem' => 'Vaga não encontrada ou não pertence à sua empresa.', 'tipo' => 'error']);
                Redirect::page('empresa/vagas');
                return;
            }

            $vagaCurriculumModel = new VagaCurriculumModel();
            $this->dados['vaga'] = $vaga;
            $this->dados['candidatos'] = $vagaCurriculumModel->getCandidatosPorVaga($vagaId);

            $this->loadView("empresa/candidatos_vaga", $this->dados, false);

        } else {
            $vagaCurriculumModel = new VagaCurriculumModel();
            $this->dados['candidatos'] = $vagaCurriculumModel->getCandidatosPorEstabelecimento($this->estabelecimentoId);
            
            $vagaModel = new VagaModel();
            $this->dados['vagas'] = $vagaModel->getByEstabelecimento($this->estabelecimentoId);
            
            $this->loadView("empresa/candidatos", $this->dados, false);
        }
    }

    public function verCurriculo($params)
    {
        $curriculoId = $params[0] ?? null;
        if (!$curriculoId) {
            Redirect::page('empresa/candidatos');
            return;
        }

        $curriculumModel = new CurriculumModel();
        $curriculo = $curriculumModel->getCompletoById($curriculoId);

        if (empty($curriculo)) {
            Session::set('flash_msg', ['mensagem' => 'Currículo não encontrado.', 'tipo' => 'error']);
            Redirect::page('empresa/candidatos');
            return;
        }

        $this->dados['curriculo'] = $curriculo;
        $this->loadView("empresa/ver_curriculo", $this->dados, false);
    }
    
    public function vagas()
    {
        $vagaModel = new VagaModel();
        $cargoModel = new CargoModel();
        $vagaCurriculumModel = new VagaCurriculumModel();

        $todasVagas = $vagaModel->getByEstabelecimento($this->estabelecimentoId);
        $this->dados['cargos'] = $cargoModel->listarTodos();

        foreach ($todasVagas as &$vaga) {
            $vaga['num_candidatos'] = $vagaCurriculumModel->countByVagaId($vaga['vaga_id']);
        }

        $vagasAtivas = [];
        $vagasArquivadas = [];

        foreach ($todasVagas as $vaga) {
            if ($vaga['statusVaga'] == 99) {
                $vagasArquivadas[] = $vaga;
            } else {
                $vagasAtivas[] = $vaga;
            }
        }

        $this->dados['vagas_ativas'] = $vagasAtivas;
        $this->dados['vagas_arquivadas'] = $vagasArquivadas;

        $this->loadView("empresa/vagas", $this->dados, false);
    }

    public function salvar()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $vagaModel = new VagaModel();

            $dadosVaga = [
                'cargo_id' => $_POST['cargo_id'],
                'descricao' => $_POST['descricao'],
                'sobreaVaga' => $_POST['sobreaVaga'],
                'modalidade' => $_POST['modalidade'],
                'vinculo' => $_POST['vinculo'],
                'dtInicio' => $_POST['dtInicio'],
                'dtFim' => $_POST['dtFim'],
                'estabelecimento_id' => $this->estabelecimentoId,
                'statusVaga' => 11
            ];

            if ($vagaModel->insert($dadosVaga)) {
                Session::set('flash_msg', ['mensagem' => 'Vaga criada com sucesso!', 'tipo' => 'success']);
            } else {
                Session::set('flash_msg', ['mensagem' => 'Erro ao criar a vaga. Verifique os dados.', 'tipo' => 'error']);
            }
        } else {
            Session::set('flash_msg', ['mensagem' => 'Requisição inválida.', 'tipo' => 'error']);
        }

        Redirect::page('empresa/vagas');
    }

    public function editar($idVaga = null)
    {
        if (empty($idVaga)) {
            Redirect::page('empresa/vagas');
            exit;
        }

        $vagaModel = new VagaModel();
        $cargoModel = new CargoModel();

        $vaga = $vagaModel->getById($idVaga[0]);
        if (!$vaga || $vaga['estabelecimento_id'] != $this->estabelecimentoId) {
             Session::set('flash_msg', ['mensagem' => 'Vaga não encontrada ou não pertence a esta empresa.', 'tipo' => 'error']);
            Redirect::page('empresa/vagas');
            exit;
        }

        $this->dados['vaga'] = $vaga;
        $this->dados['cargos'] = $cargoModel->listarTodos();

        $this->loadView("empresa/editar_vaga", $this->dados, false);
    }

    public function atualizar()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['vaga_id'])) {
            $vagaModel = new VagaModel();
            $idVaga = $_POST['vaga_id'];

            $vaga = $vagaModel->getById($idVaga);
            if (!$vaga || $vaga['estabelecimento_id'] != $this->estabelecimentoId) {
                Session::set('flash_msg', ['mensagem' => 'Ação não permitida.', 'tipo' => 'error']);
                Redirect::page('empresa/vagas');
                return;
            }
            
            $dadosVaga = [
                'cargo_id' => $_POST['cargo_id'],
                'descricao' => $_POST['descricao'],
                'sobreaVaga' => $_POST['sobreaVaga'],
                'modalidade' => $_POST['modalidade'],
                'vinculo' => $_POST['vinculo'],
                'dtInicio' => $_POST['dtInicio'],
                'dtFim' => $_POST['dtFim']
            ];

            $originalRules = $vagaModel->validationRules;
            $updateRules = array_intersect_key($originalRules, $dadosVaga);
            $vagaModel->validationRules = $updateRules;

            if ($vagaModel->update($idVaga, $dadosVaga)) {
                Session::set('flash_msg', ['mensagem' => 'Vaga atualizada com sucesso!', 'tipo' => 'success']);
            } else {
                Session::set('flash_msg', ['mensagem' => 'Erro ao atualizar a vaga.', 'tipo' => 'error']);
            }
            
            $vagaModel->validationRules = $originalRules;

        } else {
            Session::set('flash_msg', ['mensagem' => 'Requisição inválida.', 'tipo' => 'error']);
        }

        Redirect::page('empresa/vagas');
    }
    
    public function excluir()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['vaga_id'])) {
            $vagaModel = new VagaModel();
            $idVaga = $_POST['vaga_id'];

            $vaga = $vagaModel->getById($idVaga);
            if ($vaga && $vaga['estabelecimento_id'] == $this->estabelecimentoId) {
                $dadosUpdate = ['statusVaga' => 99];
                
                $originalRules = $vagaModel->validationRules;
                $updateRules = array_intersect_key($originalRules, $dadosUpdate);
                $vagaModel->validationRules = $updateRules;

                if ($vagaModel->update($idVaga, $dadosUpdate)) {
                    Session::set('flash_msg', ['mensagem' => 'Vaga arquivada com sucesso!', 'tipo' => 'success']);
                } else {
                    Session::set('flash_msg', ['mensagem' => 'Erro ao arquivar a vaga.', 'tipo' => 'error']);
                }

                $vagaModel->validationRules = $originalRules;
            } else {
                Session::set('flash_msg', ['mensagem' => 'Ação não permitida.', 'tipo' => 'error']);
            }
        } else {
            Session::set('flash_msg', ['mensagem' => 'Requisição inválida.', 'tipo' => 'error']);
        }
        Redirect::page('empresa/vagas');
    }

    public function atualizarStatus()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['vaga_id'], $_POST['statusVaga'])) {
            $vagaModel = new VagaModel();
            $idVaga = $_POST['vaga_id'];
            $status = $_POST['statusVaga'];

            $vaga = $vagaModel->getById($idVaga);
            if ($vaga && $vaga['estabelecimento_id'] == $this->estabelecimentoId) {
                $dadosUpdate = ['statusVaga' => $status];

                $originalRules = $vagaModel->validationRules;
                $updateRules = array_intersect_key($originalRules, $dadosUpdate);
                $vagaModel->validationRules = $updateRules;

                if ($vagaModel->update($idVaga, $dadosUpdate)) {
                    Session::set('flash_msg', ['mensagem' => 'Status da vaga atualizado com sucesso!', 'tipo' => 'success']);
                } else {
                    Session::set('flash_msg', ['mensagem' => 'Erro ao atualizar o status da vaga.', 'tipo' => 'error']);
                }

                $vagaModel->validationRules = $originalRules;
            } else {
                Session::set('flash_msg', ['mensagem' => 'Ação não permitida.', 'tipo' => 'error']);
            }
        } else {
            Session::set('flash_msg', ['mensagem' => 'Requisição inválida.', 'tipo' => 'error']);
        }

        Redirect::page('empresa/vagas');
    }

    public function mensagens()
    {
        $this->loadView("empresa/mensagens", $this->dados, false);
    }

    public function configuracoes()
    {
        $usuarioLogado = Session::get('usuario_logado');
        $estabelecimentoModel = new EstabelecimentoModel();
        $estabelecimentoDados = $estabelecimentoModel->getById($this->estabelecimentoId);
        $this->dados['usuario'] = $estabelecimentoDados ? array_merge($usuarioLogado, $estabelecimentoDados) : $usuarioLogado;

        $this->loadView("empresa/configuracoes", $this->dados, false);
    }

    public function salvarConfiguracoes()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            Session::set('flash_msg', ['mensagem' => 'Requisição inválida.', 'tipo' => 'error']);
            Redirect::page('empresa/configuracoes');
            return;
        }

        $estabelecimentoModel = new EstabelecimentoModel();
        
        $dadosUpdate = [
            'nome' => $_POST['nome'] ?? '',
            'sobre' => $_POST['sobre'] ?? '',
            'website' => $_POST['website'] ?? ''
        ];
        
        $originalRules = $estabelecimentoModel->validationRules;
        $rulesToApply = array_intersect_key($originalRules, array_filter($dadosUpdate));
        $estabelecimentoModel->validationRules = $rulesToApply;

        if ($estabelecimentoModel->update($this->estabelecimentoId, $dadosUpdate)) {
            Session::set('flash_msg', ['mensagem' => 'Informações da empresa atualizadas com sucesso!', 'tipo' => 'success']);
        } else {
            $errors = Session::get('form_errors') ?? [];
            $errorMessage = 'Erro ao atualizar as informações. ';
            if (!empty($errors)) {
                $errorMessage .= implode(' ', array_values($errors));
                Session::destroy('form_errors');
            } else {
                $errorMessage .= 'Verifique os dados inseridos.';
            }
            Session::set('flash_msg', ['mensagem' => $errorMessage, 'tipo' => 'error']);
        }

        $estabelecimentoModel->validationRules = $originalRules;

        Redirect::page('empresa/configuracoes');
    }
}

