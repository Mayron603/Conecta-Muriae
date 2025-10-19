<?php

// --- DADOS VINDOS DO CONTROLLER ---
$usuario = $dados['usuario'] ?? [];
$stats = $dados['stats'] ?? [
    'vagas' => 0,
    'candidatos' => 0
];

// Usar o campo 'nome' que já existe.
$nomeCompleto = trim(!empty($usuario['nome']) ? $usuario['nome'] : 'Empresa');

// Padronizar o nome do campo para 'logo' e verificar se o arquivo existe.
$logoUrl = baseUrl() . 'uploads/logos/default.png';
if (!empty($usuario['logo'])) {
    // Define ROOTPATH se não estiver definido para garantir que file_exists funcione.
    if (!defined('ROOTPATH')) {
        define('ROOTPATH', dirname(__DIR__, 4) . DIRECTORY_SEPARATOR);
    }
    $logoPath = ROOTPATH . 'public/uploads/logos/' . $usuario['logo'];
    if (file_exists($logoPath)) {
        $logoUrl = baseUrl() . 'uploads/logos/' . $usuario['logo'];
    }
}

// ADICIONADO: ID 'sidebarLogoPreview' para ser alvo do JavaScript após o upload.
if (strpos($logoUrl, 'default.png')) {
    $logoHtml = '<div id="sidebarLogoPreview" class="profile-photo-default">' .
                '<i class="fas fa-building"></i>' .
                '</div>';
} else {
    $logoHtml = '<img id="sidebarLogoPreview" src="' . $logoUrl . '" alt="Logo da Empresa" class="profile-photo">';
}

$requestUri = $_SERVER['REQUEST_URI'] ?? '';

?>

<!-- ADICIONADO: Dependências do Cropper.js necessárias para o modal de recorte -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.js"></script>

