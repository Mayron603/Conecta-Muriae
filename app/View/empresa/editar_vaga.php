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
                <h1 class="h3 mb-0">Editar Vaga</h1>
                <a href="<?= baseUrl() ?>empresa/vagas" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-1"></i> Voltar para Vagas
                </a>
            </div>

            <div class="card shadow-sm">
                <div class="card-body">
                    <form action="<?= baseUrl() ?>empresa/atualizar" method="POST">
                        <input type="hidden" name="vaga_id" value="<?= $vaga['vaga_id'] ?>">
                        <div class="row g-3">
                            <div class="col-md-12">
                                <label for="descricao" class="form-label">Título da Vaga*</label>
                                <input type="text" class="form-control" id="descricao" name="descricao" value="<?= htmlspecialchars($vaga['descricao']) ?>" required>
                            </div>
                            
                            <div class="col-md-6">
                                <label for="vinculo" class="form-label">Vínculo*</label>
                                <select class="form-select" id="vinculo" name="vinculo" required>
                                    <option value="1" <?= $vaga['vinculo'] == 1 ? 'selected' : '' ?>>CLT</option>
                                    <option value="2" <?= $vaga['vinculo'] == 2 ? 'selected' : '' ?>>Pessoa Jurídica (PJ)</option>
                                </select>
                            </div>
                            
                            <div class="col-md-6">
                                <label for="modalidade" class="form-label">Modalidade*</label>
                                <select class="form-select" id="modalidade" name="modalidade" required>
                                    <option value="1" <?= $vaga['modalidade'] == 1 ? 'selected' : '' ?>>Presencial</option>
                                    <option value="2" <?= $vaga['modalidade'] == 2 ? 'selected' : '' ?>>Remoto</option>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label for="salario" class="form-label">Salário (Opcional)</label>
                                <input type="number" class="form-control" id="salario" name="salario" value="<?= htmlspecialchars($vaga['salario'] ?? '') ?>" placeholder="Ex: 3500.00" step="0.01" min="0">
                                <div class="form-text">Deixe em branco se for "A combinar".</div>
                            </div>
                            <div class="col-md-6">
                                <label for="dtInicio" class="form-label">Data de Início*</label>
                                <input type="date" class="form-control" id="dtInicio" name="dtInicio" value="<?= $vaga['dtInicio'] ?>" required>
                            </div>

                            <div class="col-md-6">
                                <label for="dtFim" class="form-label">Data de Fim*</label>
                                <input type="date" class="form-control" id="dtFim" name="dtFim" value="<?= $vaga['dtFim'] ?>" required>
                            </div>
                            
                            <div class="col-md-12">
                                <label for="cargo_id" class="form-label">Cargo*</label>
                                <select class="form-select" id="cargo_id" name="cargo_id" required>
                                    <?php foreach ($cargos as $cargo): ?>
                                        <option value="<?= $cargo['cargo_id'] ?>" <?= $vaga['cargo_id'] == $cargo['cargo_id'] ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($cargo['descricao'])
                                            ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            
                            <div class="col-12">
                                <label for="sobreaVaga" class="form-label">Sobre a Vaga*</label>
                                <textarea class="form-control" id="sobreaVaga" name="sobreaVaga" rows="5" required><?= htmlspecialchars($vaga['sobreaVaga']) ?></textarea>
                            </div>

                        </div>

                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary">Salvar Alterações</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php 
    require_once $pathView . 'comuns/rodape.php'; 
    
?>