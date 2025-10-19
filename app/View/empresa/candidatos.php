<?php 
    $pathView = __DIR__ . '/../../View/';
    require_once $pathView . 'empresa/comuns/empresa_cabecalho.php'; 
?>

<div class="container-fluid py-4">
    <div class="row">
        <?php 
            require_once $pathView . 'empresa/comuns/sidebar.php'; 
        ?>

        <div class="col-lg-9">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0">Candidatos</h1>
                <div>
                    <button class="btn btn-outline-secondary" id="filter-btn">
                        <i class="fas fa-filter me-1"></i> Filtros
                    </button>
                </div>
            </div>

            <div class="card shadow-sm mb-4 d-none" id="filter-card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <label for="vagaFilter" class="form-label">Filtrar por Vaga</label>
                            <select id="vagaFilter" class="form-select">
                                <option value="all" selected>Todas as Vagas</option>
                                <?php foreach($dados['vagas'] as $vaga): ?>
                                    <option value="<?= $vaga['vaga_id'] ?>"><?= htmlspecialchars($vaga['descricao']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle" id="candidatos-table">
                            <thead class="table-light">
                                <tr>
                                    <th>Candidato</th>
                                    <th>Vaga Aplicada</th>
                                    <th>Data da Aplicação</th>
                                    <th>Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($dados['candidatos'])): ?>
                                    <tr><td colspan="4" class="text-center">Nenhum candidato encontrado.</td></tr>
                                <?php else: ?>
                                    <?php foreach ($dados['candidatos'] as $candidato): ?>
                                        <tr data-vaga-id="<?= $candidato['vaga_id'] ?>">
                                            <td><?= htmlspecialchars($candidato['candidato_nome']) ?></td>
                                            <td><?= htmlspecialchars($candidato['vaga_descricao']) ?></td>
                                            <td><?= date('d/m/Y', strtotime($candidato['dateCandidatura'])) ?></td>
                                            <td>
                                                <a href="<?= baseUrl() ?>empresa/verCurriculo/<?= $candidato['curriculum_id'] ?>" class="btn btn-sm btn-outline-primary">Ver Currículo</a>
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

<script>
document.addEventListener('DOMContentLoaded', function () {
    const filterBtn = document.getElementById('filter-btn');
    const filterCard = document.getElementById('filter-card');
    const vagaFilter = document.getElementById('vagaFilter');
    const tableRows = document.querySelectorAll('#candidatos-table tbody tr');

    filterBtn.addEventListener('click', () => {
        filterCard.classList.toggle('d-none');
    });

    vagaFilter.addEventListener('change', function() {
        const selectedVagaId = this.value;

        tableRows.forEach(row => {
            const rowVagaId = row.getAttribute('data-vaga-id');
            if (selectedVagaId === 'all' || selectedVagaId === rowVagaId) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });
});
</script>

<?php 
    require_once $pathView . 'comuns/rodape.php'; 
?>