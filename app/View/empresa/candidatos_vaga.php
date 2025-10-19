<?php 
    // Caminho para a pasta de views
    $pathView = __DIR__ . '/../../View/';

    // Inclui o cabeçalho dinâmico da área da empresa
    require_once $pathView . 'empresa/comuns/empresa_cabecalho.php'; 
?>

<!-- Main Content -->
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h2 class="h4 mb-0">Candidatos para a Vaga: <?= htmlspecialchars($vaga['descricao']) ?></h2>
                </div>
                <div class="card-body">
                    <?php if (empty($candidatos)): ?>
                        <p class="text-center text-muted">Nenhum candidato para esta vaga ainda.</p>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Nome do Candidato</th>
                                        <th>Data da Candidatura</th>
                                        <th class="text-end">Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($candidatos as $candidato): ?>
                                        <tr>
                                            <td><?= htmlspecialchars($candidato['nome']) ?></td>
                                            <td><?= date('d/m/Y H:i', strtotime($candidato['dateCandidatura'])) ?></td>
                                            <td class="text-end">
                                                <a href="<?= baseUrl() ?>empresa/verCurriculo/<?= $candidato['curriculum_id'] ?>" class="btn btn-sm btn-primary">Ver Currículo</a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="card-footer bg-white text-center">
                    <a href="<?= baseUrl() ?>empresa/vagas" class="btn btn-secondary">Voltar para Vagas</a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php 
    // Inclui o rodapé global
    require_once $pathView . 'comuns/rodape.php'; 
?>
