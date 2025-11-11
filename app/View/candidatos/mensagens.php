<?php 
    include_once __DIR__ . '/comuns/candidato_cabecalho.php'; 
?>
<?php 
$conversas = $dados['conversas'] ?? [];
$mensagens = $dados['mensagens'] ?? [];
$id_conversa_ativa = $dados['id_conversa_ativa'] ?? null;
$nome_destinatario = $dados['nome_destinatario'] ?? 'Chat';
$id_candidato = $dados['id_candidato'] ?? null;

$id_empresa_ativa = null;
if ($id_conversa_ativa) {
    foreach ($conversas as $c) {
        if ($c['id_conversa'] == $id_conversa_ativa) {
            $id_empresa_ativa = $c['id_empresa'];
            break;
        }
    }
}
?>
<div class="container-fluid py-4">
    <div class="row">
        <?php include_once __DIR__ . '/comuns/sidebar.php'; ?>
        <div class="col-lg-9">
            <div class="card">
                <div class="card-header">
                    <h4>Mensagens</h4>
                </div>
                <div class="card-body p-0">
                    <div class="chat-container">
                        <div class="conversations-list">
                            <div class="list-group list-group-flush">
                                <?php if (!empty($conversas)):
                                    foreach ($conversas as $conversa):
                                ?>
                                        <a href="<?= baseUrl() ?>mensagemCandidato/chat/<?= $conversa['id_conversa'] ?>" class="list-group-item list-group-item-action <?= ($conversa['id_conversa'] == $id_conversa_ativa) ? 'active' : '' ?>">
                                            <?= htmlspecialchars($conversa['nome_empresa']) ?>
                                        </a>
                                <?php 
                                    endforeach;
                                else: 
                                ?>
                                    <div class="list-group-item">Nenhuma conversa encontrada.</div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="chat-window">
                            <?php if ($id_conversa_ativa): ?>
                                <div class="card-header bg-light">
                                    <strong><?= htmlspecialchars($nome_destinatario) ?></strong>
                                </div>
                                <div class="chat-messages" id="chat-messages">
                                    <?php foreach ($mensagens as $msg): ?>
                                        <?php if ($msg['id_remetente'] == $id_candidato): ?>
                                            <div class="message sent">
                                                <p><?= htmlspecialchars($msg['mensagem']) ?></p>
                                                <small class="text-muted"><?= date('d/m/Y H:i', strtotime($msg['data_envio'])) ?></small>
                                            </div>
                                        <?php else: ?>
                                            <div class="message received">
                                                <p><?= htmlspecialchars($msg['mensagem']) ?></p>
                                                <small class="text-muted"><?= date('d/m/Y H:i', strtotime($msg['data_envio'])) ?></small>
                                            </div>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </div>
                                <div class="chat-form">
                                    <form action="<?= baseUrl() ?>mensagemCandidato/enviar" method="post">
                                        <div class="input-group">
                                            <input type="hidden" name="id_conversa" value="<?= $id_conversa_ativa ?>">
                                            <input type="hidden" name="id_destinatario" value="<?= $id_empresa_ativa ?>">
                                            <input type="text" name="mensagem" class="form-control" placeholder="Digite sua mensagem" required autofocus>
                                            <button class="btn btn-primary" type="submit">Enviar</button>
                                        </div>
                                    </form>
                                </div>
                            <?php else: ?>
                                <div class="d-flex justify-content-center align-items-center h-100">
                                    <p>Selecione uma conversa para começar.</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php 
    include_once __DIR__ . '/comuns/candidato_rodape.php'; 
?>