function toggleOutroCargo(selectElement, targetId) {
    const targetDiv = document.getElementById(targetId);
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

function openEditExperienciaModal(experienciaId) {
    if (!experienciaId) return;
    
    const url = window.APP_BASE_URL + 'candidatos/getExperiencia/' + experienciaId;

    fetch(url)
        .then(response => {
            if (!response.ok) {
                throw new Error('Erro ao buscar dados da experiência.');
            }
            return response.json();
        })
        .then(data => {
            if(data.erro) {
                alert(data.erro);
                return;
            }

            const modal = new bootstrap.Modal(document.getElementById('modalEditarExperiencia'));
            
            document.getElementById('edit_curriculum_experiencia_id').value = data.curriculum_experiencia_id;
            document.getElementById('edit_empresa').value = data.empresa;
            document.getElementById('edit_cargo_id').value = data.cargo_id;
            document.getElementById('edit_cargoDescricao').value = data.cargoDescricao || '';
            document.getElementById('edit_inicioMes').value = data.inicioMes;
            document.getElementById('edit_inicioAno').value = data.inicioAno;
            document.getElementById('edit_fimMes').value = data.fimMes || '';
            document.getElementById('edit_fimAno').value = data.fimAno || '';
            document.getElementById('edit_trabalhoAtual').checked = data.trabalhoAtual == 1;
            document.getElementById('edit_atividades').value = data.atividades;

            toggleOutroCargo(document.getElementById('edit_cargo_id'), 'editOutroCargoContainer');
            
            modal.show();
        })
        .catch(error => {
            console.error('Erro no fetch:', error);
            alert('Não foi possível carregar os dados para edição.');
        });
}

function openConfirmDeleteModal(id, tipo, nome) {
    const modalElement = document.getElementById('confirmDeleteModal');
    const modal = bootstrap.Modal.getInstance(modalElement) || new bootstrap.Modal(modalElement);
    
    const form = modalElement.querySelector('#deleteForm');
    const message = modalElement.querySelector('#deleteMessage');


    form.action = window.APP_BASE_URL + 'candidatos/excluir' + tipo + '/' + id;
    
    message.innerHTML = `Você tem certeza que deseja excluir o item: <strong>${nome}</strong>?`;
    
    modal.show();
}

document.addEventListener('DOMContentLoaded', function() {
    
    const escolaridadeCargoSelect = document.getElementById('escolaridade_cargo_id');
    if (escolaridadeCargoSelect) {
        escolaridadeCargoSelect.addEventListener('change', function() {
            toggleOutroCargo(this, 'escolaridadeOutroCargoContainer');
        });
        toggleOutroCargo(escolaridadeCargoSelect, 'escolaridadeOutroCargoContainer');
    }

    const experienciaCargoSelect = document.getElementById('experiencia_cargo_id');
    if (experienciaCargoSelect) {
        experienciaCargoSelect.addEventListener('change', function() {
            toggleOutroCargo(this, 'experienciaOutroCargoContainer');
        });
        toggleOutroCargo(experienciaCargoSelect, 'experienciaOutroCargoContainer');
    }

    const editExperienciaCargoSelect = document.getElementById('edit_cargo_id');
    if (editExperienciaCargoSelect) {
        editExperienciaCargoSelect.addEventListener('change', function() {
            toggleOutroCargo(this, 'editOutroCargoContainer');
        });
    }

    document.querySelectorAll('.btn-edit-experiencia').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const experienciaId = this.getAttribute('data-id');
            openEditExperienciaModal(experienciaId);
        });
    });

    document.querySelectorAll('.btn-confirm-delete').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const id = this.getAttribute('data-id');
            const tipo = this.getAttribute('data-tipo');
            const nome = this.getAttribute('data-nome');
            openConfirmDeleteModal(id, tipo, nome);
        });
    });
});