<style>
    /* Estilos da sidebar... (sem alterações) */
    :root {
        --sidebar-link-hover-bg: #f8f9fa;
        --sidebar-link-active-bg: #e9ecef;
        --sidebar-active-border-color: var(--bs-primary);
    }
    .profile-card .card-header-bg { background: linear-gradient(135deg, rgba(13, 110, 253, 0.9), rgba(10, 88, 202, 0.95)), url('https://www.toptal.com/designers/subtlepatterns/uploads/double-bubble-outline.png'); height: 110px; border-radius: var(--bs-card-inner-border-radius) var(--bs-card-inner-border-radius) 0 0; }
    .profile-photo-container { margin-top: -60px; cursor: pointer; position: relative; width: 120px; height: 120px; margin-left: auto; margin-right: auto; }
    .profile-photo, .profile-photo-default { width: 120px; height: 120px; border-radius: 50%; border: 4px solid #fff; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15); object-fit: cover; background-color: #6c757d; transition: transform 0.3s ease, box-shadow 0.3s ease; }
    .profile-photo-default { display: flex; align-items: center; justify-content: center; font-size: 4rem; color: white; }
    .profile-photo-container:hover .profile-photo { transform: scale(1.05); box-shadow: 0 6px 16px rgba(0, 0, 0, 0.2); }
    .photo-overlay { position: absolute; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(33, 37, 41, 0.6); border-radius: 50%; opacity: 0; transition: opacity 0.3s ease; display: flex; align-items: center; justify-content: center; color: white; pointer-events: none; }
    .profile-photo-container:hover .photo-overlay { opacity: 1; }
    .profile-card .card-body { padding-top: 1rem; }
    .nav-menu .list-group-item { border: 0; border-left: 4px solid transparent; border-radius: 0; padding: 0.9rem 1.25rem; transition: all 0.2s ease; color: #495057; }
    .nav-menu .list-group-item:hover { background-color: var(--sidebar-link-hover-bg); color: var(--bs-primary); }
    .nav-menu .list-group-item.active { font-weight: 600; color: var(--bs-primary); background-color: var(--sidebar-link-active-bg); border-left-color: var(--sidebar-active-border-color); }
    .nav-menu .list-group-item .fa-fw { color: #adb5bd; transition: color 0.2s ease; }
    .nav-menu .list-group-item:hover .fa-fw, .nav-menu .list-group-item.active .fa-fw { color: var(--bs-primary); }
</style>

<div class="col-lg-3">
    <div class="card shadow-sm mb-4 text-center profile-card">
        <div class="card-header-bg"></div>
        <div class="card-body">
             <div id="sidebar-logo-upload" class="profile-photo-container" onclick="document.getElementById('logo-input-file').click()" title="Clique para alterar a logo">
                <?= $logoHtml ?>
                 <div class="photo-overlay">
                     <div class="text-center">
                         <i class="fas fa-camera fa-2x"></i>
                         <div class="fw-bold mt-1 small">Alterar</div>
                     </div>
                 </div>
             </div>

            <h4 class="mb-1 mt-3"><?= htmlspecialchars($nomeCompleto) ?></h4>

             <div class="d-flex justify-content-around p-3">
                 <div class="text-center">
                     <h5 class="mb-0"><?= $stats['vagas'] ?></h5>
                     <small class="text-muted">Vagas</small>
                 </div>
                 <div class="text-center">
                     <h5 class="mb-0"><?= $stats['candidatos'] ?></h5>
                     <small class="text-muted">Candidatos</small>
                 </div>
             </div>

            <a href="<?= baseUrl() ?>empresa/vagas/add" class="btn btn-primary">
                <i class="fas fa-plus-circle me-1"></i> Nova Vaga
            </a>
        </div>
    </div>

    <div class="card shadow-sm nav-menu">
        <div class="list-group list-group-flush">
            <?php $isActive = (basename(rtrim($requestUri, '/')) === 'empresa'); ?>
            <a href="<?= baseUrl() ?>empresa" class="list-group-item list-group-item-action <?= $isActive ? 'active' : '' ?>">
                <i class="fas fa-tachometer-alt fa-fw me-2"></i>Dashboard
            </a>
            <?php $isActive = str_contains($requestUri, 'empresa/vagas'); ?>
            <a href="<?= baseUrl() ?>empresa/vagas" class="list-group-item list-group-item-action <?= $isActive ? 'active' : '' ?>">
                <i class="fas fa-briefcase fa-fw me-2"></i>Minhas Vagas
            </a>
            <?php $isActive = str_contains($requestUri, 'empresa/candidatos'); ?>
            <a href="<?= baseUrl() ?>empresa/candidatos" class="list-group-item list-group-item-action <?= $isActive ? 'active' : '' ?>">
                <i class="fas fa-users fa-fw me-2"></i>Candidatos
            </a>
            <?php $isActive = str_contains($requestUri, 'empresa/mensagens'); ?>
            <a href="<?= baseUrl() ?>mensagem/chat" class="list-group-item list-group-item-action">
                <i class="fas fa-comments fa-fw me-2"></i>Mensagens
            </a>
            </a>
            <?php $isActive = str_contains($requestUri, 'empresa/configuracoes'); ?>
            <a href="<?= baseUrl() ?>empresa/configuracoes" class="list-group-item list-group-item-action <?= $isActive ? 'active' : '' ?>">
                <i class="fas fa-cog fa-fw me-2"></i>Configurações
            </a>
        </div>
    </div>
</div>

<!-- Input de arquivo oculto para a logo -->
<form id="logo-upload-form" style="display: none;">
    <input type="file" id="logo-input-file" name="logo" accept="image/png, image/jpeg, image/gif">
</form>

<!-- ADICIONADO: Modal para recorte da imagem (vindo da página de configurações) -->
<div class="modal fade" id="cropModal" tabindex="-1" aria-labelledby="cropModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="cropModalLabel">Recortar Imagem</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="img-container">
                    <img id="imageToCrop" src="" alt="Imagem para recortar" style="max-width: 100%;">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="cropAndSave">Salvar Logo</button>
            </div>
        </div>
    </div>
</div>

<!-- ATUALIZADO: Script para usar o Cropper.js em vez do upload direto -->
<script>
document.addEventListener('DOMContentLoaded', function () {
    const input = document.getElementById('logo-input-file'); // Alvo correto
    const imageToCrop = document.getElementById('imageToCrop');
    const modalElement = document.getElementById('cropModal');
    const cropAndSaveBtn = document.getElementById('cropAndSave');
    const modal = new bootstrap.Modal(modalElement);
    let cropper;

    input.addEventListener('change', function (e) {
        const files = e.target.files;
        if (files && files.length > 0) {
            const reader = new FileReader();
            reader.onload = function (event) {
                imageToCrop.src = event.target.result;
                modal.show();
            };
            reader.readAsDataURL(files[0]);
        }
    });

    modalElement.addEventListener('shown.bs.modal', function () {
        cropper = new Cropper(imageToCrop, {
            aspectRatio: 1,
            viewMode: 1,
            dragMode: 'move',
            background: false,
            autoCropArea: 0.8,
            movable: true,
            zoomable: true,
        });
    });

    modalElement.addEventListener('hidden.bs.modal', function () {
        cropper.destroy();
        cropper = null;
        input.value = '';
    });

    cropAndSaveBtn.addEventListener('click', function () {
        if (!cropper) {
            return;
        }
        
        const button = this;
        button.disabled = true;
        button.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Salvando...';

        cropper.getCroppedCanvas({
            width: 500,
            height: 500,
            fillColor: '#fff',
            imageSmoothingEnabled: true,
            imageSmoothingQuality: 'high',
        }).toBlob(function (blob) {
            const formData = new FormData();
            formData.append('logo', blob, 'logo.png');

            fetch('<?= baseUrl() ?>empresa/salvarLogoRecortada', {
                method: 'POST',
                body: formData,
            })
            .then(response => response.json())
            .then(result => {
                if (result.success && result.url) {
                    const newUrl = result.url + '?t=' + new Date().getTime(); // Cache busting
                    
                    // Alvo principal: a imagem/ícone na sidebar
                    const sidebarLogo = document.getElementById('sidebarLogoPreview');
                    if (sidebarLogo) {
                        if (sidebarLogo.tagName.toLowerCase() === 'img'){
                            sidebarLogo.src = newUrl; 
                        } else {
                            // Se era o ícone default, substitui o div por um elemento img
                            const newImg = document.createElement('img');
                            newImg.id = 'sidebarLogoPreview';
                            newImg.src = newUrl;
                            newImg.alt = 'Logo da Empresa';
                            newImg.className = 'profile-photo';
                            sidebarLogo.parentNode.replaceChild(newImg, sidebarLogo);
                        }
                    }
                    modal.hide();
                } else {
                    alert('Erro ao salvar: ' + (result.message || 'Erro desconhecido.'));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Ocorreu um erro na requisição.');
            })
            .finally(() => {
                button.disabled = false;
                button.innerHTML = 'Salvar Logo';
            });
        }, 'image/png');
    });
});
</script>
