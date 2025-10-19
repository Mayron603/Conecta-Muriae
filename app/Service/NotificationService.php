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
     * Cria uma notificação padrão sobre uma nova candidatura a uma vaga.
     *
     * @param int   $pessoaFisicaId O ID do usuário que se candidatou.
     * @param array $vaga           Os dados da vaga (precisa ter 'descricao' e 'estabelecimento_nome').
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
     * Cria uma notificação padrão sobre a alteração de senha.
     *
     * @param int $pessoaFisicaId O ID do usuário que alterou a senha.
     * @return void
     */
    public function notificaSenhaAlterada(int $pessoaFisicaId)
    {
        $titulo = "Segurança: Senha Alterada";
        $mensagem = "Sua senha foi alterada com sucesso. Se você não reconhece esta ação, entre em contato com o suporte.";
        $link = "/candidatos/configuracoes"; // Link para a página de configurações

        $this->notificacaoModel->create($pessoaFisicaId, $titulo, $mensagem, $link);
    }
    
    // Futuramente, podemos adicionar outros métodos de notificação aqui.
    // Ex: notificaVagaFechada(), notificaRecadoEmpresa(), etc.
}
