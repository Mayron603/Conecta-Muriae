<?php 
include_once __DIR__ . "/comuns/cabecalho.php"; 
use Core\Library\Session;

// Dicionários para tradução dos códigos
$vagas = $dados['vagas'] ?? [];
$vinculos = [
    1 => 'CLT',
    2 => 'Pessoa Jurídica (PJ)'
];

$modalidades = [
    1 => 'Presencial',
    2 => 'Remoto'
];

?>

<!-- Hero Section -->
<section class="hero-section-vagas">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <h1 class="hero-title display-4 fw-bold">Encontre sua próxima oportunidade</h1>
                <p class="hero-subtitle lead">Centenas de vagas disponíveis em Muriaé e região</p>
            </div>
            <div class="col-lg-6">
                <img src="<?= baseUrl() ?>assets/img/footer.png"
                     alt="Pessoas procurando emprego" class="img-fluid hero-img">
            </div>
        </div>
    </div>
</section>

<!-- Barra de Busca -->
<section class="search-section py-4 bg-light">
    <div class="container">
        <div class="card shadow-sm">
            <div class="card-body p-4">
                <form id="form-busca" class="row g-3">
                    <!-- ... (código do formulário de busca mantido) ... -->
                </form>
            </div>
        </div>
    </div>
</section>

<!-- Listagem de Vagas -->
<section class="vagas-section py-5">
    <div class="container">

        <?php
        // Checa por mensagens flash na sessão e as exibe
        $flash_msg = Session::get('flash_msg');
        if ($flash_msg) {
            $alert_class = ($flash_msg['tipo'] === 'success') ? 'alert-success' : 'alert-danger';
            echo "<div class='alert {$alert_class} alert-dismissible fade show' role='alert'>".
                 htmlspecialchars($flash_msg['mensagem']).
                 '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>'.
                 '</div>';
            // CORREÇÃO DEFINITIVA: Sobrescrevendo a sessão com null para limpá-la
            Session::set('flash_msg', null);
        }
        ?>

        <div class="row">
            <!-- Filtros -->
            <aside class="col-lg-3 mb-4 mb-lg-0">
                <div class="card">
                    <div class="card-header bg-white border-bottom-0">
                        <h5 class="mb-0">Filtrar Vagas</h5>
                    </div>
                    <div class="card-body">
                        <!-- ... (código dos filtros mantido) ... -->
                    </div>
                </div>
            </aside>
            
            <!-- Lista de Vagas -->
            <div class="col-lg-9">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2 class="h4 fw-bold mb-0">
                        <span id="vagas-count"><?= count($vagas) ?></span> Vagas Disponíveis
                    </h2>
                    <div class="d-flex align-items-center">
                        <span class="me-2">Ordenar por:</span>
                        <select class="form-select form-select-sm w-auto" id="select-ordem">
                            <option value="recentes">Mais recentes</option>
                            <option value="salario">Melhor remuneração</option>
                        </select>
                    </div>
                </div>
                
                <!-- Container das vagas -->
                <div class="vagas-container">
                    <?php if (empty($vagas)): ?>
                        <div class="card mb-3">
                            <div class="card-body text-center">
                                <h5 class="card-title">Nenhuma vaga encontrada</h5>
                                <p class="card-text">Não há vagas disponíveis no momento. Tente novamente mais tarde.</p>
                            </div>
                        </div>
                    <?php else: ?>
                        <?php foreach ($vagas as $vaga): ?>
                            <div class="card vaga-card mb-3">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-9">
                                            <h5 class="card-title fw-bold"><?= htmlspecialchars($vaga['cargo_descricao']) ?></h5>
                                            <h6 class="card-subtitle mb-2 text-muted"><?= htmlspecialchars($vaga['nome_fantasia']) ?></h6>
                                            <p class="card-text text-truncate"><?= htmlspecialchars($vaga['descricao']) ?></p>
                                        </div>
                                        <div class="col-md-3 text-md-end">
                                             <a href="<?= baseUrl() ?>vagas/candidatar/<?= $vaga['vaga_id'] ?>" class="btn btn-primary btn-sm mt-2">Candidatar-se</a>
                                            <a href="<?= baseUrl() ?>vagas/visualizar/<?= $vaga['vaga_id'] ?>" class="btn btn-secondary btn-sm mt-2">Ver detalhes</a>
                                        </div>
                                    </div>
                                    <div class="d-flex mt-3 text-muted">
                                        <span class="me-3"><i class="fas fa-building me-1"></i> <?= htmlspecialchars($modalidades[$vaga['modalidade']] ?? 'N/A') ?></span>
                                        <span class="me-3"><i class="fas fa-briefcase me-1"></i> <?= htmlspecialchars($vinculos[$vaga['vinculo']] ?? 'N/A') ?></span>
                                        <span class="me-3"><i class="fas fa-calendar-alt me-1"></i> Publicada em <?= !empty($vaga['dtInicio']) ? date('d/m/Y', strtotime($vaga['dtInicio'])) : 'N/A' ?></span>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
                
                <!-- Paginação -->
                <nav aria-label="Page navigation" class="mt-4">
                    <ul class="pagination justify-content-center">
                        <!-- ... (código da paginação mantido) ... -->
                    </ul>
                </nav>
            </div>
        </div>
    </div>
</section>

<script src="<?= baseUrl() ?>assets/js/vagas.js"></script>

<?php include_once __DIR__ . "/comuns/rodape.php"; ?>