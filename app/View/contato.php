<?php include_once __DIR__ . "/comuns/cabecalho.php"; ?>

<!-- Hero Section -->
<section class="hero-section-contato">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-12 text-center">
                <h1 class="hero-title display-4 fw-bold mb-4">Fale Conosco</h1>
                <div class="d-flex justify-content-center">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="<?= baseUrl() ?>">Início</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Contato</li>
                        </ol>
                    </nav>
                </div>
                <p class="hero-subtitle lead mx-auto" style="max-width: 700px;">Estamos aqui para ajudar. Entre em contato conosco por telefone, e-mail ou redes sociais para tirar dúvidas, dar sugestões ou reportar problemas. Atendimento 100% digital.</p>
            </div>
        </div>
    </div>
</section>

<!-- Contact Section -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row g-4 justify-content-center">
            <!-- Contact Cards -->
            <div class="col-md-6">
                <div class="card contact-card h-100 border-0 shadow-sm">
                    <div class="card-body text-center p-4">
                        <div class="contact-icon bg-primary-light mb-4 mx-auto">
                            <i class="fas fa-phone-alt text-primary"></i>
                        </div>
                        <h3 class="h5 fw-bold mb-3">Telefone</h3>
                        <p class="mb-2">(32) 3721-2301</p>
                        <p class="mb-0">(32) 98486-3138 (WhatsApp)</p>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="card contact-card h-100 border-0 shadow-sm">
                    <div class="card-body text-center p-4">
                        <div class="contact-icon bg-primary-light mb-4 mx-auto">
                            <i class="fas fa-envelope text-primary"></i>
                        </div>
                        <h3 class="h5 fw-bold mb-3">Email</h3>
                        <p class="mb-2">contato@muriaeempregos.com</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Contact Form Section -->
<section class="py-5 bg-white">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card border-0 shadow-lg overflow-hidden">
                    <div class="row g-0">
                        <!-- Contact Info -->
                        <div class="col-md-5 contact-info-side">
                            <div class="h-100 p-4 p-md-5 d-flex flex-column">
                                <h2 class="h3 fw-bold text-white mb-4">Informações de Contato</h2>
                                
                                <div class="mb-4">
                                    <div class="d-flex align-items-start mb-3">
                                        <i class="fas fa-phone mt-1 me-3"></i>
                                        <div>
                                            <h3 class="h5 fw-semibold text-white mb-1">Telefone</h3>
                                            <p class="mb-0 text-white-80">
                                                (32) 3721-1234<br>
                                                (32) 99876-5432 (WhatsApp)
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="mb-4">
                                    <div class="d-flex align-items-start mb-3">
                                        <i class="fas fa-envelope mt-1 me-3"></i>
                                        <div>
                                            <h3 class="h5 fw-semibold text-white mb-1">Email</h3>
                                            <p class="mb-0 text-white-80">
                                                contato@muriaeempregos.com<br>
                                                suporte@muriaeempregos.com
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="mt-auto">
                                    <h3 class="h5 fw-semibold text-white mb-3">Redes Sociais</h3>
                                    <div class="d-flex gap-3">
                                        <a href="#" class="text-white fs-5"><i class="fab fa-facebook-f"></i></a>
                                        <a href="#" class="text-white fs-5"><i class="fab fa-instagram"></i></a>
                                        <a href="#" class="text-white fs-5"><i class="fab fa-linkedin-in"></i></a>
                                        <a href="#" class="text-white fs-5"><i class="fab fa-whatsapp"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Form -->
                        <div class="col-md-7">
                            <div class="p-4 p-md-5">
                                <h2 class="h3 fw-bold mb-4">Envie sua mensagem</h2>
                                <p class="text-muted mb-4">Preencha o formulário abaixo e entraremos em contato o mais rápido possível.</p>
                                
                                <form id="contactForm" action="<?= baseUrl() ?>contato/enviar" method="POST">
                                    <div class="mb-3">
                                        <label for="name" class="form-label">Seu Nome <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="name" name="name" required>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="email" class="form-label">Seu Email <span class="text-danger">*</span></label>
                                        <input type="email" class="form-control" id="email" name="email" required>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="subject" class="form-label">Assunto <span class="text-danger">*</span></label>
                                        <select class="form-select" id="subject" name="subject" required>
                                            <option value="" disabled selected>Selecione um assunto</option>
                                            <option value="support">Suporte Técnico</option>
                                            <option value="suggestion">Sugestão</option>
                                            <option value="complaint">Reclamação</option>
                                            <option value="partnership">Parceria</option>
                                            <option value="job">Oportunidades de Trabalho</option>
                                            <option value="other">Outro</option>
                                        </select>
                                    </div>
                                    
                                    <div class="mb-4">
                                        <label for="message" class="form-label">Mensagem <span class="text-danger">*</span></label>
                                        <textarea class="form-control" id="message" name="message" rows="4" required></textarea>
                                    </div>
                                    
                                    <div class="d-grid">
                                        <button type="submit" class="btn btn-primary py-3 fw-semibold">
                                            <i class="fas fa-paper-plane me-2"></i> Enviar Mensagem
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

