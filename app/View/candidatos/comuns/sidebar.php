<?php

// --- DADOS VINDOS DO CONTROLLER ---
$usuario = $dados['usuario'] ?? [];
$curriculum = $dados['curriculum'] ?? [];
$progresso = $dados['progresso_perfil'] ?? 0;
$unreadNotifications = $dados['unread_notifications'] ?? 0;

// [CORREÇÃO APLICADA AQUI]
$nomeCompleto = trim($usuario['nome'] ?? '');

// --- LÓGICA PARA EXIBIÇÃO ---
$corProgresso = 'bg-danger';
if ($progresso > 25) $corProgresso = 'bg-warning';
if ($progresso > 60) $corProgresso = 'bg-info';
if ($progresso >= 100) $corProgresso = 'bg-success';

$apresentacaoCompleta = $curriculum['apresentacaoPessoal'] ?? '';
$resumoApresentacao = $apresentacaoCompleta;
if (mb_strlen($resumoApresentacao) > 80) {
    $resumoApresentacao = mb_substr($resumoApresentacao, 0, 80) . '...';
}

$fotoUrl = baseUrl() . 'uploads/fotos_perfil/default.png';
if (!empty($curriculum['foto']) && $curriculum['foto'] !== 'default.png') {
    $fotoUrl = baseUrl() . 'uploads/fotos_perfil/' . $curriculum['foto'];
}

$fotoHtml = '<img src="' . $fotoUrl . '" alt="Foto de Perfil" class="profile-photo">';
if (strpos($fotoUrl, 'default.png')) {
    $fotoHtml = '<div class="profile-photo-default">' .
        '<i class="fas fa-user"></i>' .
        '</div>';
}

$requestUri = $_SERVER['REQUEST_URI'] ?? '';

// --- [NOVA LÓGICA PARA TRIGGER DA FOTO] ---
// Verifica se o currículo já existe (necessário para upload de foto)
$curriculoId = $curriculum['curriculum_id'] ?? null;
$isCurriculoPendente = empty($curriculoId);

$onClickAction = $isCurriculoPendente
    ? "alert('Você precisa salvar seus dados principais no menu \"Meu Currículo\" antes de adicionar uma foto.');"
    : "document.getElementById('inputFoto')?.click()";

$title = $isCurriculoPendente
    ? 'Salve seu currículo para adicionar uma foto'
    : 'Clique para alterar a foto de perfil';

?>

<div class="col-lg-3">
    <div class="card shadow-sm mb-4 text-center profile-card">
        <div class="card-header-bg"></div>

        <div class="card-body">
            <div id="sidebar-photo-upload" class="profile-photo-container" onclick="<?= $onClickAction ?>" title="<?= htmlspecialchars($title) ?>">
                <?= $fotoHtml ?>

                <div class="photo-overlay">
                    <div class="text-center">
                        <i class="fas fa-camera fa-2x"></i>
                        <div class="fw-bold mt-1 small">Alterar</div>
                    </div>
                </div>

                <?php if ($progresso >= 100) : ?>
                    <div class="profile-badge" title="Perfil Completo">
                        <i class="fas fa-check text-white fs-6"></i>
                    </div>
                <?php endif; ?>
            </div>

            <h4 class="mb-1 mt-3"><?= htmlspecialchars($nomeCompleto) ?></h4>
            <p class="text-muted small mb-3 px-2" title="<?= htmlspecialchars($apresentacaoCompleta) ?>">
                <?= htmlspecialchars(!empty(trim($resumoApresentacao)) ? $resumoApresentacao : 'Apresentação não definida') ?>
            </p>

            <div class="px-4 mb-3">
                <div class="d-flex justify-content-between small text-muted mb-1">
                    <span>Progresso</span>
                    <span><?= $progresso ?>%</span>
                </div>
                <div class="progress" title="Progresso do perfil: <?= $progresso ?>%">
                    <div class="progress-bar <?= $corProgresso ?>" role="progressbar" style="width: <?= $progresso ?>%;" aria-valuenow="<?= $progresso ?>" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
            </div>

            <a href="<?= baseUrl() ?>candidatos/curriculo" class="btn btn-primary">
                <i class="fas fa-user-edit me-1"></i> <?= ($progresso < 100) ? 'Completar Perfil' : 'Editar Perfil' ?>
            </a>
        </div>
    </div>

    <div class="card shadow-sm nav-menu">
        <div class="list-group list-group-flush">

            <?php $isActive = (basename(rtrim($requestUri, '/')) === 'candidatos'); ?>
            <a href="<?= baseUrl() ?>candidatos" class="list-group-item list-group-item-action <?= $isActive ? 'active' : '' ?>">
                <i class="fas fa-tachometer-alt fa-fw me-2"></i>Dashboard
            </a>

            <?php $isActive = str_contains($requestUri, 'candidatos/curriculo'); ?>
            <a href="<?= baseUrl() ?>candidatos/curriculo" class="list-group-item list-group-item-action <?= $isActive ? 'active' : '' ?>">
                <i class="fas fa-file-alt fa-fw me-2"></i>Meu Currículo
            </a>

            <?php $isActive = str_contains($requestUri, 'candidatos/minhasCandidaturas'); ?>
            <a href="<?= baseUrl() ?>candidatos/minhasCandidaturas" class="list-group-item list-group-item-action <?= $isActive ? 'active' : '' ?>">
                <i class="fas fa-briefcase fa-fw me-2"></i>Minhas Candidaturas
            </a>

            <?php $isActive = str_contains($requestUri, 'mensagemCandidato'); ?>
            <a href="<?= baseUrl() ?>mensagemCandidato" class="list-group-item list-group-item-action <?= $isActive ? 'active' : '' ?>">
                <i class="fas fa-comments fa-fw me-2"></i>Mensagens
            </a>

            <?php $isActive = str_contains($requestUri, 'candidatos/notificacoes'); ?>
            <a href="<?= baseUrl() ?>candidatos/notificacoes" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center <?= $isActive ? 'active' : '' ?>">
                <span><i class="fas fa-bell fa-fw me-2"></i>Notificações</span>
                <?php if (isset($unreadNotifications) && $unreadNotifications > 0) : ?>
                    <span class="badge bg-danger rounded-pill"><?= $unreadNotifications ?></span>
                <?php endif; ?>
            </a>

            <?php $isActive = str_contains($requestUri, 'candidatos/configuracoes'); ?>
            <a href="<?= baseUrl() ?>candidatos/configuracoes" class="list-group-item list-group-item-action <?= $isActive ? 'active' : '' ?>">
                <i class="fas fa-cog fa-fw me-2"></i>Configurações
            </a>

        </div>
    </div>
</div>