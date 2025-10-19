<?php 
include_once __DIR__ . "/comuns/candidato_cabecalho.php";

$usuario = $dados['usuario'] ?? [];
$curriculum = $dados['curriculum'] ?? [];
$nomeCompleto = trim(($usuario['nome'] ?? '') . ' ' . ($usuario['sobrenome'] ?? ''));
$progresso = $dados['progresso_perfil'] ?? 0;

// Montar endereço completo incluindo cidade e UF
$endereco = trim(
    ($curriculum['logradouro'] ?? '') . ', ' . 
    ($curriculum['numero'] ?? '') . 
    (!empty($curriculum['complemento']) ? ' - ' . $curriculum['complemento'] : '')
);
$cidadeUf = trim(
    ($curriculum['cidade'] ?? '') . (!empty($curriculum['uf']) ? ' - ' . $curriculum['uf'] : '')
);

?>

<div class="container-fluid py-4">
    <div class="row">
        <?php include_once __DIR__ . "/comuns/sidebar.php"; ?>

        <div class="col-lg-9">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-light d-flex justify-content-between align-items-center">
                    <h2 class="h4 mb-0">Meu Perfil</h2>
                    <a href="<?= baseUrl() ?>candidatos/curriculo" class="btn btn-sm btn-primary">Editar Perfil</a>
                </div>
                <div class="card-body">
                    <!-- Barra de Progresso -->
                    <div class="mb-4">
                        <h3 class="h6 mb-2">Progresso do Perfil</h3>
                        <div class="progress" style="height: 20px;">
                            <div class="progress-bar bg-success" role="progressbar" style="width: <?= $progresso ?>%;" aria-valuenow="<?= $progresso ?>" aria-valuemin="0" aria-valuemax="100">
                                <?= $progresso ?>%
                            </div>
                        </div>
                        <small class="text-muted">Complete seu perfil para ter mais chances de ser notado.</small>
                    </div>

                    <form>
                        <fieldset disabled>
                            <h3 class="h5 mb-3">Informações Pessoais</h3>
                            <div class="row mb-4">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Nome Completo</label>
                                    <input type="text" class="form-control" value="<?= htmlspecialchars($nomeCompleto) ?>">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">E-mail</label>
                                    <input type="email" class="form-control" value="<?= htmlspecialchars($usuario['login'] ?? '') ?>">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Telefone</label>
                                    <input type="tel" class="form-control" value="<?= htmlspecialchars($usuario['telefone'] ?? '') ?>">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Data de Nascimento</label>
                                    <input type="text" class="form-control" value="<?= htmlspecialchars(!empty($curriculum['dataNascimento']) ? date('d/m/Y', strtotime($curriculum['dataNascimento'])) : '') ?>">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Endereço</label>
                                    <input type="text" class="form-control" value="<?= htmlspecialchars($endereco) ?>">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Bairro</label>
                                    <input type="text" class="form-control" value="<?= htmlspecialchars($curriculum['bairro'] ?? '') ?>">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Cidade</label>
                                    <input type="text" class="form-control" value="<?= htmlspecialchars($cidadeUf) ?>">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">CEP</label>
                                    <input type="text" class="form-control" value="<?= htmlspecialchars($curriculum['cep'] ?? '') ?>">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Sexo</label>
                                    <input type="text" class="form-control" value="<?= htmlspecialchars(($curriculum['sexo'] ?? '') == 'M' ? 'Masculino' : (($curriculum['sexo'] ?? '') == 'F' ? 'Feminino' : '')) ?>">
                                </div>
                            </div>

                            <h3 class="h5 mb-3">Apresentação Pessoal</h3>
                            <div class="row mb-4">
                                <div class="col-12 mb-3">
                                    <textarea class="form-control" rows="4"><?= htmlspecialchars($curriculum['apresentacaoPessoal'] ?? 'Não informado.') ?></textarea>
                                </div>
                            </div>
                        </fieldset>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include_once __DIR__ . "/comuns/candidato_rodape.php"; ?>
