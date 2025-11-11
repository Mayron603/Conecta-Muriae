// Salve este código como: public/assets/js/candidato-curriculo.js

/**
 * Alterna a exibição do campo 'Outro Cargo'
 */
function toggleOutroCargo(selectElement, targetId) {
    const targetDiv = document.getElementById(targetId);
    if (selectElement.value === "") {
        targetDiv.style.display = 'block';
    } else {
        targetDiv.style.display = 'none';
        const input = targetDiv.querySelector('input[name="cargoDescricao"]');
        if (input) {
            input.value = ''; // Limpa o campo de texto se uma opção for selecionada
        }
    }
}

/**
 * Abre o modal de EDIÇÃO de experiência e o preenche com dados via AJAX/Fetch
 */
function openEditExperienciaModal(experienciaId) {
    if (!experienciaId) return;
    
    // Usamos a variável global definida no rodapé
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
            
            // Preenche o formulário do modal
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

            // Mostra ou esconde o campo "Outro Cargo"
            toggleOutroCargo(document.getElementById('edit_cargo_id'), 'editOutroCargoContainer');
            
            modal.show();
        })
        .catch(error => {
            console.error('Erro no fetch:', error);
            alert('Não foi possível carregar os dados para edição.');
        });
}

/**
 * Abre o modal genérico de CONFIRMAÇÃO DE EXCLUSÃO
 */
function openConfirmDeleteModal(id, tipo, nome) {
    const modalElement = document.getElementById('confirmDeleteModal');
    const modal = bootstrap.Modal.getInstance(modalElement) || new bootstrap.Modal(modalElement);
    
    const form = modalElement.querySelector('#deleteForm');
    const message = modalElement.querySelector('#deleteMessage');

    // Usamos a variável global definida no rodapé
    form.action = window.APP_BASE_URL + 'candidatos/excluir' + tipo + '/' + id;
    
    message.innerHTML = `Você tem certeza que deseja excluir o item: <strong>${nome}</strong>?`;
    
    modal.show();
}

/**
 * Adiciona todos os "escutadores de eventos" (listeners) quando a página carregar
 */
document.addEventListener('DOMContentLoaded', function() {
    
    // Listener para o select de cargo (Formação/Escolaridade)
    const escolaridadeCargoSelect = document.getElementById('escolaridade_cargo_id');
    if (escolaridadeCargoSelect) {
        escolaridadeCargoSelect.addEventListener('change', function() {
            toggleOutroCargo(this, 'escolaridadeOutroCargoContainer');
        });
        // Inicializa no carregamento da página
        toggleOutroCargo(escolaridadeCargoSelect, 'escolaridadeOutroCargoContainer');
    }

    // Listener para o select de cargo (Experiência)
    const experienciaCargoSelect = document.getElementById('experiencia_cargo_id');
    if (experienciaCargoSelect) {
        experienciaCargoSelect.addEventListener('change', function() {
            toggleOutroCargo(this, 'experienciaOutroCargoContainer');
        });
        // Inicializa no carregamento da página
        toggleOutroCargo(experienciaCargoSelect, 'experienciaOutroCargoContainer');
    }

    // Listener para o select de cargo (Edição de Experiência)
    const editExperienciaCargoSelect = document.getElementById('edit_cargo_id');
    if (editExperienciaCargoSelect) {
        editExperienciaCargoSelect.addEventListener('change', function() {
            toggleOutroCargo(this, 'editOutroCargoContainer');
        });
    }

    // Listener para os botões de "Editar Experiência"
    document.querySelectorAll('.btn-edit-experiencia').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const experienciaId = this.getAttribute('data-id');
            openEditExperienciaModal(experienciaId);
        });
    });

    // Listener para os botões de "Excluir" (genérico)
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