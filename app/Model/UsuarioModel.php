<?php

namespace App\Model;

use Core\Library\ModelMain;

class UsuarioModel extends ModelMain
{
    protected $table = "usuario";
    protected $primaryKey = "usuario_id";

    public $validationRules = [
        "login"  => [
            "label" => 'E-mail (Login)',
            "rules" => 'required|valid_email|max:50'
        ],
        "senha"  => [
            "label" => 'Senha',
            "rules" => 'required'
        ],
        "tipo"  => [
            "label" => 'Tipo',
            "rules" => 'required|max:2'
        ]
    ];

    /**
     * Busca um usuário pelo seu login (email).
     */
    public function getUserEmail($login)
    {
        return $this->db->table($this->table)->where("login", $login)->first();
    }
    
    /**
     * Busca um usuário pelo seu token de redefinição de senha.
     */
    public function getUserByToken($token)
    {
        return $this->db->table($this->table)->where('reset_token', $token)->first();
    }

    /**
     * Salva o token de redefinição no banco sem disparar as validações globais.
     */
    public function setResetToken($usuarioId, $token, $expires)
    {
        $data = [
            'reset_token' => $token,
            'reset_expires_at' => $expires
        ];
        return $this->db->table($this->table)->where($this->primaryKey, $usuarioId)->update($data);
    }
    
    /**
     * Atualiza a senha do usuário e limpa o token de redefinição.
     */
    public function updatePassword($usuarioId, $newPassword)
    {
        $data = [
            'senha' => password_hash($newPassword, PASSWORD_DEFAULT),
            'reset_token' => null,
            'reset_expires_at' => null
        ];
        return $this->db->table($this->table)->where($this->primaryKey, $usuarioId)->update($data);
    }

    /**
     * Método genérico de registro de usuário.
     */
    public function registrarUsuario($dados)
    {
        return $this->insert($dados);
    }
}
