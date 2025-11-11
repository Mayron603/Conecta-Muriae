<?php

namespace App\Controller;

use Core\Library\ControllerMain;
use Core\Library\Email;
use Core\Library\Redirect;
use Core\Library\Session;

class Contato extends ControllerMain
{
    public function __construct()
    {
        $this->auxiliarConstruct();
    }

    public function index()
    {
        $this->loadView('contato');
    }

    public function enviar()
    {
        $post = $this->request->getPost();

        if (empty($post['name']) || empty($post['email']) || !filter_var($post['email'], FILTER_VALIDATE_EMAIL) || empty($post['subject']) || empty($post['message'])) {
            Session::set('flash_msg', ['mensagem' => 'Por favor, preencha todos os campos corretamente.', 'tipo' => 'error']);
            return Redirect::page('contato');
        }

        $nomeRemetente  = htmlspecialchars($post['name']);
        $emailRemetente = $post['email'];
        $assuntoForm    = htmlspecialchars($post['subject']);
        $mensagemForm   = nl2br(htmlspecialchars($post['message']));
        $destinatarioAdmin = $_ENV['MAIL.USER'];

        $assuntoAdmin = "Nova Mensagem do Site: " . $assuntoForm;
        $corpoEmailAdmin = "
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset='UTF-8'>
            <style>
                body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; color: #333; background-color: #f4f4f9; margin: 0; padding: 20px; }
                .container { max-width: 600px; margin: auto; background-color: #ffffff; border-radius: 8px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); overflow: hidden; }
                .header { background-color: #0056b3; color: #ffffff; padding: 20px; text-align: center; }
                .header h1 { margin: 0; font-size: 24px; }
                .content { padding: 30px; }
                .content p { line-height: 1.6; }
                .info-table { width: 100%; border-collapse: collapse; margin-top: 20px; }
                .info-table td { padding: 12px; border-bottom: 1px solid #eeeeee; }
                .info-table td:first-child { font-weight: 600; color: #0056b3; width: 100px; }
                .message-box { background-color: #f9f9f9; border-left: 4px solid #0056b3; padding: 15px; margin-top: 20px; }
                .footer { text-align: center; padding: 20px; font-size: 12px; color: #777; background-color: #f4f4f9; }
            </style>
        </head>
        <body>
            <div class='container'>
                <div class='header'><h1>Nova Mensagem de Contato</h1></div>
                <div class='content'>
                    <p>Você recebeu uma nova mensagem através do formulário de contato do site <strong>Muriaé Empregos</strong>.</p>
                    <table class='info-table'>
                        <tr><td><strong>Nome:</strong></td><td>{$nomeRemetente}</td></tr>
                        <tr><td><strong>Email:</strong></td><td><a href='mailto:{$emailRemetente}'>{$emailRemetente}</a></td></tr>
                        <tr><td><strong>Assunto:</strong></td><td>{$assuntoForm}</td></tr>
                    </table>
                    <div class='message-box'>
                        <p><strong>Mensagem:</strong></p>
                        <p>{$mensagemForm}</p>
                    </div>
                </div>
                <div class='footer'><p>&copy; " . date('Y') . " Muriaé Empregos. Todos os direitos reservados.</p></div>
            </div>
        </body>
        </html>";

        // Envia o e-mail para o admin
        $emailEnviado = Email::enviaEmail($destinatarioAdmin, 'Muriaé Empregos - Contato', $assuntoAdmin, $corpoEmailAdmin, $destinatarioAdmin);

        if ($emailEnviado) {
            $assuntoUsuario = "Confirmamos o recebimento da sua mensagem!";
            $corpoEmailUsuario = "
            <!DOCTYPE html>
            <html>
            <head>
                <meta charset='UTF-8'>
                <style>
                    body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; color: #333; background-color: #f4f4f9; margin: 0; padding: 20px; }
                    .container { max-width: 600px; margin: auto; background-color: #ffffff; border-radius: 8px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); overflow: hidden; }
                    .header { background-color: #0056b3; color: #ffffff; padding: 20px 30px; text-align: left; }
                    .header h1 { margin: 0; font-size: 24px; }
                    .content { padding: 30px; }
                    .content p { line-height: 1.7; font-size: 16px; }
                    .message-box { background-color: #f9f9f9; border: 1px solid #eeeeee; border-radius: 5px; padding: 20px; margin-top: 25px; }
                    .message-box h3 { margin-top: 0; color: #0056b3; font-size: 18px; }
                    .footer { text-align: center; padding: 20px; font-size: 12px; color: #777; background-color: #f4f4f9; }
                </style>
            </head>
            <body>
                <div class='container'>
                    <div class='header'><h1>Sua mensagem foi recebida!</h1></div>
                    <div class='content'>
                        <p>Olá, <strong>{$nomeRemetente}</strong>!</p>
                        <p>Agradecemos por entrar em contato com o <strong>Muriaé Empregos</strong>. Confirmamos o recebimento da sua mensagem e nossa equipe irá analisá-la em breve.</p>
                        <p>Faremos o nosso melhor para responder o mais rápido possível.</p>
                        <div class='message-box'>
                            <h3>Sua mensagem:</h3>
                            <p><strong>Assunto:</strong> {$assuntoForm}<br>
                            <strong>Mensagem:</strong> {$mensagemForm}</p>
                        </div>
                    </div>
                    <div class='footer'><p>&copy; " . date('Y') . " Muriaé Empregos. Todos os direitos reservados.</p></div>
                </div>
            </body>
            </html>";
            
            // Envia o e-mail de confirmação para o usuário
            Email::enviaEmail($destinatarioAdmin, 'Muriaé Empregos', $assuntoUsuario, $corpoEmailUsuario, $emailRemetente);

            Session::set('flash_msg', ['mensagem' => 'Sua mensagem foi enviada com sucesso! Agradecemos o seu contato.', 'tipo' => 'success']);
        } else {
            if (empty(Session::get('msgError'))) {
                 Session::set('flash_msg', ['mensagem' => 'Ocorreu um erro ao enviar sua mensagem. Por favor, tente novamente mais tarde.', 'tipo' => 'error']);
            }
        }

        return Redirect::page('contato');
    }
}