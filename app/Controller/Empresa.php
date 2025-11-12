<?php

namespace App\Controller;

use Core\Library\Session;
use Core\Library\Redirect;
use App\Model\VagaModel;
use App\Model\CargoModel;
use App\Model\VagaCurriculumModel;
use App\Model\CurriculumModel;
use App\Model\EstabelecimentoModel;
use App\Model\UsuarioModel;
use Core\Library\Files;


class Empresa extends EmpresaBaseController
{


    public function __construct()
    {
        parent::__construct();
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

        $this->viewData['curriculo'] = $curriculo;
        $this->loadView("empresa/ver_curriculo", $this->viewData, false);
    }

     public function salvarLogoRecortada()
    {
        header('Content-Type: application/json');
        try {
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
            
            $logoAntiga = $this->viewData['usuario']['logo'] ?? '';
            $subfolder = 'logos';
            
            $uploadPath = ROOTPATH . 'public' . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR;

            $files = new Files(
                $uploadPath,
                ['image/png', 'image/jpeg', 'image/gif'],
                2
            );

            $uploadedFiles = $files->upload([$_FILES['logo']], $subfolder, $logoAntiga);
            
            if (!$uploadedFiles) {
                $errorMsg = Session::get('msgError') ?? 'Erro desconhecido durante o upload.';
                Session::destroy('msgError');
                throw new \Exception($errorMsg);
            }

            $novoNomeLogo = $uploadedFiles[0];

            if ($estabelecimentoModel->updateLogo($this->estabelecimentoId, $novoNomeLogo)) {
                $usuarioLogado = Session::get('usuario_logado');
                $usuarioLogado['logo'] = $novoNomeLogo;
                Session::set('usuario_logado', $usuarioLogado);

                echo json_encode([
                    'success' => true,
                    'url' => baseUrl() . 'uploads/' . $subfolder . '/' . $novoNomeLogo,
                    'message' => 'Logo atualizada com sucesso!'
                ]);
            } else {
                $files->delete($novoNomeLogo, $subfolder);
                throw new \Exception('Erro ao atualizar a logo no banco de dados.');
            }

        } catch (\Throwable $e) {
            http_response_code(400);
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
        $vagaModel = $this->loadModel("Vaga");
        $this->viewData['vagas_ativas'] = $vagaModel->findRecentesByEstabelecimento($this->estabelecimentoId, 5);
        $this->loadView("empresa/index", $this->viewData, false);
    }

    public function candidatos($params = [])
    {
        $vagaId = $params[0] ?? null;
        $vagaModel = $this->loadModel("Vaga"); 
        $vagaCurriculumModel = $this->loadModel("VagaCurriculum");

        if ($vagaId) {
            $vaga = $vagaModel->getById($vagaId);
            if (!$vaga || $vaga['estabelecimento_id'] != $this->estabelecimentoId) {
                Session::set('flash_msg', ['mensagem' => 'Vaga não encontrada ou não pertence à sua empresa.', 'tipo' => 'error']);
                Redirect::page('empresa/vagas');
                return;
            }

            $this->viewData['vaga'] = $vaga;
            $this->viewData['candidatos'] = $vagaCurriculumModel->getCandidatosPorVaga($vagaId);

            $this->loadView("empresa/candidatos_vaga", $this->viewData, false);

        } else {
            $this->viewData['candidatos'] = $vagaCurriculumModel->getCandidatosPorEstabelecimento($this->estabelecimentoId);
            $this->viewData['vagas'] = $vagaModel->getByEstabelecimento($this->estabelecimentoId);
            
            $this->loadView("empresa/candidatos", $this->viewData, false);
        }
    }
    
    public function vagas()
    {
        $vagaModel = $this->loadModel("Vaga");
        $cargoModel = $this->loadModel("Cargo");
        $vagaCurriculumModel = $this->loadModel("VagaCurriculum");
        $todasVagas = $vagaModel->getByEstabelecimento($this->estabelecimentoId);
        
        $this->viewData['cargos'] = $cargoModel->listarTodos();
        $this->viewData['vaga'] = []; // Garante que o formulário de nova vaga esteja sempre limpo

        // Use um novo array para evitar problemas de referência com o foreach
        $vagasComContagem = [];
        foreach ($todasVagas as $vaga) {
            $vaga['num_candidatos'] = $vagaCurriculumModel->countByVagaId($vaga['vaga_id']);
            $vagasComContagem[] = $vaga;
        }

        $vagasAtivas = [];
        $vagasArquivadas = [];

        foreach ($vagasComContagem as $vaga) {
            if ($vaga['statusVaga'] == 99) {
                $vagasArquivadas[] = $vaga;
            } else {
                $vagasAtivas[] = $vaga;
            }
        }

        $this->viewData['vagas_ativas'] = $vagasAtivas;
        $this->viewData['vagas_arquivadas'] = $vagasArquivadas;

        $this->loadView("empresa/vagas", $this->viewData, false);
    }

    public function salvar()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $vagaModel = $this->loadModel('Vaga');
            $cargoModel = $this->loadModel('Cargo');
            $cargoId = $_POST['cargo_id'];

            if ($cargoId === 'outro' && !empty($_POST['outro_cargo_descricao'])) {
                $novoCargoDescricao = trim($_POST['outro_cargo_descricao']);
                
                $cargoId = $cargoModel->insert(['descricao' => $novoCargoDescricao]);
                
                if (!$cargoId) {
                    Session::set('flash_msg', ['mensagem' => 'Erro ao criar o novo cargo.', 'tipo' => 'error']);
                    Redirect::page('empresa/vagas');
                    return;
                }
            }

            $dadosVaga = [
                'cargo_id' => $cargoId,
                'descricao' => $_POST['descricao'],
                'sobreaVaga' => $_POST['sobreaVaga'],
                'modalidade' => $_POST['modalidade'],
                'vinculo' => $_POST['vinculo'],
                'dtInicio' => $_POST['dtInicio'],
                'dtFim' => $_POST['dtFim'],
                'salario' => !empty($_POST['salario']) ? $_POST['salario'] : null,
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

    public function editar($params = [])
    {
        $idVaga = $params[0] ?? null;
        if (empty($idVaga)) {
            Redirect::page('empresa/vagas');
            exit;
        }

        $vagaModel = $this->loadModel("Vaga");
        $cargoModel = $this->loadModel("Cargo");

        $vaga = $vagaModel->getById($idVaga);
    

        
        // Esta verificação está correta. O erro é causado pela corrupção de dados
        // no método 'atualizar'.
        if (!$vaga || $vaga['estabelecimento_id'] != $this->estabelecimentoId) {
             Session::set('flash_msg', ['mensagem' => 'Vaga não encontrada ou não pertence a esta empresa.', 'tipo' => 'error']);
            Redirect::page('empresa/vagas');
            exit;
        }

        $this->viewData['vaga'] = $vaga;
        $this->viewData['cargos'] = $cargoModel->listarTodos();

        $this->loadView("empresa/editar_vaga", $this->viewData, false);
    }

    public function atualizar()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['vaga_id'])) {
            $vagaModel = $this->loadModel("Vaga");
            $idVaga = $_POST['vaga_id'];

            $vaga = $vagaModel->getById($idVaga);
            if (!$vaga || $vaga['estabelecimento_id'] != $this->estabelecimentoId) {
                Session::set('flash_msg', ['mensagem' => 'Ação não permitida.', 'tipo' => 'error']);
                Redirect::page('empresa/vagas');
                return;
            }
            
            // Dados vindos do formulário
            $dadosForm = [
                'cargo_id' => $_POST['cargo_id'],
                'descricao' => $_POST['descricao'],
                'sobreaVaga' => $_POST['sobreaVaga'],
                'modalidade' => $_POST['modalidade'],
                'vinculo' => $_POST['vinculo'],
                'dtInicio' => $_POST['dtInicio'],
                'dtFim' => $_POST['dtFim'],
                'salario' => !empty($_POST['salario']) ? $_POST['salario'] : null
            ];

            // *** CORREÇÃO ***
            // Mescla os dados antigos com os novos dados do formulário
            // Isso garante que 'estabelecimento_id' e 'statusVaga' não sejam perdidos
            $dadosVaga = array_merge($vaga, $dadosForm);

            // *** CORREÇÃO ***
            // Remove a lógica de manipulação de regras de validação.
            // O modelo deve validar o objeto $dadosVaga completo.
            // $originalRules = $vagaModel->validationRules; // REMOVIDO
            // $updateRules = array_intersect_key($originalRules, $dadosVaga); // REMOVIDO
            // $vagaModel->validationRules = $updateRules; // REMOVIDO

            if ($vagaModel->update($idVaga, $dadosVaga)) {
                Session::set('flash_msg', ['mensagem' => 'Vaga atualizada com sucesso!', 'tipo' => 'success']);
            } else {
                Session::set('flash_msg', ['mensagem' => 'Erro ao atualizar a vaga.', 'tipo' => 'error']);
            }
            
            // $vagaModel->validationRules = $originalRules; // REMOVIDO

        } else {
            Session::set('flash_msg', ['mensagem' => 'Requisição inválida.', 'tipo' => 'error']);
        }

