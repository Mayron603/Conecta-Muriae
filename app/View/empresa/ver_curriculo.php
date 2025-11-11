<?php
// Caminho para a pasta de views
$pathView = __DIR__ . '/../../View/';

// Inclui o cabeçalho dinâmico da área da empresa
require_once $pathView . 'empresa/comuns/empresa_cabecalho.php';

$curriculo = $dados['curriculo'] ?? [];
$escolaridades = $curriculo['escolaridades'] ?? [];
$experiencias = $curriculo['experiencias'] ?? [];
$qualificacoes = $curriculo['qualificacoes'] ?? [];

function formatarDataPeriodo($mes, $ano) {
    if (empty($ano)) return 'Não informado';
    return str_pad($mes, 2, '0', STR_PAD_LEFT) . '/' . $ano;
}

function formatarData($data) {
    if (empty($data) || $data === '0000-00-00') return 'Não informado';
    try {
        return (new DateTime($data))->format('d/m/Y');
    } catch (Exception $e) {
        return 'Data inválida';
    }
}
?>

<div class="container-fluid py-5" style="background-color: #f8f9fa;">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap">
            <h1 class="display-5 text-dark mb-2 mb-md-0">Currículo de <?= htmlspecialchars($curriculo['nome'] ?? 'Candidato'); ?></h1>
            <div class="d-flex gap-2">
                <?php if (!empty($curriculo['arquivo_curriculo'])) : ?>
                    <a href="<?= baseUrl() . 'uploads/curriculos/' . htmlspecialchars($curriculo['arquivo_curriculo']); ?>" target="_blank" class="btn btn-primary">
                       <i class="fas fa-file-download me-2"></i>Baixar Currículo
                    </a>
                <?php endif; ?>
            <a href="<?= baseUrl() . 'mensagem/iniciarConversa/' . ($curriculo['curriculum_id'] ?? '#') ?>" class="btn btn-info text-white">
                <i class="fas fa-paper-plane me-2"></i>Enviar Mensagem
                </a>
                <a href="javascript:history.back()" class="btn btn-secondary"><i class="fas fa-arrow-left me-2"></i>Voltar</a>
            </div>
        </div>

        <!-- Dados Pessoais -->
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white border-0 py-3">
                <h5 class="mb-0 text-primary"><i class="fas fa-user-tie me-2"></i>Dados Pessoais</h5>
            </div>
            <div class="card-body p-4">
                <div class="row">
                    <div class="col-md-8">
                        <p class="mb-1 text-muted">Nome Completo</p>
                        <p class="fw-bold"><?= htmlspecialchars($curriculo['nome'] ?? 'Não informado'); ?></p>
                    </div>
                    <div class="col-md-4">
                        <p class="mb-1 text-muted">Data de Nascimento</p>
                        <p class="fw-bold"><?= formatarData($curriculo['dataNascimento'] ?? null); ?></p>
                    </div>
                    <div class="col-md-4">
                        <p class="mb-1 text-muted">E-mail</p>
                        <p class="fw-bold"><?= htmlspecialchars($curriculo['email'] ?? 'Não informado'); ?></p>
                    </div>
                    <div class="col-md-4">
                        <p class="mb-1 text-muted">Celular</p>
                        <p class="fw-bold"><?= htmlspecialchars($curriculo['celular'] ?? 'Não informado'); ?></p>
                    </div>
                     <div class="col-md-4">
                        <p class="mb-1 text-muted">Endereço</p>
                        <p class="fw-bold">
                            <?= htmlspecialchars($curriculo['logradouro'] ?? ''); ?>, <?= htmlspecialchars($curriculo['numero'] ?? 'S/N'); ?> - <?= htmlspecialchars($curriculo['bairro'] ?? ''); ?>, CEP: <?= htmlspecialchars($curriculo['cep'] ?? ''); ?>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Apresentação Pessoal -->
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white border-0 py-3">
                <h5 class="mb-0 text-primary"><i class="fas fa-bullhorn me-2"></i>Resumo Profissional</h5>
            </div>
            <div class="card-body p-4">
                <p class="lead">
                    <?= nl2br(htmlspecialchars($curriculo['apresentacaoPessoal'] ?? 'Nenhuma apresentação pessoal cadastrada.')); ?>
                </p>
            </div>
        </div>

        <!-- Escolaridade -->
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white border-0 py-3">
                <h5 class="mb-0 text-primary"><i class="fas fa-graduation-cap me-2"></i>Escolaridade</h5>
            </div>
            <div class="card-body p-4">
                <?php if (!empty($escolaridades)): ?>
                    <ul class="list-group list-group-flush">
                        <?php foreach ($escolaridades as $item): ?>
                            <li class="list-group-item px-0">
                                <p class="mb-1"><span class="fw-bold">Nível:</span> <?= htmlspecialchars($item['nivel_escolaridade']); ?></p>
                                <p class="mb-1"><span class="fw-bold">Curso:</span> <?= htmlspecialchars($item['descricao']); ?></p>
                                <p class="mb-1"><span class="fw-bold">Instituição:</span> <?= htmlspecialchars($item['instituicao']); ?></p>
                                <p class="mb-0 text-muted">
                                    <i class="fas fa-calendar-alt me-2"></i>
                                    Período: <?= formatarDataPeriodo($item['inicioMes'], $item['inicioAno']); ?> a <?= $item['fimAno'] ? formatarDataPeriodo($item['fimMes'], $item['fimAno']) : 'Atual'; ?>
                                </p>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <p class="text-muted">Nenhuma escolaridade cadastrada.</p>
                <?php endif; ?>
            </div>
        </div>

        <!-- Experiência Profissional -->
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white border-0 py-3">
                <h5 class="mb-0 text-primary"><i class="fas fa-briefcase me-2"></i>Experiência Profissional</h5>
            </div>
            <div class="card-body p-4">
                <?php if (!empty($experiencias)): ?>
                    <ul class="list-group list-group-flush">
                        <?php foreach ($experiencias as $item): ?>
                         <li class="list-group-item px-0">
                            <p class="mb-1"><span class="fw-bold">Cargo:</span> <?= htmlspecialchars($item['cargo_nome'] ?? $item['cargoDescricao']); ?></p>
                            <p class="mb-1"><span class="fw-bold">Empresa:</span> <?= htmlspecialchars($item['estabelecimento']); ?></p>
                            <p class="mb-0 text-muted">
                                <i class="fas fa-calendar-alt me-2"></i>
                                <span class="fw-bold">Período:</span> <?= formatarDataPeriodo($item['inicioMes'], $item['inicioAno']); ?> a <?= $item['fimAno'] ? formatarDataPeriodo($item['fimMes'], $item['fimAno']) : 'Atual'; ?>
                            </p>
                            <p class="mt-2"><span class="fw-bold">Atividades Exercidas:</span><br><?= nl2br(htmlspecialchars($item['atividadesExercidas'])); ?></p>
                         </li>
                        <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <p class="text-muted">Nenhuma experiência profissional cadastrada.</p>
                <?php endif; ?>
            </div>
        </div>

        <!-- Qualificações e Cursos -->
        <div class="card shadow-sm">
            <div class="card-header bg-white border-0 py-3">
                <h5 class="mb-0 text-primary"><i class="fas fa-star me-2"></i>Qualificações e Cursos</h5>
            </div>
            <div class="card-body p-4">
                <?php if (!empty($qualificacoes)): ?>
                    <ul class="list-group list-group-flush">
                        <?php foreach ($qualificacoes as $item): ?>
                         <li class="list-group-item px-0">
                            <p class="mb-1"><span class="fw-bold">Curso/Descrição:</span> <?= htmlspecialchars($item['descricao']); ?></p>
                            <p class="mb-1"><span class="fw-bold">Instituição:</span> <?= htmlspecialchars($item['estabelecimento']); ?></p>
                            <p class="mb-0 text-muted"><span class="fw-bold">Carga (h):</span> <?= htmlspecialchars($item['cargaHoraria']); ?> horas</p>
                            <p class="mb-0 text-muted"><span class="fw-bold">Conclusão:</span> <?= htmlspecialchars($item['mes']); ?>/<?= htmlspecialchars($item['ano']); ?></p>
                         </li>
                        <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <p class="text-muted">Nenhuma qualificação ou curso cadastrado.</p>
                <?php endif; ?>
            </div>
        </div>

    </div>
</div>
<?php
// Inclui o rodapé global
require_once $pathView . 'comuns/rodape.php';
?>