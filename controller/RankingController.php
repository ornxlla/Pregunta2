<?php

class RankingController
{
    private $model;
    private $presenter;

    public function __construct($model, $presenter){
        $this->model = $model;
        $this->presenter = $presenter;
    }



    public function getRankingView(){
        $usuarios = $this->model->getRanking();
        $data["primer_usuario"] = array_shift($usuarios);
        foreach ($usuarios as $index => $usuario) {
            $usuarios[$index]["index"] = $index + 1;
        }

        $data["otros_usuarios"] = $usuarios; // Los demás usuarios
        $this->presenter->render("rankingView", $data);
    }
}