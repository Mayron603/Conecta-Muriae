<?php 
    $pathView = __DIR__ . '/../../View/';
    // Carrega o cabeçalho específico da empresa, que pode conter scripts globais.
    require_once $pathView . 'empresa/comuns/empresa_cabecalho.php'; 

    $flashMessage = Core\Library\Session::get('flash_msg');
    if ($flashMessage) {
        Core\Library\Session::destroy('flash_msg');
    }
?>

<!-- Adicionando a biblioteca Cropper.js -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.js"></script>

<div class="container-fluid py-4">
    <div class="row">
        <?php require_once $pathView . 'empresa/comuns/sidebar.php'; ?>

        <div class="col-lg-9">
            <?php if (isset($flashMessage) && $flashMessage): ?>
                <div id="flashMessage" class="alert alert-<?= $flashMessage['tipo'] == 'error' ? 'danger' : 'success' ?> alert-dismissible fade show" role="alert">
                    <?= htmlspecialchars($flashMessage['mensagem']) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <!-- Card para alterar a Logo -->
            <div class="card shadow-sm mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Logo da Empresa</h5>
                </div>
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-3 text-center">
                            <img id="logoPreview" src="<?= (!empty($dados['usuario']['logo'])) ? baseUrl() . 'uploads/logos/' . htmlspecialchars($dados['usuario']['logo']) : 'https://via.placeholder.com/150' ?>" alt="Logo da Empresa" class="img-thumbnail rounded-circle" style="width: 150px; height: 150px; object-fit: cover;">
                        </div>
                        <div class="col-md-9">
                            <label for="logo" class="form-label">Alterar logo</label>
                            <input class="form-control d-none" type="file" id="logo" accept="image/png, image/jpeg, image/gif">
                            <button type="button" class="btn btn-primary" onclick="document.getElementById('logo').click();">
                                <i class="fas fa-upload me-2"></i> Escolher Nova Logo
                            </button>
                            <div class="form-text mt-2">Você poderá recortar a imagem antes de salvar.</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card para outras informações -->
            <div class="card shadow-sm">
                <div class="card-header">
                    <h5 class="mb-0">Informações da Empresa</h5>
                </div>
                <div class="card-body">
                    <form action="<?= baseUrl() ?>empresa/salvarConfiguracoes" method="post">
                        <div class="mb-3">
                            <label for="nome" class="form-label">Nome da Empresa</label>
                            <input type="text" class="form-control" id="nome" name="nome" value="<?= htmlspecialchars($dados['usuario']['nome'] ?? '') ?>">
                        </div>
                        <div class="mb-3">
                            <label for="sobre" class="form-label">Sobre a Empresa</label>
                            <textarea class="form-control" id="sobre" name="sobre" rows="5"><?= htmlspecialchars($dados['usuario']['sobre'] ?? '') ?></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="website" class="form-label">Website</label>
                            <input type="url" class="form-control" id="website" name="website" value="<?= htmlspecialchars($dados['usuario']['website'] ?? '') ?>" placeholder="https://suaempresa.com.br">
                        </div>
                        <hr>
                        <button type="submit" class="btn btn-primary">Salvar Informações</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal para recorte da imagem -->
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

<script>
document.addEventListener('DOMContentLoaded', function () {
    const input = document.getElementById('logo');
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
        input.value = ''; // Limpa o input para permitir selecionar o mesmo arquivo novamente
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
                    // Atualiza a imagem na página de configurações
                    document.getElementById('logoPreview').src = newUrl;
                    // Atualiza a imagem na sidebar
                    const sidebarLogo = document.getElementById('sidebarLogoPreview');
                    if (sidebarLogo) {
                        if (sidebarLogo.tagName.toLowerCase() === 'img'){
                           sidebarLogo.src = newUrl; 
                        } else {
                            // Recria o elemento img se era o ícone default
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

<?php 
    require_once $pathView . 'comuns/rodape.php'; 
?>