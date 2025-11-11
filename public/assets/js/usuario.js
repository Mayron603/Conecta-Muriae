/**
 * Função para mostrar/esconder o campo "Outro Cargo"
 */
function toggleOutroCargo(selectElement, targetId) {
    const targetDiv = document.getElementById(targetId);
    if (!targetDiv) return; // Se o elemento não existir, não faz nada

    if (selectElement.value === "") {
        targetDiv.style.display = 'block';
    } else {
        targetDiv.style.display = 'none';
        const input = targetDiv.querySelector('input[name="cargoDescricao"]');
        if (input) {
            input.value = '';
        }
    }
}
/**
 * Executa o código quando o DOM (página) estiver pronto
 */
document.addEventListener('DOMContentLoaded', function () {

    // --- LÓGICA DO CROPPER DE FOTO ---

    // 1. Procura o input da foto na página
    const inputFoto = document.getElementById('inputFoto');

    // 2. Se o input existir (estamos na página de currículo), executa o script
    if (inputFoto) {

        // 3. Pega a URL de upload que o PHP colocou no HTML
        const uploadUrl = inputFoto.dataset.uploadUrl;
        
        // Se não encontrar a URL, avisa no console e para
        if (!uploadUrl) {
            console.error('Erro: data-upload-url não foi definido no input #inputFoto');
            return;
        }

        // 4. Pega os outros elementos do modal
        const modalCropFoto = new bootstrap.Modal(document.getElementById('modalCropFoto'));
        const imageToCrop = document.getElementById('imageToCrop');
        const cropAndUploadBtn = document.getElementById('cropAndUpload');
        let cropper;

        // 5. Adiciona os "escutadores" de eventos
        inputFoto.addEventListener('change', function (e) {
            const files = e.target.files;
            if (files && files.length > 0) {
                const reader = new FileReader();
                reader.onload = function (event) {
                    imageToCrop.src = event.target.result;
                    modalCropFoto.show();
                };
                reader.readAsDataURL(files[0]);
            }
        });

        document.getElementById('modalCropFoto').addEventListener('shown.bs.modal', function () {
            cropper = new Cropper(imageToCrop, {
                aspectRatio: 1 / 1,
                viewMode: 1,
                dragMode: 'move',
                background: false,
            });
        });

        document.getElementById('modalCropFoto').addEventListener('hidden.bs.modal', function () {
            if (cropper) {
                cropper.destroy();
                cropper = null;
            }
            inputFoto.value = '';
        });

        cropAndUploadBtn.addEventListener('click', function () {
            this.disabled = true;
            this.innerHTML = 'Enviando...';

            cropper.getCroppedCanvas({
                width: 400,
                height: 400,
            }).toBlob(function (blob) {
                const formData = new FormData();
                formData.append('foto', blob, 'foto_perfil.jpg');
                
                // 6. USA A URL CORRETA (que lemos do atributo 'data-')
                fetch(uploadUrl, {
                    method: 'POST',
                    body: formData,
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        window.location.reload();
                    } else {
                        alert(data.message || 'Ocorreu um erro ao enviar a foto.');
                        cropAndUploadBtn.disabled = false;
                        cropAndUploadBtn.innerHTML = 'Salvar Foto';
                    }
                })
                .catch(error => {
                    console.error('Erro:', error);
                    alert('Ocorreu um erro de comunicação com o servidor.');
                    cropAndUploadBtn.disabled = false;
                    cropAndUploadBtn.innerHTML = 'Salvar Foto';
                });

            }, 'image/jpeg');
        });
    }

    const chatMessages = document.getElementById('chat-messages');
    if (chatMessages) {
        chatMessages.scrollTop = chatMessages.scrollHeight;
    }
});