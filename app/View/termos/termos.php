<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Termos de Uso - MuriaeEmpregos</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- CSS Personalizado -->
    <link rel="stylesheet" href="<?= baseUrl() ?>/paginas/css/style.css">
</head>
<body>
    <?php 
        // Caminho para a pasta de views
        $pathView = __DIR__ . '/../../View/';

        // Inclui o cabeçalho dinâmico
        require_once $pathView . 'comuns/cabecalho.php'; 
    ?>

    <!-- Hero Section -->
    <section class="hero-section-termos">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-12 text-center">
                    <h1 class="hero-title display-4 fw-bold">Termos de Uso</h1>
                    <p class="hero-subtitle lead">Última atualização: 03 de Junho de 2025</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Content Section -->
    <section class="py-5 bg-white">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="card shadow-sm border-0">
                        <div class="card-body p-4 p-md-5">
                            <h2 class="h4 fw-bold mb-4">1. Aceitação dos Termos</h2>
                            <p class="mb-4">Ao acessar ou utilizar a plataforma MuriaeEmpregos, você concorda em cumprir estes Termos de Uso e nossa Política de Privacidade. Se não concordar, não utilize nossos serviços.</p>
                            
                            <h2 class="h4 fw-bold mb-4">2. Cadastro na Plataforma</h2>
                            <p class="mb-4">2.1 Para utilizar nossos serviços, você precisará criar uma conta fornecendo informações precisas e completas.</p>
                            <p class="mb-4">2.2 Você é responsável por manter a confidencialidade de suas credenciais de acesso.</p>
                            
                            <h2 class="h4 fw-bold mb-4">3. Conduta do Usuário</h2>
                            <p class="mb-4">3.1 Você concorda em não:</p>
                            <ul class="mb-4">
                                <li>Publicar informações falsas ou enganosas</li>
                                <li>Utilizar a plataforma para fins ilegais</li>
                                <li>Violar direitos de propriedade intelectual</li>
                                <li>Praticar discriminação de qualquer tipo</li>
                            </ul>
                            
                            <h2 class="h4 fw-bold mb-4">4. Vagas e Candidaturas</h2>
                            <p class="mb-4">4.1 As empresas são responsáveis pela veracidade das informações das vagas publicadas.</p>
                            <p class="mb-4">4.2 O MuriaeEmpregos não garante a contratação dos candidatos.</p>
                            
                            <h2 class="h4 fw-bold mb-4">5. Propriedade Intelectual</h2>
                            <p class="mb-4">Todo o conteúdo da plataforma, exceto o fornecido por usuários, é propriedade do MuriaeEmpregos.</p>
                            
                            <h2 class="h4 fw-bold mb-4">6. Limitação de Responsabilidade</h2>
                            <p class="mb-4">O MuriaeEmpregos não se responsabiliza por:</p>
                            <ul class="mb-4">
                                <li>Conteúdo publicado por terceiros</li>
                                <li>Contratações realizadas através da plataforma</li>
                                <li>Danos decorrentes do uso ou impossibilidade de uso</li>
                            </ul>
                            
                            <h2 class="h4 fw-bold mb-4">7. Modificações nos Termos</h2>
                            <p class="mb-4">Podemos alterar estes Termos a qualquer momento. As alterações entrarão em vigor após a publicação.</p>
                            
                            <h2 class="h4 fw-bold mb-4">8. Lei Aplicável</h2>
                            <p class="mb-4">Estes Termos são regidos pelas leis brasileiras, especialmente a Lei nº 13.709/2018 (LGPD).</p>
                            
                            <h2 class="h4 fw-bold mb-4">9. Contato</h2>
                            <p>Para dúvidas sobre estes Termos, entre em contato através do nosso <a href="./contato.html">formulário de contato</a>.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <?php 
        // Inclui o rodapé global
        require_once $pathView . 'comuns/rodape.php'; 
    ?>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
