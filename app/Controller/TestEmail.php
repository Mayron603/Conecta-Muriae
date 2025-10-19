<?php

namespace App\Controller;

use Core\Library\ControllerMain;
use Core\Library\Email;
use Core\Library\Session;

class TestEmail extends ControllerMain
{
    public function sendTestEmail()
    {
        // Vamos usar o e-mail do seu .env como remetente e destinatário
        $email = $_ENV['MAIL.USER'];
        $nome = $_ENV['MAIL.NOME'];

        $assunto = 'AtomPHP - E-mail de Teste';
        $corpo = '<h1>Teste de Envio de E-mail</h1><p>Se você recebeu este e-mail, a configuração do seu sistema de e-mail no AtomPHP está funcionando corretamente.</p>';

        echo "<p>Tentando enviar um e-mail de teste de <b>{$email}</b> para <b>{$email}</b>...</p>";

        if (Email::enviaEmail($email, $nome, $assunto, $corpo, $email)) {
            echo "<p style='color:green; font-weight:bold;'>E-mail de teste enviado com sucesso!</p><p>Verifique sua caixa de entrada.</p>";
        } else {
            echo "<p style='color:red; font-weight:bold;'>Falha ao enviar o e-mail de teste.</p> <p>Erro: " . Session::get('msgError') . "</p>";
            echo "<hr><p><b>Possíveis causas:</b></p>";
            echo "<ul>";
            echo "<li>Verifique se as credenciais (MAIL.USER, MAIL.PASSWORD) no seu arquivo .env estão corretas.</li>";
            echo "<li>Lembre-se que para o Gmail, você precisa usar uma <b>'Senha de App'</b>, e não sua senha normal.</li>";
            echo "</ul>";
        }
    }
}
