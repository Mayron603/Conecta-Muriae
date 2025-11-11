<?php
    $usuarioLogado = \Core\Library\Session::get('usuario_logado');
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> 
    <title>MuriaeEmpregos - Conectando talentos e oportunidades</title> 
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- CSS -->
    <link rel="stylesheet" href="<?= baseUrl() ?>assets/css/style.css">
</head>
<body>
    <!-- NAVBAR -->
    <nav class="navbar navbar-expand-lg navbar-light sticky-top">
        <div class="container">
            <a class="navbar-brand" href="<?= baseUrl() ?>">
                <img src="<?= baseUrl() ?>assets/img/logo.png" alt="Conecta Muriaé">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="<?= baseUrl() ?>">Início</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?= baseUrl() ?>vagas">Vagas</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?= baseUrl() ?>sobre">Sobre</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?= baseUrl() ?>contato">Contato</a></li>
                </ul>
                <div class="ms-lg-3 mt-3 mt-lg-0">
                    <?php if (isset($usuarioLogado) && !empty($usuarioLogado)): ?>
                        <?php
                            $dashboardUrl = baseUrl();
                            if ($usuarioLogado['tipo'] == 'CN') { 
                                $dashboardUrl .= 'candidatos/index';
                            } elseif (in_array($usuarioLogado['tipo'], ['A', 'G'])) { 
                                $dashboardUrl .= 'empresa/index';
                            }

                            // Lógica segura para obter o primeiro nome do usuário
                            $nomeCompleto = $usuarioLogado['nome'] ?? $usuarioLogado['nome_fantasia'] ?? $usuarioLogado['login'] ?? 'Usuário';
                            $primeiroNome = htmlspecialchars(explode(' ', $nomeCompleto)[0]);
                        ?>
                        <div class="dropdown">
                            <a href="#" class="btn btn-primary dropdown-toggle" role="button" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-user me-2"></i> Olá, <?= $primeiroNome ?>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                                <li><a class="dropdown-item" href="<?= $dashboardUrl ?>">Meu Painel</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item text-danger" href="<?= baseUrl() ?>login/sair">Sair</a></li>
                            </ul>
                        </div>
                    <?php else: ?>
                        <a href="<?= baseUrl() ?>login" class="btn btn-outline-primary me-2">Entrar</a>
                        <a href="<?= baseUrl() ?>login/cadastro" class="btn btn-primary">Cadastrar</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>
