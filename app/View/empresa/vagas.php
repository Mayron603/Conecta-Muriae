<?php 
    $pathView = __DIR__ . '/../../View/';
    require_once $pathView . 'empresa/comuns/empresa_cabecalho.php'; 

    $flashMessage = Core\Library\Session::get('flash_msg');
    if ($flashMessage) {
        Core\Library\Session::destroy('flash_msg');
    }

    $statusVagas = [
        1 => 'Pré-vaga',
        11 => 'Em aberto',
        91 => 'Suspensa',
        99 => 'Arquivada'
    ];
    $statusClasses = [
        1 => 'bg-secondary',
        11 => 'bg-success',
        91 => 'bg-warning text-dark',
        99 => 'bg-dark'
    ];
?>

<div class="container-fluid py-4">
    <div class="row">
        <?php 
            require_once $pathView . 'empresa/comuns/sidebar.php'; 
        ?>

        <div class="col-lg-9">
            <?php if (isset($flashMessage) && $flashMessage): ?>
                <div class="alert alert-<?= $flashMessage['tipo'] == 'error' ? 'danger' : 'success' ?> alert-dismissible fade show" role="alert">
                    <?= htmlspecialchars($flashMessage['mensagem']) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0">Minhas Vagas</h1>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#novaVagaModal">
                    <i class="fas fa-plus me-1"></i> Nova Vaga
                </button>
            </div>
            
            <ul class="nav nav-tabs mb-3" id="vagasTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="ativas-tab" data-bs-toggle="tab" data-bs-target="#ativas" type="button" role="tab" aria-controls="ativas" aria-selected="true">Vagas Ativas</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="arquivadas-tab" data-bs-toggle="tab" data-bs-target="#arquivadas" type="button" role="tab" aria-controls="arquivadas" aria-selected="false">Vagas Arquivadas</button>
                </li>
            </ul>

            <div class="tab-content" id="vagasTabContent">
                <div class="tab-pane fade show active" id="ativas" role="tabpanel" aria-labelledby="ativas-tab">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Título da Vaga</th>
                                            <th>Candidatos</th>
                                            <th>Status</th>
                                            <th>Publicada em</th>
                                            <th>Ações</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (empty($dados['vagas_ativas'])):
 ?>
                                            <tr><td colspan="5" class="text-center">Nenhuma vaga ativa encontrada.</td></tr>
                                        <?php else: ?>
                                            <?php foreach ($dados['vagas_ativas'] as $vaga): ?>
                                                <tr>
                                                    <td>
                                                        <h6 class="mb-0"><?= htmlspecialchars($vaga['descricao']) ?></h6>
                                                        <small class="text-muted"><?= htmlspecialchars(isset($vaga['cargo_descricao']) ? $vaga['cargo_descricao'] : 'N/A') ?></small>
                                                    </td>
                                                    <td><span class="badge rounded-pill bg-primary bg-opacity-25 text-primary px-2 py-1 fw-normal"><i class="fas fa-users me-1"></i> <?= isset($vaga['num_candidatos']) ? $vaga['num_candidatos'] : 0 ?></span></td>
                                                    <td>
                                                        <span class="badge <?= isset($statusClasses[$vaga['statusVaga']]) ? $statusClasses[$vaga['statusVaga']] : 'bg-light text-dark' ?>"><?= htmlspecialchars(isset($statusVagas[$vaga['statusVaga']]) ? $statusVagas[$vaga['statusVaga']] : 'N/A') ?></span>
                                                    </td>
                                                    <td><?= htmlspecialchars(date('d/m/Y', strtotime($vaga['dtInicio']))) ?></td>
                                                    <td>
                                                        <div class="dropdown">
                                                            <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="fas fa-ellipsis-h"></i></button>
                                                            <ul class="dropdown-menu">
                                                                <li><a class="dropdown-item" href="<?= baseUrl() ?>empresa/editar/<?= $vaga['vaga_id'] ?>"><i class="fas fa-edit me-2"></i>Editar</a></li>
                                                                <li><hr class="dropdown-divider"></li>
                                                                <?php if ($vaga['statusVaga'] != 91): ?>
                                                                    <li><form action="<?= baseUrl() ?>empresa/atualizarStatus" method="POST"><input type="hidden" name="vaga_id" value="<?= $vaga['vaga_id'] ?>"><input type="hidden" name="statusVaga" value="91"><button type="submit" class="dropdown-item">Suspender</button></form></li>
                                                                <?php endif; ?>
                                                                <li><hr class="dropdown-divider"></li>
                                                                <li><form action="<?= baseUrl() ?>empresa/excluir" method="POST"><input type="hidden" name="vaga_id" value="<?= $vaga['vaga_id'] ?>"><button type="submit" class="dropdown-item text-danger" onclick="return confirm('Tem certeza que deseja arquivar esta vaga?');"><i class="fas fa-archive me-2"></i>Arquivar</button></form></li>
                                                            </ul>
                                                        </div>
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

                <div class="tab-pane fade" id="arquivadas" role="tabpanel" aria-labelledby="arquivadas-tab">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Título da Vaga</th>
                                            <th>Status</th>
                                            <th>Arquivada em</th>
                                            <th>Ações</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (empty($dados['vagas_arquivadas'])):
 ?>
                                            <tr><td colspan="4" class="text-center">Nenhuma vaga arquivada encontrada.</td></tr>
                                        <?php else: ?>
                                            <?php foreach ($dados['vagas_arquivadas'] as $vaga): ?>
                                                <tr>
                                                    <td>
                                                        <h6 class="mb-0"><?= htmlspecialchars($vaga['descricao']) ?></h6>
                                                        <small class="text-muted"><?= htmlspecialchars(isset($vaga['cargo_descricao']) ? $vaga['cargo_descricao'] : 'N/A') ?></small>
                                                    </td>
                                                    <td>
                                                        <span class="badge <?= isset($statusClasses[$vaga['statusVaga']]) ? $statusClasses[$vaga['statusVaga']] : 'bg-light text-dark' ?>"><?= htmlspecialchars(isset($statusVagas[$vaga['statusVaga']]) ? $statusVagas[$vaga['statusVaga']] : 'N/A') ?></span>
                                                    </td>
                                                    <td><?= htmlspecialchars(date('d/m/Y', strtotime($vaga['dtFim']))) ?></td>
                                                    <td>
                                                        <form action="<?= baseUrl() ?>empresa/atualizarStatus" method="POST">
                                                            <input type="hidden" name="vaga_id" value="<?= $vaga['vaga_id'] ?>">
                                                            <input type="hidden" name="statusVaga" value="11">
                                                            <button type="submit" class="btn btn-sm btn-outline-success">Reabrir Vaga</button>
                                                        </form>
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
    </div>
</div>

<?php 
    $pathView = __DIR__ . '/../../View/';
    require_once $pathView . 'empresa/comuns/empresa_modal_nova_vaga.php';
    require_once $pathView . 'comuns/rodape.php'; 
?>