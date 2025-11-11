<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.js"></script>

<input class="form-control d-none" 
       type="file" 
       id="inputFoto" 
       data-upload-url="<?= baseUrl() ?>candidatos/salvarFoto" 
       accept="image/png, image/jpeg, image/gif">

<div class="modal fade" id="modalCropFoto" tabindex="-1" aria-labelledby="modalCropLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalCropLabel">Recortar Foto de Perfil</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div>
                    <img id="imageToCrop" src="" style="max-width: 100%;">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="cropAndUpload">Salvar Foto</button>
            </div>
        </div>
    </div>
</div>

<script src="<?= baseUrl() ?>assets/js/usuario.js"></script>

</body>
</html>