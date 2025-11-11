<?php
use Core\Library\Session;

// Pega mensagem flash, se existir
$flash = Session::get('flash_msg') ?? null;
Session::destroy('flash_msg');
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - MuriaeEmpregos</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- CSS Personalizado -->
    <link rel="stylesheet" href="<?= baseUrl() ?>assets/css/style.css">
</head>
<body>

<section class="py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <div class="card shadow-sm">
                    <div class="card-body p-5">
                        <div class="text-center mb-4">
                            <h2 class="fw-bold">Acesse sua conta</h2>
                            <p class="text-muted">Aproveite sua vida profissional ao máximo</p>
                        </div>

                        <?php if ($flash): ?>
                            <?php if ($flash['tipo'] === 'error'): ?>
                                <div class="alert alert-danger"><?= $flash['mensagem'] ?></div>
                            <?php else: ?>
                                <div class="alert alert-success"><?= $flash['mensagem'] ?></div>
                            <?php endif; ?>
                        <?php endif; ?>

                        <form id="loginForm" action="<?= baseUrl() ?>Login/autenticar" method="POST">


                            <!-- Login (e-mail) -->
                            <div class="mb-3">
                                <label for="login" class="form-label">E-mail</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                    <input type="text" class="form-control" id="login" name="login" placeholder="seu@email.com" required>
                                </div>
                            </div>

                            <!-- Senha -->
                            <div class="mb-3">
                                <label for="senha" class="form-label">Senha</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                    <input type="password" class="form-control" id="senha" name="senha" placeholder="Sua senha" required>
                                    <span class="input-group-text password-toggle"><i class="fas fa-eye"></i></span>
                                </div>
                            </div>

                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="remember-me" name="remember-me">
                                    <label class="form-check-label" for="remember-me">Lembrar meus dados</label>
                                </div>
                                <a href="<?= baseUrl() ?>Login/recuperarSenha" class="text-decoration-none">Esqueceu a senha?</a>
                            </div>

                            <div class="d-grid mb-3">
                                <button type="submit" class="btn btn-primary py-2">Entrar</button>
                            </div>

                            <div class="text-center small text-muted mb-3">
                                <p>
                                    Ao clicar em "Entrar", você aceita o
                                    <a href="<?= baseUrl() ?>termos" class="text-decoration-none">Contrato do Usuário</a>,
                                    a <a href="<?= baseUrl() ?>termos/privacidade" class="text-decoration-none">Política de Privacidade</a> e
                                    a <a href="<?= baseUrl() ?>termos/cookies" class="text-decoration-none">Política de Cookies</a>.
                                </p>
                            </div>

                            <div class="text-center">
                                <p class="text-muted">Não tem uma conta? <a href="<?= baseUrl() ?>login/cadastro" class="text-decoration-none">Cadastre-se</a></p>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Footer -->
<?php include __DIR__ . '/../comuns/rodape.php'; ?>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="<?= baseUrl() ?>assets/js/auth.js"></script>

</body>
</html>