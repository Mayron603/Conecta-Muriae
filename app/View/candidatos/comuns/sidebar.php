<?php
      
      // --- DADOS VINDOS DO CONTROLLER ---
      $usuario = $dados['usuario'] ?? [];
      $curriculum = $dados['curriculum'] ?? [];
      $progresso = $dados['progresso_perfil'] ?? 0;
      $unreadNotifications = $dados['unread_notifications'] ?? 0;
      
      // [CORREÇÃO APLICADA AQUI]
      // O nome completo agora vem diretamente do campo 'nome' do banco de dados, que é a fonte da verdade.
      // Isso evita a duplicação que ocorria ao concatenar com um 'sobrenome' vindo da sessão.
      $nomeCompleto = trim($usuario['nome'] ?? '');
      
      // --- LÓGICA PARA EXIBIÇÃO ---
      $corProgresso = 'bg-danger';
      if ($progresso > 25) $corProgresso = 'bg-warning';
      if ($progresso > 60) $corProgresso = 'bg-info';
      if ($progresso >= 100) $corProgresso = 'bg-success';
      
      $apresentacaoCompleta = $curriculum['apresentacaoPessoal'] ?? '';
      $resumoApresentacao = $apresentacaoCompleta;
      if (mb_strlen($resumoApresentacao) > 80) {
          $resumoApresentacao = mb_substr($resumoApresentacao, 0, 80) . '...';
      }
      
      $fotoUrl = baseUrl() . 'uploads/fotos_perfil/default.png';
      if (!empty($curriculum['foto']) && $curriculum['foto'] !== 'default.png') {
          $fotoUrl = baseUrl() . 'uploads/fotos_perfil/' . $curriculum['foto'];
      }
      
      $fotoHtml = '<img src="' . $fotoUrl . '" alt="Foto de Perfil" class="profile-photo">';
      if (strpos($fotoUrl, 'default.png')) {
          $fotoHtml = '<div class="profile-photo-default">' .
                      '<i class="fas fa-user"></i>' .
                      '</div>';
      }
      
      $requestUri = $_SERVER['REQUEST_URI'] ?? '';
      
      ?>
      
      <style>
          :root {
              --sidebar-link-hover-bg: #f8f9fa;
              --sidebar-link-active-bg: #e9ecef;
              --sidebar-active-border-color: var(--bs-primary);
          }
      
          /* Card de Perfil */
          .profile-card .card-header-bg {
              background: linear-gradient(135deg, rgba(13, 110, 253, 0.9), rgba(10, 88, 202, 0.95)), url('https://www.toptal.com/designers/subtlepatterns/uploads/double-bubble-outline.png');
              height: 110px;
              border-radius: var(--bs-card-inner-border-radius) var(--bs-card-inner-border-radius) 0 0;
          }
      
          .profile-photo-container {
              margin-top: -60px; /* Puxa a foto para cima */
              cursor: pointer;
              position: relative;
              width: 120px;
              height: 120px;
              margin-left: auto;
              margin-right: auto;
          }
      
          .profile-photo,
          .profile-photo-default {
              width: 120px;
              height: 120px;
              border-radius: 50%;
              border: 4px solid #fff;
              box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
              object-fit: cover;
              background-color: #6c757d;
              transition: transform 0.3s ease, box-shadow 0.3s ease;
          }
          
          .profile-photo-default {
              display: flex;
              align-items: center;
              justify-content: center;
              font-size: 4rem;
              color: white;
          }
          
          .profile-photo-container:hover .profile-photo {
              transform: scale(1.05);
              box-shadow: 0 6px 16px rgba(0, 0, 0, 0.2);
          }
      
          .photo-overlay {
              position: absolute;
              top: 0;
              left: 0;
              width: 100%;
              height: 100%;
              background-color: rgba(33, 37, 41, 0.6);
              border-radius: 50%;
              opacity: 0;
              transition: opacity 0.3s ease;
              display: flex;
              align-items: center;
              justify-content: center;
              color: white;
              pointer-events: none; /* Garante que o clique passe para o container pai */
          }
      
         .profile-photo-container:hover .photo-overlay {
              opacity: 1;
          }
      
         .profile-badge {
              position: absolute;
              bottom: 5px;
              right: 5px;
              width: 28px;
              height: 28px;
              background-color: var(--bs-success);
              border-radius: 50%;
              display: flex;
              align-items: center;
              justify-content: center;
              border: 2px solid white;
              z-index: 2;
          }
      
         .profile-card .card-body {
              padding-top: 1rem;
          }
          
         .profile-card .progress {
              height: 10px;
              border-radius: 5px;
          }
      
         /* Menu de Navegação */
         .nav-menu .list-group-item {
              border: 0;
              border-left: 4px solid transparent; /* Espaço para o indicador ativo */
              border-radius: 0;
              padding: 0.9rem 1.25rem;
              transition: background-color 0.2s ease, color 0.2s ease, border-left-color 0.2s ease;
              color: #495057;
          }
      
         .nav-menu .list-group-item:hover {
              background-color: var(--sidebar-link-hover-bg);
              color: var(--bs-primary);
          }
          
         .nav-menu .list-group-item.active {
              font-weight: 600;
              color: var(--bs-primary);
              background-color: var(--sidebar-link-active-bg);
              border-left-color: var(--sidebar-active-border-color);
          }
          
         .nav-menu .list-group-item .fa-fw {
              color: #adb5bd;
              transition: color 0.2s ease;
          }
          
         .nav-menu .list-group-item:hover .fa-fw,
         .nav-menu .list-group-item.active .fa-fw {
              color: var(--bs-primary);
          }
      </style>
      
      <div class="col-lg-3">
          <div class="card shadow-sm mb-4 text-center profile-card">
              <div class="card-header-bg"></div>
      
              <div class="card-body">
                  <div
                      id="sidebar-photo-upload"
                      class="profile-photo-container"
                      onclick="document.getElementById('inputFoto')?.click()"
                      title="Clique para alterar a foto de perfil"
                  >
                      <?= $fotoHtml ?>
      
                      <div class="photo-overlay">
                          <div class="text-center">
                              <i class="fas fa-camera fa-2x"></i>
                              <div class="fw-bold mt-1 small">Alterar</div>
                          </div>
                      </div>
      
                      <?php if ($progresso >= 100): ?>
                          <div class="profile-badge" title="Perfil Completo">
                              <i class="fas fa-check text-white fs-6"></i>
                          </div>
                      <?php endif; ?>
                  </div>
      
                  <h4 class="mb-1 mt-3"><?= htmlspecialchars($nomeCompleto) ?></h4>
                  <p class="text-muted small mb-3 px-2" title="<?= htmlspecialchars($apresentacaoCompleta) ?>">
                      <?= htmlspecialchars(!empty(trim($resumoApresentacao)) ? $resumoApresentacao : 'Apresentação não definida') ?>
                  </p>
      
                  <div class="px-4 mb-3">
                      <div class="d-flex justify-content-between small text-muted mb-1">
                          <span>Progresso</span>
                          <span><?= $progresso ?>%</span>
                      </div>
                      <div class="progress" title="Progresso do perfil: <?= $progresso ?>%">
                          <div class="progress-bar <?= $corProgresso ?>" role="progressbar" style="width: <?= $progresso ?>%;" aria-valuenow="<?= $progresso ?>" aria-valuemin="0" aria-valuemax="100"></div>
                      </div>
                  </div>
      
                  <a href="<?= baseUrl() ?>candidatos/curriculo" class="btn btn-primary">
                      <i class="fas fa-user-edit me-1"></i> <?= ($progresso < 100) ? 'Completar Perfil' : 'Editar Perfil' ?>
                  </a>
              </div>
          </div>
      
          <div class="card shadow-sm nav-menu">
              <div class="list-group list-group-flush">
      
                  <?php $isActive = (basename(rtrim($requestUri, '/')) === 'candidatos'); ?>
                  <a href="<?= baseUrl() ?>candidatos" class="list-group-item list-group-item-action <?= $isActive ? 'active' : '' ?>">
                      <i class="fas fa-tachometer-alt fa-fw me-2"></i>Dashboard
                  </a>
      
                  <?php $isActive = str_contains($requestUri, 'candidatos/curriculo'); ?>
                  <a href="<?= baseUrl() ?>candidatos/curriculo" class="list-group-item list-group-item-action <?= $isActive ? 'active' : '' ?>">
                      <i class="fas fa-file-alt fa-fw me-2"></i>Meu Currículo
                  </a>
      
                  <?php $isActive = str_contains($requestUri, 'candidatos/minhasCandidaturas'); ?>
                  <a href="<?= baseUrl() ?>candidatos/minhasCandidaturas" class="list-group-item list-group-item-action <?= $isActive ? 'active' : '' ?>">
                      <i class="fas fa-briefcase fa-fw me-2"></i>Minhas Candidaturas
                  </a>
      
                  <?php $isActive = str_contains($requestUri, 'mensagemCandidato'); ?>
                  <a href="<?= baseUrl() ?>mensagemCandidato" class="list-group-item list-group-item-action <?= $isActive ? 'active' : '' ?>">
                      <i class="fas fa-comments fa-fw me-2"></i>Mensagens
                  </a>
                  
                  <?php $isActive = str_contains($requestUri, 'candidatos/notificacoes'); ?>
                  <a href="<?= baseUrl() ?>candidatos/notificacoes" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center <?= $isActive ? 'active' : '' ?>">
                      <span><i class="fas fa-bell fa-fw me-2"></i>Notificações</span>
                      <?php if (isset($unreadNotifications) && $unreadNotifications > 0): ?>
                          <span class="badge bg-danger rounded-pill"><?= $unreadNotifications ?></span>
                      <?php endif; ?>
                  </a>
      
                  <?php $isActive = str_contains($requestUri, 'candidatos/configuracoes'); ?>
                  <a href="<?= baseUrl() ?>candidatos/configuracoes" class="list-group-item list-group-item-action <?= $isActive ? 'active' : '' ?>">
                      <i class="fas fa-cog fa-fw me-2"></i>Configurações
                  </a>
      
              </div>
          </div>
      </div>
      