<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro - Conecta Muriaé</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= baseUrl() ?>assets/css/style.css">
</head>
<body>
    <?php 
        // Recupera os dados do formulário da sessão, se existirem
        $formData = Core\Library\Session::get('form_data');
        Core\Library\Session::destroy('form_data'); // Limpa para não repopular em novas visitas
    ?>
    <section class="py-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="card shadow-sm">
                        <div class="card-body p-5">
                            <div class="text-center mb-4">
                                <h2 class="fw-bold">Crie sua conta</h2>
                                <p class="text-muted">Comece sua jornada profissional ou encontre talentos</p>
                            </div>

                            <?php 
                                $flash = Core\Library\Session::get('flash_msg');
                                if ($flash): 
                            ?>
                                <div class="alert alert-<?= $flash['tipo'] === 'success' ? 'success' : 'danger' ?>"><?= $flash['mensagem'] ?></div>
                            <?php 
                                Core\Library\Session::destroy('flash_msg');
                                endif; 
                            ?>

                            <form id="formCadastro" action="<?= baseUrl() ?>login/registrar" method="POST">
                                <!-- Tipo de conta -->
                                <div class="mb-3">
                                    <label for="tipo" class="form-label">Tipo de Conta*</label>
                                    <select class="form-select" id="tipo" name="tipo" required>
                                        <option value="" disabled <?= empty($formData['tipo']) ? 'selected' : '' ?>>Selecione...</option>
                                        <option value="CN" <?= ($formData['tipo'] ?? '') === 'CN' ? 'selected' : '' ?>>Sou Candidato</option>
                                        <option value="A" <?= ($formData['tipo'] ?? '') === 'A' ? 'selected' : '' ?>>Sou Empresa</option>
                                        <option value="G" <?= ($formData['tipo'] ?? '') === 'G' ? 'selected' : '' ?>>Sou Gestor</option>
                                    </select>
                                </div>

                                <!-- Campos para Pessoa Física (Candidato) -->
                                <div id="pessoaFisicaFields" class="d-none">
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="nome" class="form-label">Nome Completo*</label>
                                            <input type="text" class="form-control" id="nome" name="nome" placeholder="Seu nome completo" value="<?= htmlspecialchars($formData['nome'] ?? '') ?>">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="cpf" class="form-label">CPF*</label>
                                            <input type="text" class="form-control" id="cpf" name="cpf" placeholder="000.000.000-00" maxlength="14" value="<?= htmlspecialchars($formData['cpf'] ?? '') ?>">
                                        </div>
                                    </div>
                                </div>

                                <!-- Campos para Estabelecimento (Empresa) -->
                                <div id="estabelecimentoFields" class="d-none">
                                    <h5 class="mt-4 mb-3 border-bottom pb-2">Dados da Empresa</h5>
                                    <div class="mb-3">
                                        <label for="estabelecimento_nome" class="form-label">Nome da Empresa*</label>
                                        <input type="text" class="form-control" id="estabelecimento_nome" name="estabelecimento_nome" placeholder="Nome fantasia da sua empresa" value="<?= htmlspecialchars($formData['estabelecimento_nome'] ?? '') ?>">
                                    </div>
                                    <div class="mb-3">
                                        <label for="estabelecimento_email" class="form-label">E-mail da Empresa*</label>
                                        <input type="email" class="form-control" id="estabelecimento_email" name="estabelecimento_email" placeholder="contato@suaempresa.com" value="<?= htmlspecialchars($formData['estabelecimento_email'] ?? '') ?>">
                                    </div>
                                </div>
                                
                                <h5 class="mt-4 mb-3 border-bottom pb-2">Dados de Acesso</h5>
                                <!-- E-mail (login) -->
                                <div class="mb-3">
                                    <label for="login" class="form-label">E-mail de Acesso*</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                         <input type="email" class="form-control" id="login" name="login" placeholder="seu@email.com" required value="<?= htmlspecialchars($formData['login'] ?? '') ?>">
                                    </div>
                                </div>

                                <!-- Senha -->
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="senha" class="form-label">Senha*</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                            <input type="password" class="form-control" id="senha" name="senha" placeholder="Crie uma senha forte" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="confirmar_senha" class="form-label">Confirmar Senha*</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-check-circle"></i></span>
                                            <input type="password" class="form-control" id="confirmar_senha" name="confirmar_senha" placeholder="Confirme a senha" required>
                                        </div>
                                    </div>
                                </div>

                                <!-- Termos de Uso e Privacidade -->
                                <div class="mb-4 mt-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="termos" name="termos" required <?= !empty($formData['termos']) ? 'checked' : '' ?>>
                                        <label class="form-check-label" for="termos">
                                            Eu li e concordo com os <a href="<?= baseUrl() ?>termos" target="_blank">Termos de Uso</a>, a <a href="<?= baseUrl() ?>termos/privacidade" target="_blank">Política de Privacidade</a> e a <a href="<?= baseUrl() ?>termos/cookies" target="_blank">Política de Cookies</a>.*
                                        </label>
                                    </div>
                                </div>

                                <div class="d-grid">
                                    <button type="submit" class="btn btn-primary py-2">Criar conta</button>
                                </div>
                            </form>

                            <div class="text-center mt-4">
                                <p class="text-muted">Já tem uma conta? <a href="<?= baseUrl() ?>login" class="text-decoration-none">Faça login</a></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const tipoSelect = document.getElementById('tipo');
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
                nomeInput.required = false;
                cpfInput.required = false;
                estFields.classList.add('d-none');
                estNomeInput.required = false;
                estEmailInput.required = false;

                if (value === 'CN') {
                    pfFields.classList.remove('d-none');
                    nomeInput.required = true;
                    cpfInput.required = true;
                } else if (value === 'A') {
                    estFields.classList.remove('d-none');
                    estNomeInput.required = true;
                    estEmailInput.required = true;
                }
            }

            tipoSelect.addEventListener('change', toggleFields);
            // Run on page load para garantir que os campos corretos sejam exibidos
            // caso o formulário seja repopulado após um erro.
            toggleFields(); 

            // Aplica a máscara de CPF
            cpfInput.addEventListener('input', function (e) {
                let value = e.target.value.replace(/\D/g, '');
                value = value.replace(/(\d{3})(\d)/, '$1.$2');
                value = value.replace(/(\d{3})(\d)/, '$1.$2');
                value = value.replace(/(\d{3})(\d{1,2})$/, '$1-$2');
                e.target.value = value;
            });
        });
    </script>
</body>
</html>