<?php

namespace App\Model;

use Core\Library\ModelMain;

class UsuarioRecuperaSenhaModel extends ModelMain
{
    protected $table = "usuariorecuperasenha";

    public function getRecuperaSenhaChave($chave) 
    {
        return $this->db->where(["statusRegistro" => 1, "chave" => $chave])->first();
    }

    function desativaChave($id) 
    {
        $rs = $this->db->where(["id" => $id])->update(["statusRegistro" => 2, "updated_at" => date("Y-m-d H:i:s")]);
        
        if ($rs > 0) {
            return true;
        } else {
            return false;
        }      
    }

    function desativaChaveAntigas($id) 
    {
        $rs = $this->db->where(["id <>" => $id])->update(["statusRegistro" => 2]);
        
        if ($rs > 0) {
            return true;
        } else {
            return false;
        }      
    }
    
}