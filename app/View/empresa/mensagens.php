<?php 
    $pathView = __DIR__ . '/../../View/';
    require_once $pathView . 'empresa/comuns/empresa_cabecalho.php'; 
?>
<style>
    .chat-container {
        display: flex;
        height: 70vh;
    }
    .conversations-list {
        border-right: 1px solid #dee2e6;
        flex-basis: 30%;
        overflow-y: auto;
    }
    .chat-window {
        flex-basis: 70%;
        display: flex;
        flex-direction: column;
    }
    .chat-messages {
        flex-grow: 1;
        overflow-y: auto;
        padding: 1rem;
        background-color: #f8f9fa;
    }
    .message {
        display: flex;
        margin-bottom: 1rem;
        max-width: 80%;
    }
    .message p {
        padding: 0.75rem 1.25rem;
        border-radius: 1.25rem;
        word-wrap: break-word;
        line-height: 1.4;
    }
    .message-info {
        font-size: 0.75rem;
        color: #6c757d;
        margin-top: 0.25rem;
    }
    .message.sent {
        margin-left: auto;
        flex-direction: row-reverse;
    }
    .message.sent p {
        background-color: #0d6efd;
        color: white;
        border-bottom-right-radius: 0.5rem;
    }
    .message.sent .message-info {
        text-align: right;
        margin-right: 0.5rem;
    }
    .message.received {
        margin-right: auto;
    }
    .message.received p {
        background-color: #e9ecef;
        color: #212529;
        border-bottom-left-radius: 0.5rem;
    }
    .message.received .message-info {
       margin-left: 0.5rem;
    }
    .chat-form {
        padding: 1rem;
        border-top: 1px solid #dee2e6;
        background-color: #fff;
    }
</style>

<?php 
$conversas = $dados['conversas'] ?? [];
$mensagens = $dados['mensagens'] ?? [];
$id_conversa_ativa = $dados['id_conversa_ativa'] ?? null;
$nome_destinatario = $dados['nome_destinatario'] ?? 'Chat';
$id_empresa_logada = $dados['usuario']['usuario_id'] ?? null;

$id_candidato_ativo = null;
if ($id_conversa_ativa) {
    foreach ($conversas as $c) {
        if ($c['id_conversa'] == $id_conversa_ativa) {
            $id_candidato_ativo = $c['id_candidato'];
            break;
        }
    }
}
?>
<div class="container-fluid py-4">
    <div class="row">
        <?php require_once $pathView . 'empresa/comuns/sidebar.php'; ?>
        <div class="col-lg-9">
            <div class="card shadow-sm">
                <div class="card-header">
                    <h4 class="mb-0">Mensagens</h4>
                </div>
                <div class="card-body p-0">
                    <div class="chat-container">
                        <div class="conversations-list">
                            <div class="list-group list-group-flush">
                                <?php if (!empty($conversas)): foreach ($conversas as $conversa): ?>
                                        <a href="<?= baseUrl() ?>mensagem/chat/<?= $conversa['id_conversa'] ?>" class="list-group-item list-group-item-action <?= ($conversa['id_conversa'] == $id_conversa_ativa) ? 'active' : '' ?>">
                                            <div class="d-flex w-100 justify-content-between">
                                                <h6 class="mb-1"><?= htmlspecialchars($conversa['nome_candidato']) ?></h6>
                                            </div>
                                            <small>Clique para ver a conversa.</small>
                                        </a>
                                <?php endforeach; else: ?>
                                    <div class="list-group-item">Nenhuma conversa encontrada.</div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="chat-window">
                            <?php if ($id_conversa_ativa): ?>
                                <div class="card-header bg-light">
                                    <h5 class="mb-0"><?= htmlspecialchars($nome_destinatario) ?></h5>
                                </div>
                                <div class="chat-messages" id="chat-messages">
                                    <?php foreach ($mensagens as $msg): ?>
                                        <?php if ($msg['id_remetente'] == $id_empresa_logada): ?>
                                            <div class="message sent">
                                                <div>
                                                    <p><?= htmlspecialchars($msg['mensagem']) ?></p>
                                                    <div class="message-info"><?= date('H:i', strtotime($msg['data_envio'])) ?></div>
                                                </div>
                                            </div>
                                        <?php else: ?>
                                            <div class="message received">
                                                <div>
                                                    <p><?= htmlspecialchars($msg['mensagem']) ?></p>
                                                    <div class="message-info"><?= date('H:i', strtotime($msg['data_envio'])) ?></div>
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </div>
                                <div class="chat-form">
                                    <form action="<?= baseUrl() ?>mensagem/enviar" method="post">
                                        <div class="input-group">
                                            <input type="hidden" name="id_conversa" value="<?= $id_conversa_ativa ?>">
                                            <input type="hidden" name="id_destinatario" value="<?= $id_candidato_ativo ?>">
                                            <input type="text" name="mensagem" class="form-control" placeholder="Digite sua mensagem..." required autocomplete="off" autofocus>
                                            <button class="btn btn-primary" type="submit" aria-label="Enviar Mensagem">
                                                <i class="fas fa-paper-plane"></i>
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            <?php else: ?>
                                <div class="d-flex justify-content-center align-items-center h-100 flex-column text-center">
                                    <i class="fas fa-comments fa-3x text-muted mb-3"></i>
                                    <h5 class="text-muted">Selecione uma conversa</h5>
                                    <p class="text-muted">Escolha um candidato na lista ao lado para ver o histórico de mensagens.</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const chatMessages = document.getElementById('chat-messages');
        if (chatMessages) {
            chatMessages.scrollTop = chatMessages.scrollHeight;
        }
    });
</script>
<?php 
    require_once $pathView . 'comuns/rodape.php'; 
?>
