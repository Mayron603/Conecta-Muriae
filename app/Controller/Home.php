<?php
// app\controller\Home.php

namespace App\Controller;

use Core\Library\ControllerMain;

class Home extends ControllerMain
{
    public function index()
    {
        $vagaModel = $this->loadModel("Vaga");
        $estabelecimentoModel = $this->loadModel("Estabelecimento");
        $pessoaFisicaModel = $this->loadModel("PessoaFisica");

        $dados = [
            'vagasAtivas' => $vagaModel->countAtivas(),
            'empresasCadastradas' => $estabelecimentoModel->countAll(),
            'candidatos' => $pessoaFisicaModel->countAll(),
        ];

        $this->loadView("home", $dados);
    }

    public function sobre($action = null)
    {
        echo "Página sobre nós. AÇÃO: {$action}";
    }

    public function produtos()
    {
        $PessoaModel = $this->loadModel("Pessoa");

        return $this->loadView("produtos", $PessoaModel->lista("nome"));
    }

    public function detalhes($action = null, $id = null, ...$params)
    {
        echo "Detalhes: <br />";
        echo "<br />Ação: " . $action;
        echo "<br />ID: " . $id;
        echo "<br />PARÂMETROS: " . implode(", ", $params);
    }
}