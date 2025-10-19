<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Política de Cookies - MuriaeEmpregos</title>
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
    <section class="hero-section-cookies">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-12 text-center">
                    <h1 class="hero-title display-4 fw-bold">Política de Cookies</h1>
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
                            <h2 class="h4 fw-bold mb-4">1. O que são Cookies?</h2>
                            <p class="mb-4">Cookies são pequenos arquivos de texto armazenados no seu dispositivo quando você visita nosso site. Eles nos ajudam a melhorar sua experiência e entender como você utiliza nossos serviços.</p>
                            
                            <h2 class="h4 fw-bold mb-4">2. Tipos de Cookies que Utilizamos</h2>
                            <p class="mb-4">2.1 Cookies Essenciais:</p>
                            <ul class="mb-4">
                                <li>Necessários para o funcionamento básico do site</li>
                                <li>Permitem navegação e uso de recursos</li>
                                <li>Não podem ser desativados</li>
                            </ul>
                            <p class="mb-4">2.2 Cookies de Desempenho:</p>
                            <ul class="mb-4">
                                <li>Coletam informações sobre como você usa o site</li>
                                <li>Nos ajudam a melhorar a performance</li>
                            </ul>
                            <p class="mb-4">2.3 Cookies de Funcionalidade:</p>
                            <ul class="mb-4">
                                <li>Lembram suas preferências</li>
                                <li>Personalizam sua experiência</li>
                            </ul>
                            <p class="mb-4">2.4 Cookies de Publicidade:</p>
                            <ul class="mb-4">
                                <li>Usados para exibir anúncios relevantes</li>
                                <li>Podem rastrear sua navegação em outros sites</li>
                            </ul>
                            
                            <h2 class="h4 fw-bold mb-4">3. Cookies de Terceiros</h2>
                            <p class="mb-4">Alguns cookies podem ser definidos por serviços de terceiros que utilizamos, como:</p>
                            <ul class="mb-4">
                                <li>Google Analytics - para análise de tráfego</li>
                                <li>Facebook Pixel - para medição de anúncios</li>
                                <li>Hotjar - para análise de experiência do usuário</li>
                            </ul>
                            
                            <h2 class="h4 fw-bold mb-4">4. Gerenciamento de Cookies</h2>
                            <p class="mb-4">4.1 Você pode controlar e/ou excluir cookies como desejar. Para saber mais, visite <a href="https://www.aboutcookies.org/" target="_blank">aboutcookies.org</a>.</p>
                            <p class="mb-4">4.2 Você pode remover todos os cookies já armazenados no seu computador e configurar a maioria dos navegadores para bloqueá-los. No entanto, se fizer isso, pode precisar ajustar manualmente algumas preferências cada vez que visitar um site.</p>
                            
                            <h2 class="h4 fw-bold mb-4">5. Consentimento</h2>
                            <p class="mb-4">Ao continuar a usar nosso site, você concorda com o uso de cookies de acordo com esta política. Na sua primeira visita, solicitamos seu consentimento através de nosso banner de cookies.</p>
                            
                            <h2 class="h4 fw-bold mb-4">6. Alterações nesta Política</h2>
                            <p class="mb-4">Podemos atualizar esta Política periodicamente. Recomendamos que você revise esta página regularmente para se manter informado.</p>
                            
                            <h2 class="h4 fw-bold mb-4">7. Contato</h2>
                            <p>Para dúvidas sobre nossa Política de Cookies, entre em contato através do nosso <a href="./contato.html">formulário de contato</a>.</p>
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
