<?php

namespace App\Controller;

use Core\Library\ControllerMain;

class Termos extends ControllerMain
{
    public function __construct()
    {
        $this->auxiliarConstruct();
    }

    public function index()
    {
        $this->loadView("termos/termos", [], false);
    }

    public function privacidade()
    {
        $this->loadView("termos/privacidade", [], false);
    }

    public function cookies()
    {
        $this->loadView("termos/cookies", [], false);
    }
}
