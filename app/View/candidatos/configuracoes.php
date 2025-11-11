<?php 
include_once __DIR__ . "/comuns/candidato_cabecalho.php"; 

$usuario = $dados['usuario'] ?? [];
$nomeCompleto = $usuario['nome'] ?? '';

?>

<div class="container-fluid py-4">
    <div class="row">

        <?php include_once __DIR__ . "/comuns/sidebar.php"; ?>

        <div class="col-lg-9">

            <?php
            $mensagem_sucesso = Core\Library\Session::get('mensagem_sucesso');
            $mensagem_erro = Core\Library\Session::get('mensagem_erro');

            if ($mensagem_sucesso) {
                echo '<div class="alert alert-success alert-dismissible fade show" role="alert">' .
                     htmlspecialchars($mensagem_sucesso) .
                     '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
                Core\Library\Session::destroy('mensagem_sucesso');
            }

            if ($mensagem_erro) {
                echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">' .
                     htmlspecialchars($mensagem_erro) .
                     '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
                Core\Library\Session::destroy('mensagem_erro');
            }
            ?>

            <!-- Card de Dados Pessoais -->
            <div class="card shadow-sm mb-4">
                <div class="card-header pb-0">
                    <h2 class="h5">Dados Pessoais</h2>
                    <p class="text-sm text-muted">Atualize seu nome.</p>
                </div>
                <div class="card-body">
                    <form method="POST" action="<?= baseUrl() ?>candidatos/salvarConfiguracoes">
                        <div class="mb-3">
                            <label for="nome_completo" class="form-label">Nome Completo</label>
                            <input type="text" class="form-control" id="nome_completo" name="nome_completo" value="<?= htmlspecialchars($nomeCompleto) ?>">
                        </div>
                        
                        <div class="mb-3">
                            <label for="email" class="form-label">E-mail</label>
                            <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($usuario['login'] ?? '') ?>" disabled>
                            <div class="form-text">Seu e-mail é usado para login e não pode ser alterado.</div>
                        </div>
                        <button type="submit" class="btn btn-primary">Salvar Dados</button>
                    </form>
                </div>
            </div>

            <!-- Card de Alteração de Senha -->
            <div class="card shadow-sm">
                <div class="card-header pb-0">
                    <h2 class="h5">Alterar Senha</h2>
                    <p class="text-sm text-muted">Para sua segurança, escolha uma senha forte.</p>
                </div>
                <div class="card-body">
                    <form method="POST" action="<?= baseUrl() ?>candidatos/alterarSenha">
                        <div class="mb-3">
                            <label for="senha_atual" class="form-label">Senha Atual</label>
                            <input type="password" class="form-control" id="senha_atual" name="senha_atual" required>
                        </div>
                        <div class="mb-3">
                            <label for="nova_senha" class="form-label">Nova Senha</label>
                            <input type="password" class="form-control" id="nova_senha" name="nova_senha" required>
                        </div>
                        <div class="mb-3">
                            <label for="confirma_senha" class="form-label">Confirmar Nova Senha</label>
                            <input type="password" class="form-control" id="confirma_senha" name="confirma_senha" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Alterar Senha</button>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>

<?php 
include_once __DIR__ . "/comuns/candidato_rodape.php"; 
?>
