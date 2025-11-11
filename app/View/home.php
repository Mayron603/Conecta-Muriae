<?php include_once __DIR__ . "/comuns/cabecalho.php"; ?>

<!-- Hero (principal) -->
<section class="hero-section">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <h1 class="hero-title display-4 fw-bold">Conectando talentos e oportunidades em Muriaé</h1>
                <p class="hero-subtitle lead">A plataforma que aproxima empresas locais e candidatos qualificados, impulsionando o desenvolvimento econômico da região.</p>
                <div class="d-flex flex-wrap gap-3">
                    <a href="<?= baseUrl() ?>vagas" class="btn btn-light btn-lg px-4">
                        <i class="fas fa-search me-2"></i> Buscar Vagas
                    </a>
                    <a href="<?= baseUrl() ?>login/cadastro" class="btn btn-outline-light btn-lg px-4">
                        <i class="fas fa-building me-2"></i> Sou Empresa
                    </a>
                </div>
            </div>
            <div class="col-lg-6">
                <img src="<?= baseUrl() ?>assets/img/footer.png" alt="Pessoas procurando emprego" class="img-fluid hero-img">
            </div>
        </div>
    </div>
</section>

<!-- Funcionalidades -->
<section class="features-section">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="section-title display-5 fw-bold">Como Funciona</h2>
            <p class="section-subtitle lead mx-auto">Um processo simples para conectar talentos e oportunidades em Muriaé e região</p>
        </div>
        
        <div class="row g-4">
            <div class="col-md-6 col-lg-3">
                <div class="card feature-card p-4">
                    <div class="feature-icon bg-primary"><i class="fas fa-user-edit"></i></div>
                    <h3 class="feature-title">Cadastro Simples</h3>
                    <p class="feature-text">Candidatos criam perfis detalhados e empresas registram suas informações em poucos minutos.</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="card feature-card p-4">
                    <div class="feature-icon bg-success"><i class="fas fa-search"></i></div>
                    <h3 class="feature-title">Busca Inteligente</h3>
                    <p class="feature-text">Encontre vagas ou candidatos com filtros avançados que consideram habilidades, experiência e localização.</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="card feature-card p-4">
                    <div class="feature-icon bg-warning"><i class="fas fa-paper-plane"></i></div>
                    <h3 class="feature-title">Candidatura Direta</h3>
                    <p class="feature-text">Candidate-se a vagas com um clique ou envie convites para entrevistas de forma rápida e eficiente.</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="card feature-card p-4">
                    <div class="feature-icon bg-warning"><i class="fas fa-envelope"></i></div>
                    <h3 class="feature-title">Comunicação Direta</h3>
                    <p class="feature-text">Troque mensagens e agende entrevistas através da plataforma, tudo em um só lugar.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Estatísticas -->
<section class="stats-section">
    <div class="container">
        <div class="row g-4">
            <div class="col-md-4">
                <div class="stat-card">
                    <div class="stat-number" id="vacancies-counter"><?= $dados['vagasAtivas'] ?></div>
                    <div class="stat-label">Vagas Ativas</div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-card">
                    <div class="stat-number" id="companies-counter"><?= $dados['empresasCadastradas'] ?></div>
                    <div class="stat-label">Empresas Cadastradas</div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-card">
                    <div class="stat-number" id="candidates-counter"><?= $dados['candidatos'] ?></div>
                    <div class="stat-label">Candidatos</div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Chamada para ação -->
<section class="cta-section">
    <div class="container">
        <h2 class="cta-title display-5 fw-bold">Faça parte da nossa comunidade</h2>
        <p class="cta-text lead mx-auto">Junte-se a dezenas de empresas e centenas de candidatos que já estão conectados através da nossa plataforma.</p>
        <div class="d-flex flex-wrap justify-content-center gap-3">
            <a href="<?= baseUrl() ?>login/cadastro" class="btn btn-light btn-lg px-4">
                <i class="fas fa-user me-2"></i> Quero me Candidatar
            </a>
            <a href="<?= baseUrl() ?>login/cadastro" class="btn btn-outline-light btn-lg px-4">
                <i class="fas fa-building me-2"></i> Sou Empresa
            </a>
        </div>
    </div>
</section>

<?php include_once __DIR__ . "/comuns/rodape.php"; ?>
