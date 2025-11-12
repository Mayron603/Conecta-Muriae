document.addEventListener('DOMContentLoaded', function () {

    // LÓGICA DO CROPPER DE LOGO

    const inputLogo = document.getElementById('inputLogo');

    if (!inputLogo) {
        return;
    }

    const uploadUrl = inputLogo.dataset.uploadUrl;
    if (!uploadUrl) {
        console.error('Erro: data-upload-url não foi definido no input #inputLogo');
        return;
    }

    const modalCropLogo = new bootstrap.Modal(document.getElementById('modalCropLogo'));
    const imageToCrop = document.getElementById('imageToCropLogo');
    const cropAndUploadBtn = document.getElementById('cropAndUploadLogo');
    let cropper;

    inputLogo.addEventListener('change', function (e) {
        const files = e.target.files;
        if (files && files.length > 0) {
            const reader = new FileReader();
            reader.onload = function (event) {
                imageToCrop.src = event.target.result;
                modalCropLogo.show();
            };
            reader.readAsDataURL(files[0]);
        }
    });

    document.getElementById('modalCropLogo').addEventListener('shown.bs.modal', function () {
        cropper = new Cropper(imageToCrop, {
            aspectRatio: 1 / 1,
            viewMode: 1,
            dragMode: 'move',
            background: false,
        });
    });

    document.getElementById('modalCropLogo').addEventListener('hidden.bs.modal', function () {
        if (cropper) {
            cropper.destroy();
            cropper = null;
        }
        inputLogo.value = '';
    });

    cropAndUploadBtn.addEventListener('click', function () {
        const button = this;
        button.disabled = true;
        button.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Enviando...';

        cropper.getCroppedCanvas({
            width: 400,
            height: 400,
        }).toBlob(function (blob) {
            const formData = new FormData();
            formData.append('logo', blob, 'logo_empresa.png');

            fetch(uploadUrl, {
                method: 'POST',
                body: formData,
            })
            .then(response => response.json())
            .then(result => {
                if (result.success) {
                    const newUrl = result.url + '?t=' + new Date().getTime();
                    
                    const sidebarLogo = document.getElementById('sidebarLogoPreview');
                    if (sidebarLogo) {
                        if (sidebarLogo.tagName.toLowerCase() === 'img'){
                            sidebarLogo.src = newUrl; 
                        } else {
                            const newImg = document.createElement('img');
                            newImg.id = 'sidebarLogoPreview';
                            newImg.src = newUrl;
                            newImg.alt = 'Logo da Empresa';
                            newImg.className = 'profile-photo';
                            sidebarLogo.parentNode.replaceChild(newImg, sidebarLogo);
                        }
                    }
                    modalCropLogo.hide();
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

    // -----------------------------------------------------------------
    
    const btnNovaVaga = document.getElementById('btnAbrirModalNovaVaga');
    
    const modalElement = document.getElementById('novaVagaModal');
    
    if (btnNovaVaga && modalElement) {
        
        const formModal = modalElement.querySelector('form');
        const modalTitle = modalElement.querySelector('.modal-title');
        const modalSubmitBtn = formModal.querySelector('button[type="submit"]');

        btnNovaVaga.addEventListener('click', function() {
            
            formModal.reset();
            
            const hiddenVagaId = formModal.querySelector('input[name="vaga_id"]');
            if (hiddenVagaId) {
                hiddenVagaId.remove();
            }

            let statusInput = formModal.querySelector('input[name="statusVaga"]');
            if (!statusInput) {
                statusInput = document.createElement('input');
                statusInput.type = 'hidden';
                statusInput.name = 'statusVaga';
                formModal.prepend(statusInput);
            }
            statusInput.value = '1';

            if(modalTitle) modalTitle.textContent = 'Criar Nova Vaga';
            if(modalSubmitBtn) modalSubmitBtn.textContent = 'Publicar Vaga';
            
            formModal.action = appBaseUrl + 'empresa/salvar';
        });
        
    }
    
    // --- LÓGICA PARA "OUTRO CARGO" NO MODAL DE NOVA VAGA
    const cargoSelect = document.getElementById('cargo_id');
    const outroCargoContainer = document.getElementById('outro-cargo-container');
    const outroCargoInput = document.getElementById('outro_cargo_descricao');

    if (cargoSelect && outroCargoContainer && outroCargoInput) {
        cargoSelect.addEventListener('change', function () {
            if (this.value === 'outro') {
                outroCargoContainer.style.display = 'block';
                outroCargoInput.setAttribute('required', 'required');
            } else {
                outroCargoContainer.style.display = 'none';
                outroCargoInput.removeAttribute('required');
                outroCargoInput.value = '';
            }
        });
    }

});