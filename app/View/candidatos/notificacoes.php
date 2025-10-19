<?php 
include_once __DIR__ . "/comuns/candidato_cabecalho.php";

$notificacoes = $dados['notificacoes'] ?? [];

?>

<div class="container-fluid py-4">
    <div class="row">

        <?php include_once __DIR__ . "/comuns/sidebar.php"; ?>

        <div class="col-lg-9">
            <div class="card shadow-sm">
                <div class="card-header pb-0">
                    <h2 class="h5">Central de Notificações</h2>
                    <p class="text-sm text-muted">Todas as suas atualizações importantes aparecem aqui.</p>
                </div>
                <div class="card-body">
                    
                    <?php if (empty($notificacoes)): ?>
                        <div class="alert alert-info text-white" role="alert">
                            <i class="fas fa-info-circle me-2"></i>
                            Você ainda não tem nenhuma notificação.
                        </div>
                    <?php else: ?>
                        <ul class="list-group list-group-flush">
                            <?php foreach ($notificacoes as $notificacao): ?>
                                <li class="list-group-item d-flex justify-content-between align-items-start py-3 ps-0">
                                    <div class="d-flex align-items-center">
                                        <div class="me-3">
                                            <i class="fas fa-bell text-primary"></i>
                                        </div>
                                        <div class="d-flex flex-column">
                                            <h6 class="mb-1 text-dark font-weight-bold">
                                                <?= htmlspecialchars($notificacao['titulo']) ?>
                                            </h6>
                                            <span class="text-sm">
                                                <?= htmlspecialchars($notificacao['mensagem']) ?>
                                            </span>
                                            <a href="<?= baseUrl() . ltrim($notificacao['link'], '/') ?>" class="text-sm font-weight-bold text-primary mt-1">Ver Detalhes</a>
                                        </div>
                                    </div>
                                    <div class="text-end text-sm text-muted">
                                        <span><?= date('d/m/Y H:i', strtotime($notificacao['dataCriacao'])) ?></span>
                                    </div>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>

                </div>
            </div>
        </div>
    </div>
</div>

<?php 
include_once __DIR__ . "/comuns/candidato_rodape.php"; 
?>
