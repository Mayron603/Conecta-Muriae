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
    <title>Redefinir Senha - MuriaeEmpregos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
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
                            <h2 class="fw-bold">Crie uma Nova Senha</h2>
                            <p class="text-muted">Sua nova senha deve ser diferente da anterior.</p>
                        </div>

                        <?php if ($flash): ?>
                            <div class="alert alert-<?= $flash['tipo'] === 'error' ? 'danger' : 'success' ?>"><?= $flash['mensagem'] ?></div>
                        <?php endif; ?>

                        <form id="resetForm" action="<?= baseUrl() ?>Login/salvarNovaSenha" method="POST">
                            <input type="hidden" name="token" value="<?= $this->viewData['token'] ?? '' ?>">

                            <div class="mb-3">
                                <label for="senha" class="form-label">Nova Senha</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                    <input type="password" class="form-control" id="senha" name="senha" required>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="confirmar_senha" class="form-label">Confirmar Nova Senha</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                    <input type="password" class="form-control" id="confirmar_senha" name="confirmar_senha" required>
                                </div>
                            </div>

                            <div class="d-grid mb-3">
                                <button type="submit" class="btn btn-primary py-2">Salvar Nova Senha</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include __DIR__ . '/../comuns/rodape.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
