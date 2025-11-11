// Espera o DOM carregar para garantir que os elementos existam
document.addEventListener('DOMContentLoaded', function() {

    // --- Lógica do login.php (Alternar visibilidade da senha) ---
    const passwordToggles = document.querySelectorAll('.password-toggle');
    
    // Verifica se existem elementos '.password-toggle' na página atual
    if (passwordToggles.length > 0) {
        passwordToggles.forEach(toggle => {
            toggle.addEventListener('click', () => {
                const input = toggle.parentElement.querySelector('input');
                if (input.type === 'password') {
                    input.type = 'text';
                    toggle.innerHTML = '<i class="fas fa-eye-slash"></i>';
                } else {
                    input.type = 'password';
                    toggle.innerHTML = '<i class="fas fa-eye"></i>';
                }
            });
        });
    }

    // --- Lógica do cadastro.php (Campos dinâmicos e máscara de CPF) ---
    const tipoSelect = document.getElementById('tipo');

    // Só executa esta lógica se encontrar o 'tipoSelect' (ou seja, se estiver na pág de cadastro)
    if (tipoSelect) {
        const pfFields = document.getElementById('pessoaFisicaFields');
        const estFields = document.getElementById('estabelecimentoFields');
        const nomeInput = document.getElementById('nome');
        const cpfInput = document.getElementById('cpf');
        const estNomeInput = document.getElementById('estabelecimento_nome');
        const estEmailInput = document.getElementById('estabelecimento_email');

        // Função para alternar campos com base no tipo de conta
        function toggleFields() {
            const value = tipoSelect.value;
            pfFields.classList.add('d-none');
            if(nomeInput) nomeInput.required = false;
            if(cpfInput) cpfInput.required = false;
            
            estFields.classList.add('d-none');
            if(estNomeInput) estNomeInput.required = false;
            if(estEmailInput) estEmailInput.required = false;

            if (value === 'CN') {
                pfFields.classList.remove('d-none');
                if(nomeInput) nomeInput.required = true;
                if(cpfInput) cpfInput.required = true;
            } else if (value === 'A') {
                estFields.classList.remove('d-none');
                if(estNomeInput) estNomeInput.required = true;
                if(estEmailInput) estEmailInput.required = true;
            }
        }

        tipoSelect.addEventListener('change', toggleFields);
        toggleFields(); // Executa ao carregar a página

        // Aplica a máscara de CPF
        if (cpfInput) {
            cpfInput.addEventListener('input', function (e) {
                let value = e.target.value.replace(/\D/g, '');
                value = value.replace(/(\d{3})(\d)/, '$1.$2');
                value = value.replace(/(\d{3})(\d)/, '$1.$2');
                value = value.replace(/(\d{3})(\d{1,2})$/, '$1-$2');
                e.target.value = value;
            });
        }
    }
});