        Redirect::page('empresa/vagas');
    }
    
    public function excluir()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['vaga_id'])) {
            $vagaModel = $this->loadModel("Vaga");
            $idVaga = $_POST['vaga_id'];

            $vaga = $vagaModel->getById($idVaga);
            if ($vaga && $vaga['estabelecimento_id'] == $this->estabelecimentoId) {
                
                $dadosUpdate = ['statusVaga' => 99];
                
                // *** CORREÇÃO ***
                // Mescla os dados antigos com os novos para não perder 'estabelecimento_id'
                $dadosVaga = array_merge($vaga, $dadosUpdate);

                // *** CORREÇÃO ***
                // Remove a lógica de manipulação de regras de validação.
                // $originalRules = $vagaModel->validationRules; // REMOVIDO
                // $updateRules = array_intersect_key($originalRules, $dadosUpdate); // REMOVIDO
                // $vagaModel->validationRules = $updateRules; // REMOVIDO

                if ($vagaModel->update($idVaga, $dadosVaga)) {
                    Session::set('flash_msg', ['mensagem' => 'Vaga arquivada com sucesso!', 'tipo' => 'success']);
                } else {
                    Session::set('flash_msg', ['mensagem' => 'Erro ao arquivar a vaga.', 'tipo' => 'error']);
                }

                // $vagaModel->validationRules = $originalRules; // REMOVIDO
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
            $vagaModel = $this->loadModel("Vaga");
            $idVaga = $_POST['vaga_id'];
            $status = $_POST['statusVaga'];

            $vaga = $vagaModel->getById($idVaga);
            if ($vaga && $vaga['estabelecimento_id'] == $this->estabelecimentoId) {
                
                $dadosUpdate = ['statusVaga' => $status];

                // *** CORREÇÃO ***
                // Mescla os dados antigos com os novos para não perder 'estabelecimento_id'
                $dadosVaga = array_merge($vaga, $dadosUpdate);

                // *** CORREÇÃO ***
                // Remove a lógica de manipulação de regras de validação.
                // $originalRules = $vagaModel->validationRules; // REMOVIDO
                // $updateRules = array_intersect_key($originalRules, $dadosUpdate); // REMOVIDO
                // $vagaModel->validationRules = $updateRules; // REMOVIDO

                if ($vagaModel->update($idVaga, $dadosVaga)) {
                    Session::set('flash_msg', ['mensagem' => 'Status da vaga atualizado com sucesso!', 'tipo' => 'success']);
                } else {
                    Session::set('flash_msg', ['mensagem' => 'Erro ao atualizar o status da vaga.', 'tipo' => 'error']);
                }

                // $vagaModel->validationRules = $originalRules; // REMOVIDO
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
        $this->loadView("empresa/mensagens", $this->viewData, false);
    }

    public function configuracoes()
    {
        $this->loadView("empresa/configuracoes", $this->viewData, false);
    }

    public function salvarConfiguracoes()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            Session::set('flash_msg', ['mensagem' => 'Requisição inválida.', 'tipo' => 'error']);
            Redirect::page('empresa/configuracoes');
            return;
        }

        $estabelecimentoModel = $this->loadModel("Estabelecimento");
        
        // Dados do formulário
        $dadosForm = [
            'nome' => $_POST['nome'] ?? null,
            'sobre' => $_POST['sobre'] ?? null,
            'website' => $_POST['website'] ?? null
        ];

        // *** CORREÇÃO ***
        // Busca dados antigos para mesclar
        // Supondo que 'usuario' nos viewData contém os dados do estabelecimento
        $dadosAntigos = $this->viewData['usuario'] ?? [];
        
        // Mescla, filtrando valores nulos ou vazios do formulário
        // para não sobrescrever dados existentes com valores em branco.
        $dadosUpdate = array_merge($dadosAntigos, array_filter($dadosForm));
        
        // *** CORREÇÃO ***
        // Remove a lógica de manipulação de regras de validação.
        // $originalRules = $estabelecimentoModel->validationRules; // REMOVIDO
        // $rulesToApply = array_intersect_key($originalRules, array_filter($dadosUpdate)); // REMOVIDO
        // $estabelecimentoModel->validationRules = $rulesToApply; // REMOVIDO

        // 21. USA $this->estabelecimentoId (herdado)
        if ($estabelecimentoModel->update($this->estabelecimentoId, $dadosUpdate)) {
            Session::set('flash_msg', ['mensagem' => 'Informações da empresa atualizadas com sucesso!', 'tipo' => 'success']);
            
            // Atualiza também a sessão para refletir as mudanças
            $usuarioLogado = Session::get('usuario_logado');
            $usuarioLogado = array_merge($usuarioLogado, array_filter($dadosForm));
            Session::set('usuario_logado', $usuarioLogado);

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

        // $estabelecimentoModel->validationRules = $originalRules; // REMOVIDO

        Redirect::page('empresa/configuracoes');
    }

    public function alterarSenha()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            Session::set('flash_msg', ['mensagem' => 'Requisição inválida.', 'tipo' => 'error']);
            Redirect::page('empresa/configuracoes');
            return;
        }

        $senhaAtual = $_POST['senha_atual'] ?? '';
        $novaSenha = $_POST['nova_senha'] ?? '';
        $confirmarSenha = $_POST['confirmar_senha'] ?? '';

        if (empty($senhaAtual) || empty($novaSenha) || empty($confirmarSenha)) {
            Session::set('flash_msg', ['mensagem' => 'Todos os campos de senha são obrigatórios.', 'tipo' => 'error']);
            Redirect::page('empresa/configuracoes');
            return;
        }

        if ($novaSenha !== $confirmarSenha) {
            Session::set('flash_msg', ['mensagem' => 'A nova senha e a confirmação não correspondem.', 'tipo' => 'error']);
            Redirect::page('empresa/configuracoes');
            return;
        }

        $usuarioLogado = Session::get('usuario_logado');
        $usuarioId = $usuarioLogado['usuario_id'];

        $usuarioModel = $this->loadModel("Usuario"); 
        $usuario = $usuarioModel->getById($usuarioId);

        if (!$usuario || !password_verify($senhaAtual, $usuario['senha'])) {
            Session::set('flash_msg', ['mensagem' => 'A senha atual está incorreta.', 'tipo' => 'error']);
            Redirect::page('empresa/configuracoes');
            return;
        }

        $novaSenhaHash = password_hash($novaSenha, PASSWORD_DEFAULT);
        $dadosUpdate = ['senha' => $novaSenhaHash];

        // Aqui a lógica de validação parcial está correta,
        // pois você está atualizando um modelo diferente (Usuario)
        // e só quer validar a senha.
        $originalRules = $usuarioModel->validationRules;
        $usuarioModel->validationRules = [
            'senha' => $originalRules['senha']
        ];

        if ($usuarioModel->update($usuarioId, $dadosUpdate)) {
            Session::set('flash_msg', ['mensagem' => 'Senha alterada com sucesso!', 'tipo' => 'success']);
        } else {
            Session::set('flash_msg', ['mensagem' => 'Ocorreu um erro ao alterar a senha. Tente novamente.', 'tipo' => 'error']);
        }

        $usuarioModel->validationRules = $originalRules; 

        Redirect::page('empresa/configuracoes');
    }
}