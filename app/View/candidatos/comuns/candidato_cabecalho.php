<?php
require_once __DIR__ . '/../../../../core/Helper/utilits.php';

$usuario = $dados['usuario'] ?? [];
$nomeParaExibir = $usuario['nome'] ?? 'Usuário';

?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> 
    <title>MuriaeEmpregos - Conectando talentos e oportunidades</title> 
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.css" rel="stylesheet">
    
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="<?= baseUrl() ?>assets/css/style.css">
    <link rel="stylesheet" href="<?= baseUrl() ?>assets/css/sidebar.css">
    <link rel="stylesheet" href="<?= baseUrl() ?>assets/css/chat.css">

</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-light sticky-top bg-primary">
        <div class="container">
            <a class="navbar-brand" href="<?= baseUrl() ?>">
                <img src="<?= baseUrl() ?>assets/img/logo.png" alt="Conecta Muriaé" class="img-fluid" style="height: 40px;">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
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
                        <a class="nav-link text-white active" href="<?= baseUrl() ?>candidatos">Área do Candidato</a>
                    </li>
                </ul>
                <div class="ms-lg-3 mt-3 mt-lg-0">
                    <div class="dropdown">
                        <button class="btn btn-light dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-user-circle me-1"></i> <?= htmlspecialchars($nomeParaExibir) ?>
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                            <li><a class="dropdown-item" href="<?= baseUrl() ?>candidatos/perfil"><i class="fas fa-user me-2"></i> Meu Perfil</a></li>
                            <li><a class="dropdown-item" href="<?= baseUrl() ?>candidatos/configuracoes"><i class="fas fa-cog me-2"></i> Configurações</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="<?= baseUrl() ?>login/sair"><i class="fas fa-sign-out-alt me-2"></i> Sair</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </nav>