<!-- FAQ Section -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="display-5 fw-bold mb-3">Perguntas Frequentes</h2>
            <p class="lead mx-auto text-muted" style="max-width: 600px;">Encontre respostas para as dúvidas mais comuns sobre nossa plataforma</p>
        </div>
        
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="accordion custom-accordion" id="faqAccordion">
                    <!-- FAQ Item 1 -->
                    <div class="accordion-item mb-3 border-0 shadow-sm">
                        <h3 class="accordion-header" id="headingOne">
                            <button class="accordion-button collapsed fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                                <i class="fas fa-question-circle text-primary me-2"></i> Como me cadastro na plataforma?
                            </button>
                        </h3>
                        <div id="collapseOne" class="accordion-collapse collapse" aria-labelledby="headingOne" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                O cadastro é simples e rápido. Basta clicar no botão "Cadastrar" no menu superior e preencher o formulário com seus dados pessoais. Você pode escolher se cadastrar como candidato (se está buscando emprego) ou como empresa (se deseja anunciar vagas). Todo o processo leva menos de 5 minutos!
                            </div>
                        </div>
                    </div>
                    
                    <!-- FAQ Item 2 -->
                    <div class="accordion-item mb-3 border-0 shadow-sm">
                        <h3 class="accordion-header" id="headingTwo">
                            <button class="accordion-button collapsed fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                <i class="fas fa-dollar-sign text-primary me-2"></i> Quanto custa para usar a plataforma?
                            </button>
                        </h3>
                        <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                Para candidatos, a plataforma é <strong>totalmente gratuita</strong>. Você pode se cadastrar, criar seu perfil, buscar vagas e se candidatar sem nenhum custo. Para empresas, oferecemos planos flexíveis conforme a necessidade de cada negócio, desde um plano básico gratuito (com limitações) até planos premium com mais recursos. Entre em contato para saber mais sobre nossos planos empresariais.
                            </div>
                        </div>
                    </div>
                    
                    <!-- FAQ Item 3 -->
                    <div class="accordion-item mb-3 border-0 shadow-sm">
                        <h3 class="accordion-header" id="headingThree">
                            <button class="accordion-button collapsed fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                <i class="fas fa-briefcase text-primary me-2"></i> Como posso me candidatar a uma vaga?
                            </button>
                        </h3>
                        <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                Após se cadastrar e fazer login, navegue até a seção "Vagas" ou utilize nossa ferramenta de busca para encontrar oportunidades que combinam com seu perfil. Quando encontrar uma vaga de interesse, clique no botão "Candidatar-se" e segua as instruções. Algumas vagas podem pedir informações adicionais ou o envio de currículo. Você pode acompanhar o status de suas candidaturas na área do candidato.
                            </div>
                        </div>
                    </div>
                    
                    <!-- FAQ Item 4 -->
                    <div class="accordion-item mb-3 border-0 shadow-sm">
                        <h3 class="accordion-header" id="headingFour">
                            <button class="accordion-button collapsed fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                                <i class="fas fa-building text-primary me-2"></i> Como minha empresa pode publicar vagas?
                            </button>
                        </h3>
                        <div id="collapseFour" class="accordion-collapse collapse" aria-labelledby="headingFour" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                Empresas precisam se cadastrar como "Empresa" e verificar seus dados. Após a aprovação do cadastro, você pode publicar vagas diretamente pelo painel administrativo. Cada vaga passa por uma moderação rápida antes de ser publicada. Nossa equipe pode ajudar no processo - entre em contato para mais informações sobre como anunciar suas oportunidades de emprego em nossa plataforma.
                            </div>
                        </div>
                    </div>

                    <!-- FAQ Item 5 -->
                    <div class="accordion-item mb-3 border-0 shadow-sm">
                        <h3 class="accordion-header" id="headingFive">
                            <button class="accordion-button collapsed fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFive" aria-expanded="false" aria-controls="collapseFive">
                                <i class="fas fa-shield-alt text-primary me-2"></i> Meus dados estão seguros na plataforma?
                            </button>
                        </h3>
                        <div id="collapseFive" class="accordion-collapse collapse" aria-labelledby="headingFive" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                Sim, a segurança dos dados dos nossos usuários é nossa prioridade. Utilizamos tecnologias de criptografia para proteger suas informações e seguimos as melhores práticas de segurança da informação. Nunca compartilhamos seus dados com terceiros sem sua autorização. Você pode saber mais sobre como protegemos seus dados em nossa <a href="<?= baseUrl() ?>privacidade">Política de Privacidade</a>.
                            </div>
                        </div>
                    </div>
                </div>

                <div class="text-center mt-5">
                    <p class="mb-3">Não encontrou a resposta que procurava?</p>
                    <a href="#" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#contactModal">
                        <i class="fas fa-headset me-2"></i> Fale com nosso suporte
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<script src="<?= baseUrl() ?>assets/js/contato.js"></script>

<?php include_once __DIR__ . "/comuns/rodape.php"; ?>