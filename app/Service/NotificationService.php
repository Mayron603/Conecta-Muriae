<?php

namespace App\Service;

use App\Model\NotificacaoModel;

class NotificationService
{
    private $notificacaoModel;

    public function __construct()
    {
        $this->notificacaoModel = new NotificacaoModel();
    }

    /**
     * @param int
     * @param array 
     * @return void
     */
    public function notificaNovaCandidatura(int $pessoaFisicaId, array $vaga)
    {
        $titulo = "Candidatura Realizada com Sucesso";
        $mensagem = "Parabéns! Você se candidatou para a vaga de " . 
                    htmlspecialchars($vaga['descricao']) . 
                    " na empresa " . htmlspecialchars($vaga['estabelecimento_nome']) . ".";
        $link = "/candidatos/minhasCandidaturas";

        $this->notificacaoModel->create($pessoaFisicaId, $titulo, $mensagem, $link);
    }

    /**
     * @param int
     * @return void
     */
    public function notificaSenhaAlterada(int $pessoaFisicaId)
    {
        $titulo = "Segurança: Senha Alterada";
        $mensagem = "Sua senha foi alterada com sucesso. Se você não reconhece esta ação, entre em contato com o suporte.";
        $link = "/candidatos/configuracoes";

        $this->notificacaoModel->create($pessoaFisicaId, $titulo, $mensagem, $link);
    }
    
}
