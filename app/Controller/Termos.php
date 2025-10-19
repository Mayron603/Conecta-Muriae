<?php

namespace App\Controller;

use Core\Library\ControllerMain;

class Termos extends ControllerMain
{
    public function __construct()
    {
        $this->auxiliarConstruct();
    }

    /**
     * Carrega a página de Termos de Uso.
     */
    public function index()
    {
        // Carrega a view, mas DESLIGA o carregamento automático do cabeçalho/rodapé padrão.
        $this->loadView("termos/termos", [], false);
    }

    /**
     * Carrega a página de Política de Privacidade.
     */
    public function privacidade()
    {
        // Carrega a view, mas DESLIGA o carregamento automático do cabeçalho/rodapé padrão.
        $this->loadView("termos/privacidade", [], false);
    }

    /**
     * Carrega a página de Política de Cookies.
     */
    public function cookies()
    {
        // Carrega a view, mas DESLIGA o carregamento automático do cabeçalho/rodapé padrão.
        $this->loadView("termos/cookies", [], false);
    }
}
