<?php
// Extrai a variável 'vaga' para facilitar o uso na view
$vaga = $dados['vaga'] ?? [];

// Mapa para traduzir o ID do vínculo para texto
$mapaVinculo = [
    '1' => 'CLT',
    '2' => 'PJ',
    '3' => 'Estágio',
    '4' => 'Temporário',
    '5' => 'Freelance'
];
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-header bg-light py-3">
                    <!-- Título da Vaga e Nome da Empresa -->
                    <h1 class="h3 mb-1 fw-bold"><?= htmlspecialchars($vaga['descricao'] ?? 'Título da Vaga') ?></h1>
                    <p class="text-muted mb-0">Publicado por: <strong><?= htmlspecialchars($vaga['nome_fantasia'] ?? 'Nome da Empresa') ?></strong></p>
                </div>
                
                <div class="card-body p-4">
                    <!-- Informações Principais -->
                    <div class="d-flex justify-content-start align-items-center flex-wrap gap-4 mb-4 text-muted border-bottom pb-3">
                        <div title="Tipo de Vínculo"><i class="fas fa-briefcase me-2"></i> <?= htmlspecialchars($mapaVinculo[$vaga['vinculo']] ?? 'Não especificado') ?></div>
                        <div title="Data de Início"><i class="fas fa-calendar-alt me-2"></i> Início em <?= !empty($vaga['dtInicio']) ? date('d/m/Y', strtotime($vaga['dtInicio'])) : 'N/A' ?></div>
                    </div>

                    <!-- Descrição da Vaga -->
                    <h5 class="mb-3">Sobre a Vaga</h5>
                    <div class="text-body mb-4">
                        <?= !empty($vaga['sobreaVaga']) ? nl2br(htmlspecialchars($vaga['sobreaVaga'])) : '<p class="text-muted">Os detalhes desta vaga não foram informados.</p>' ?>
                    </div>

                    <!-- Botões de Ação -->
                    <div class="d-flex justify-content-center flex-wrap gap-2 mt-5">
                        <a href="<?= baseUrl() ?>vagas/candidatar/<?= $vaga['vaga_id'] ?? '#' ?>" class="btn btn-primary">Candidatar-se</a>
                        
                        <?php 
                            $user = Core\Library\Session::get('usuario_logado');
                            if ($user && $user['tipo'] === 'CN'): 
                        ?>
                            <a href="<?= baseUrl() ?>mensagemCandidato/iniciarConversa/<?= $vaga['usuario_id'] ?? '#' ?>" class="btn btn-info text-white">Enviar Mensagem</a>
                        <?php endif; ?>

                        <a href="<?= baseUrl() ?>vagas" class="btn btn-secondary">Voltar</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
