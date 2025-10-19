<?php

if (!function_exists('email_redefinir_senha')) {
    /**
     * Gera o corpo do e-mail para redefinição de senha.
     *
     * @param string $nomeUsuario O nome do usuário.
     * @param string $link O link para redefinição.
     * @return string O corpo do e-mail em HTML.
     */
    function email_redefinir_senha($nomeUsuario, $link)
    {
        return "
        <div style='font-family: Arial, sans-serif; background-color: #f9f9f9; padding: 20px;'>
            <div style='max-width: 600px; margin: auto; background: #fff; border-radius: 8px; padding: 30px; box-shadow: 0 4px 10px rgba(0,0,0,0.1);'>
                <h2 style='color: #0056b3; text-align: center;'>🔒 Redefinição de Senha</h2>
                <p style='font-size: 15px; color: #333;'>Olá, <strong>{$nomeUsuario}</strong>.</p>
                <p style='font-size: 15px; color: #333;'>
                    Recebemos uma solicitação para redefinir a senha da sua conta.
                </p>
                <p style='font-size: 15px; color: #333;'>
                    Clique no botão abaixo para escolher uma nova senha. 
                    <br><strong>Este link é válido por apenas 1 hora.</strong>
                </p>
                <div style='text-align: center; margin: 25px 0;'>
                    <a href='{$link}' style='background-color: #007bff; color: #fff; font-size: 16px; padding: 12px 25px; text-decoration: none; border-radius: 6px; display: inline-block;'>
                        🔑 Redefinir Senha
                    </a>
                </div>
                <p style='font-size: 14px; color: #666;'>
                    Se você não solicitou a redefinição de senha, pode ignorar este e-mail com segurança.
                </p>
                <hr style='margin: 30px 0; border: none; border-top: 1px solid #ddd;'>
                <p style='font-size: 12px; color: #888; text-align: center;'>
                    Se o botão não funcionar, copie e cole o link abaixo no seu navegador:<br>
                    <a href='{$link}' style='color: #0056b3; word-break: break-all;'>{$link}</a>
                </p>
            </div>
        </div>
        ";
    }
}
