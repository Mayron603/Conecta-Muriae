<?php
use Core\Library\Session;

// Busca o nome do usuário/empresa da sessão, com um fallback.
$usuarioLogado = Session::get('usuario_logado');
// Tenta obter o nome do estabelecimento, se não existir, usa o login.
$nomeEmpresa = $usuarioLogado['nome_fantasia'] ?? $usuarioLogado['login'] ?? 'Empresa';

?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Área da Empresa - Descubra Muriaé</title>
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
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light sticky-top bg-primary">
        <div class="container">
            <a class="navbar-brand text-white" href="<?= baseUrl() ?>">
                <img src="<?= baseUrl() ?>assets/img/logo.png" alt="Descubra Muriaé" class="img-fluid" style="height: 90px;">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link text-white" href="<?= baseUrl() ?>">Início</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="<?= baseUrl() ?>vagas">Vagas</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white active" href="<?= baseUrl() ?>empresa/index">Área da Empresa</a>
                    </li>
                </ul>
                <div class="ms-lg-3 mt-3 mt-lg-0">
                    <div class="dropdown">
                        <button class="btn btn-light dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-building me-1"></i> <?= htmlspecialchars($nomeEmpresa) ?>
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                            <li><a class="dropdown-item" href="#"><i class="fas fa-building me-2"></i> Perfil da Empresa</a></li>
                            <li><a class="dropdown-item" href="#"><i class="fas fa-cog me-2"></i> Configurações</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="<?= baseUrl() ?>login/sair"><i class="fas fa-sign-out-alt me-2"></i> Sair</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </nav>
