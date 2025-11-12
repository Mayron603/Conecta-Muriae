document.addEventListener('DOMContentLoaded', function () {
    var cropper;
    var modalCropFoto = document.getElementById('modalCropFoto');
    var imageToCrop = document.getElementById('imageToCrop');
    var inputFoto = document.getElementById('inputFoto');
    var cropAndUploadBtn = document.getElementById('cropAndUpload');
    
    var btnTriggerFotoModal = document.getElementById('btnTriggerFotoModal');

    if (btnTriggerFotoModal) {
        btnTriggerFotoModal.addEventListener('click', function (e) {
            e.preventDefault();
            inputFoto.click(); 
        });
    }

    inputFoto.addEventListener('change', function (e) {
        var files = e.target.files;
        if (files && files.length > 0) {
            var reader = new FileReader();
            reader.onload = function (event) {
                imageToCrop.src = event.target.result;
                
                var modal = new bootstrap.Modal(modalCropFoto);
                modal.show();

                if (cropper) {
                    cropper.destroy();
                }
                
                cropper = new Cropper(imageToCrop, {
                    aspectRatio: 1,
                    viewMode: 2,
                    background: false,
                    autoCropArea: 0.9,
                    responsive: true,
                });
            };
            reader.readAsDataURL(files[0]);
        }
    });

    modalCropFoto.addEventListener('hidden.bs.modal', function () {
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
            
            fetch(window.APP_BASE_URL + 'candidatos/salvarFoto', {
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
});