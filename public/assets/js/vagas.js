document.addEventListener('DOMContentLoaded', function() {
    
    const formBusca = document.getElementById('form-busca');
    const listaVagas = document.getElementById('lista-vagas');

    // Mapeamentos (para traduzir os IDs que vêm do banco)
    const modalidades = { '1': 'Presencial', '2': 'Remoto' };
    const vinculos = { '1': 'CLT', '2': 'Pessoa Jurídica (PJ)' };

    if (formBusca) {
        // Listener para o SUBMIT do formulário
        formBusca.addEventListener('submit', function(e) {
            e.preventDefault(); // Impede o envio tradicional
            buscarVagas();
        });

        // Opcional: buscar ao mudar qualquer filtro (exceto o de texto)
        formBusca.querySelectorAll('select').forEach(select => {
            select.addEventListener('change', buscarVagas);
        });
    }

    async function buscarVagas() {
        // 1. Mostrar loading
        listaVagas.innerHTML = `
            <div class="text-center p-5">
                <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
                    <span class="visually-hidden">Buscando...</span>
                </div>
                <p class="mt-3 text-muted">Buscando vagas...</p>
            </div>
        `;

        // 2. Pegar dados do formulário e criar query string
        const formData = new FormData(formBusca);
        const params = new URLSearchParams(formData).toString();

        try {
            // 3. Fazer a requisição AJAX para o controller
            const response = await fetch(`${appBaseUrl}vagas/buscar?${params}`);
            if (!response.ok) {
                throw new Error('Erro na rede: ' + response.statusText);
            }
            
            const data = await response.json();
            
            // 4. Renderizar os resultados
            renderVagas(data.vagas);

        } catch (error) {
            console.error('Erro ao buscar vagas:', error);
            listaVagas.innerHTML = `
                <div class="alert alert-danger text-center">
                    Ocorreu um erro ao buscar as vagas. Tente novamente mais tarde.
                </div>
            `;
        }
    }

    function renderVagas(vagas) {
        // Limpa a lista
        listaVagas.innerHTML = '';

        if (!vagas || vagas.length === 0) {
            listaVagas.innerHTML = `
                <div class="alert alert-info text-center">
                    <h5 class="alert-heading">Nenhuma vaga encontrada</h5>
                    <p>Tente ajustar seus termos de busca.</p>
                </div>
            `;
            return;
        }

        // 5. Criar o HTML para cada vaga
        vagas.forEach(vaga => {
            // Formata a data
            let dataFormatada = 'N/A';
            if (vaga.dtInicio) {
                try {
                    // Adiciona fuso horário para evitar problemas de "um dia antes"
                    const data = new Date(vaga.dtInicio + 'T03:00:00Z'); 
                    dataFormatada = data.toLocaleDateString('pt-BR');
                } catch(e) {
                    console.warn('Data inválida:', vaga.dtInicio);
                }
            }

            // Traduz os IDs
            const modalidadeNome = modalidades[vaga.modalidade] || 'N/A';
            const vinculoNome = vinculos[vaga.vinculo] || 'N/A';
            
            const vagaCardHTML = `
                <div class="card vaga-card mb-3 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between flex-wrap">
                            <div class="me-3">
                                <h5 class="card-title fw-bold text-primary">${escapeHTML(vaga.titulo)}</h5>
                                <h6 class="card-subtitle mb-2 text-muted">${escapeHTML(vaga.nome_fantasia)}</h6>
                            </div>
                            <div class="text-end" style="min-width: 120px;">
                                <a href="${appBaseUrl}vagas/visualizar/${vaga.vaga_id}" class="btn btn-primary btn-sm w-100">Ver detalhes</a>
                            </div>
                        </div>
                        <div class="d-flex flex-wrap mt-3 text-muted small">
                            <span class="me-3 mb-1"><i class="fas fa-building me-1"></i> ${escapeHTML(modalidadeNome)}</span>
                            <span class="me-3 mb-1"><i class="fas fa-briefcase me-1"></i> ${escapeHTML(vinculoNome)}</span>
                            <span class="me-3 mb-1"><i class="fas fa-calendar-alt me-1"></i> Publicada em ${dataFormatada}</span>
                        </div>
                    </div>
                </div>
            `;
            
            listaVagas.innerHTML += vagaCardHTML;
        });
    }

    // Função auxiliar para evitar XSS
    function escapeHTML(str) {
        if (str === null || str === undefined) return '';
        return str.toString()
                 .replace(/&/g, '&amp;')
                 .replace(/</g, '&lt;')
                 .replace(/>/g, '&gt;')
                 .replace(/"/g, '&quot;')
                 .replace(/'/g, '&#039;');
    }
    
    // Opcional: Carregar as vagas uma vez ao carregar a página
    // (Apenas se você não quiser o carregamento inicial via PHP)
    // buscarVagas(); 
});