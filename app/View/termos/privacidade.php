<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Política de Privacidade - MuriaeEmpregos</title>
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
    <section class="hero-section-privacidade">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-12 text-center">
                    <h1 class="hero-title display-4 fw-bold">Política de Privacidade</h1>
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
                            <h2 class="h4 fw-bold mb-4">1. Introdução</h2>
                            <p class="mb-4">Esta Política explica como coletamos, usamos, compartilhamos e protegemos suas informações pessoais, em conformidade com a Lei Geral de Proteção de Dados (LGPD - Lei nº 13.709/2018).</p>
                            
                            <h2 class="h4 fw-bold mb-4">2. Dados que Coletamos</h2>
                            <p class="mb-4">2.1 Dados fornecidos voluntariamente:</p>
                            <ul class="mb-4">
                                <li>Informações de cadastro (nome, e-mail, telefone)</li>
                                <li>Dados profissionais (currículo, formação, experiência)</li>
                                <li>Dados empresariais (CNPJ, endereço, informações de contato)</li>
                            </ul>
                            <p class="mb-4">2.2 Dados coletados automaticamente:</p>
                            <ul class="mb-4">
                                <li>Dados de acesso (IP, navegador, dispositivo)</li>
                                <li>Cookies e tecnologias similares</li>
                            </ul>
                            
                            <h2 class="h4 fw-bold mb-4">3. Finalidades do Tratamento</h2>
                            <p class="mb-4">Seus dados são utilizados para:</p>
                            <ul class="mb-4">
                                <li>Fornecer e melhorar nossos serviços</li>
                                <li>Facilitar o processo de recrutamento</li>
                                <li>Comunicações relacionadas ao serviço</li>
                                <li>Segurança e prevenção de fraudes</li>
                                <li>Cumprimento de obrigações legais</li>
                            </ul>
                            
                            <h2 class="h4 fw-bold mb-4">4. Compartilhamento de Dados</h2>
                            <p class="mb-4">4.1 Seus dados podem ser compartilhados com:</p>
                            <ul class="mb-4">
                                <li>Empresas para as quais você se candidata</li>
                                <li>Prestadores de serviços terceirizados</li>
                                <li>Autoridades quando exigido por lei</li>
                            </ul>
                            <p class="mb-4">4.2 Não vendemos seus dados pessoais.</p>
                            
                            <h2 class="h4 fw-bold mb-4">5. Seus Direitos</h2>
                            <p class="mb-4">Conforme a LGPD, você tem direito a:</p>
                            <ul class="mb-4">
                                <li>Acesso aos seus dados</li>
                                <li>Correção de dados incompletos ou inexatos</li>
                                <li>Exclusão de dados tratados com seu consentimento</li>
                                <li>Portabilidade dos dados</li>
                                <li>Revogação do consentimento</li>
                            </ul>
                            
                            <h2 class="h4 fw-bold mb-4">6. Segurança de Dados</h2>
                            <p class="mb-4">Implementamos medidas técnicas e administrativas para proteger seus dados contra acesso não autorizado e situações acidentais ou ilícitas.</p>
                            
                            <h2 class="h4 fw-bold mb-4">7. Retenção de Dados</h2>
                            <p class="mb-4">Mantemos seus dados apenas enquanto necessário para as finalidades descritas ou conforme exigido por lei.</p>
                            
                            <h2 class="h4 fw-bold mb-4">8. Alterações nesta Política</h2>
                            <p class="mb-4">Podemos atualizar esta Política periodicamente. Alterações significativas serão comunicadas.</p>
                            
                            <h2 class="h4 fw-bold mb-4">9. Contato</h2>
                            <p>Para exercer seus direitos ou dúvidas sobre privacidade, entre em contato pelo e-mail: <a href="mailto:privacidade@muriaeempregos.com">privacidade@muriaeempregos.com</a></p>
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
