<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.js"></script>

<input class="form-control d-none" 
       type="file" 
       id="inputLogo" 
       data-upload-url="<?= baseUrl() ?>empresa/salvarLogoRecortada" 
       accept="image/png, image/jpeg, image/gif">

<div class="modal fade" id="modalCropLogo" tabindex="-1" aria-labelledby="modalCropLogoLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalCropLogoLabel">Recortar Logo</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p class="text-muted">Ajuste o quadrado para selecionar a melhor parte da sua logo.</p>
                <div>
                    <img id="imageToCropLogo" src="" style="max-width: 100%;">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="cropAndUploadLogo">Salvar Logo</button>
            </div>
        </div>
    </div>
</div>

<script src="<?= baseUrl() ?>assets/js/empresa.js"></script>


</body>
</html>