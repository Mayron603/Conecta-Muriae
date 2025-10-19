<?php 
include_once __DIR__ . "/comuns/candidato_cabecalho.php";

$mapaVinculo = [
    '1' => 'CLT',
    '2' => 'PJ',
    '3' => 'Estágio',
    '4' => 'Temporário',
    '5' => 'Freelance'
];

$candidaturas = $aDados['candidaturas'] ?? [];

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

        <div class="col-lg-9">
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <h2 class="h4 mb-4">Minhas Candidaturas</h2>
                    
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Vaga</th>
                                    <th>Empresa</th>
                                    <th>Data da Candidatura</th>
                                    <th>Status</th>
                                    <th>Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($candidaturas)): ?>
                                    <tr>
                                        <td colspan="5" class="text-center py-5">
                                            <i class="fas fa-folder-open fa-3x text-muted mb-3"></i>
                                            <p class="text-muted mb-0">Você ainda não se candidatou a nenhuma vaga.</p>
                                            <a href="<?= baseUrl() ?>vagas" class="btn btn-primary mt-3">Buscar Vagas</a>
                                        </td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($candidaturas as $item): ?>
                                        <tr>
                                            <td>
                                                <h6 class="mb-0 fw-bold"><?= htmlspecialchars($item['vaga']['cargo_descricao'] ?? 'Vaga não encontrada') ?></h6>
                                                <small class="text-muted"><?= htmlspecialchars($mapaVinculo[$item['vaga']['vinculo']] ?? 'Não especificado') ?></small>
                                            </td>
                                            <td><?= htmlspecialchars($item['vaga']['nome_fantasia'] ?? 'Empresa não encontrada') ?></td>
                                            <td><?= !empty($item['candidatura']['dateCandidatura']) ? date("d/m/Y", strtotime($item['candidatura']['dateCandidatura'])) : '<span class="text-muted">N/A</span>' ?></td>
                                            <td>
                                                <?php $statusInfo = getStatusCandidatura($item['candidatura']['status_candidatura'] ?? 0); ?>
                                                <span class="badge <?= $statusInfo['class'] ?>"><?= $statusInfo['text'] ?></span>
                                            </td>
                                            <td>
                                                <a href="<?= baseUrl() ?>vagas/visualizar/<?= $item['vaga']['vaga_id'] ?? '#' ?>" class="btn btn-sm btn-outline-primary">Ver Vaga</a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
