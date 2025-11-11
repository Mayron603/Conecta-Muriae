document.addEventListener('DOMContentLoaded', function () {

    // --- LÓGICA DO CROPPER DE LOGO ---

    // 1. Procura o input da logo na página
    const inputLogo = document.getElementById('inputLogo');

    // 2. Se o input NÃO existir, não faz nada (evita erros em outras páginas)
    if (!inputLogo) {
        return;
    }

    // 3. Pega a URL de upload que o PHP colocou no HTML
    const uploadUrl = inputLogo.dataset.uploadUrl;
    if (!uploadUrl) {
        console.error('Erro: data-upload-url não foi definido no input #inputLogo');
        return;
    }

    // 4. Pega os outros elementos do modal
    const modalCropLogo = new bootstrap.Modal(document.getElementById('modalCropLogo'));
    const imageToCrop = document.getElementById('imageToCropLogo');
    const cropAndUploadBtn = document.getElementById('cropAndUploadLogo');
    let cropper;

    // 5. Adiciona os "escutadores" de eventos
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

            // 6. USA A URL CORRETA (que lemos do atributo 'data-')
            fetch(uploadUrl, {
                method: 'POST',
                body: formData,
            })
            .then(response => response.json())
            .then(result => {
                if (result.success) {
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

    // --- FIM DA LÓGICA DO CROPPER ---

    // -----------------------------------------------------------------
    // --- Lógica do vagas.php (Resetar Modal Nova Vaga)
    // -----------------------------------------------------------------
    
    // 1. Encontra o botão "Nova Vaga"
    const btnNovaVaga = document.getElementById('btnAbrirModalNovaVaga');
    
    // 2. Encontra o elemento do modal
    const modalElement = document.getElementById('novaVagaModal');
    
    // 3. Só executa se ambos existirem na página
    if (btnNovaVaga && modalElement) {
        
        const formModal = modalElement.querySelector('form');
        const modalTitle = modalElement.querySelector('.modal-title');
        const modalSubmitBtn = formModal.querySelector('button[type="submit"]');

        // 4. Adiciona o "ouvinte" de clique no botão "Nova Vaga"
        btnNovaVaga.addEventListener('click', function() {
            
            // 5. Limpa todos os campos do formulário
            formModal.reset();
            
            // 6. Remove o input 'vaga_id' se ele existir (A PARTE MAIS IMPORTANTE)
            const hiddenVagaId = formModal.querySelector('input[name="vaga_id"]');
            if (hiddenVagaId) {
                hiddenVagaId.remove();
            }

            // 7. Garante que o input 'statusVaga' esteja presente e com valor 1
            let statusInput = formModal.querySelector('input[name="statusVaga"]');
            if (!statusInput) {
                statusInput = document.createElement('input');
                statusInput.type = 'hidden';
                statusInput.name = 'statusVaga';
                formModal.prepend(statusInput);
            }
            statusInput.value = '1';

            // 8. Reseta a interface do modal para o modo "Criar"
            if(modalTitle) modalTitle.textContent = 'Criar Nova Vaga';
            if(modalSubmitBtn) modalSubmitBtn.textContent = 'Publicar Vaga';
            
            // 9. AJUSTE: Usa a variável 'appBaseUrl' em vez de PHP
            formModal.action = appBaseUrl + 'empresa/salvar';
        });
    }
    // --- Fim da lógica do vagas.php ---

});