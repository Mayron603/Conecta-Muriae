<?php 
include_once __DIR__ . "/comuns/candidato_cabecalho.php"; 

$usuario = $aDados['usuario'] ?? [];
$nome = $usuario['nome'] ?? 'Candidato';

$stats = $aDados['stats'] ?? [
    'candidaturas' => 0
];

$vagasRecomendadas = $aDados['vagasRecomendadas'] ?? [];
$candidaturasRecentes = $aDados['candidaturasRecentes'] ?? [];

function getStatusCandidatura($status) {
    switch ($status) {
        case 1: return ['text' => 'Pendente', 'class' => 'bg-warning'];
        case 2: return ['text' => 'Em Análise', 'class' => 'bg-info'];
        case 3: return ['text' => 'Entrevista Agendada', 'class' => 'bg-primary'];
        case 4: return ['text' => 'Aprovado', 'class' => 'bg-success'];
        case 5: return ['text' => 'Reprovado', 'class' => 'bg-danger'];
        default: return ['text' => 'Desconhecido', 'class' => 'bg-secondary'];
    }
}

?>

<div class="container-fluid py-4">
    <div class="row">

        <?php include_once __DIR__ . "/comuns/sidebar.php"; ?>

        <!-- Content Area -->
        <div class="col-lg-9">
            <!-- Welcome Card -->
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h2 class="h4 mb-0">Bem-vindo de volta, <?= htmlspecialchars($nome) ?>!</h2>
                        <a href="<?= baseUrl() ?>candidatos" class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-sync-alt me-1"></i> Atualizar
                        </a>
                    </div>
                    <p class="text-muted mb-4">Acompanhe suas candidaturas e encontre novas oportunidades.</p>
                    
                    <div class="row justify-content-center">
                        <div class="col-md-6 mb-3 mb-md-0">
                            <div class="card bg-primary bg-opacity-10 border-0 h-100">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div class="bg-primary bg-opacity-25 p-3 rounded me-3">
                                            <i class="fas fa-briefcase text-primary"></i>
                                        </div>
                                        <div>
                                            <h3 class="mb-0"><?= $stats['candidaturas'] ?></h3>
                                            <small class="text-muted">Candidaturas</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recommended Jobs -->
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h2 class="h4 mb-0">Vagas Recomendadas</h2>
                        <a href="<?= baseUrl() ?>vagas" class="btn btn-sm btn-outline-primary">Ver todas</a>
                    </div>
                    <p class="text-muted mb-4">Baseado no seu perfil e histórico</p>
                    
                    <div class="row">
                        <?php if (!empty($vagasRecomendadas)): ?>
                            <?php foreach (array_slice($vagasRecomendadas, 0, 2) as $vaga): ?>
                                <div class="col-md-6 mb-4">
                                    <div class="card h-100 hover-shadow">
                                        <div class="card-body">
                                            <h5 class="card-title"><?= htmlspecialchars($vaga['cargo_descricao'] ?? 'N/A') ?></h5>
                                            <h6 class="card-subtitle mb-2 text-muted"><?= htmlspecialchars($vaga['nome_fantasia'] ?? 'N/A') ?></h6>
                                            <p class="card-text"><?= substr(strip_tags($vaga['sobreaVaga'] ?? '' ), 0, 100) . '...' ?></p>
                                            <a href="<?= baseUrl() ?>vagas/visualizar/<?= $vaga['vaga_id'] ?>" class="btn btn-sm btn-primary">Ver Detalhes</a>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="col">
                                <p>Nenhuma vaga recomendada no momento.</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Application Status -->
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h2 class="h4 mb-0">Status das Candidaturas</h2>
                        <a href="<?= baseUrl() ?>candidatos/minhasCandidaturas" class="btn btn-sm btn-outline-primary">Ver histórico</a>
                    </div>
                    
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead>
                                <tr>
                                    <th>Vaga</th>
                                    <th>Empresa</th>
                                    <th>Data da Candidatura</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($candidaturasRecentes)): ?>
                                    <?php foreach ($candidaturasRecentes as $item): ?>
                                        <tr>
                                            <td>
                                                <a href="<?= baseUrl() ?>vagas/visualizar/<?= $item['vaga']['vaga_id'] ?? '#' ?>">
                                                    <?= htmlspecialchars($item['vaga']['cargo_descricao'] ?? 'Vaga não encontrada') ?>
                                                </a>
                                            </td>
                                            <td><?= htmlspecialchars($item['vaga']['nome_fantasia'] ?? 'Empresa não encontrada') ?></td>
                                            <td><?= isset($item['candidatura']['dateCandidatura']) ? date("d/m/Y", strtotime($item['candidatura']['dateCandidatura'])) : 'N/A' ?></td>
                                            <td>
                                                <?php $statusInfo = getStatusCandidatura($item['candidatura']['status_candidatura'] ?? 0); ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="4" class="text-center">Nenhuma candidatura recente.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include_once __DIR__ . "/comuns/candidato_rodape.php"; ?>
