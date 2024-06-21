<?php

class RankingController
{

    private $presenter;
    private $model;

    public function __construct($presenter, $model)
    {
        $this->presenter = $presenter;
        $this->model = $model;
    }


    public function get()
    {
        if (isset($_SESSION["Session_id"])) {
            $usuarios = $this->model->getRanking();


            if ($usuarios !== false) {
                $data = [];


                $primerUsuario = array_shift($usuarios);
                $data["primer_usuario"] = $primerUsuario;


                $otrosUsuarios = [];
                foreach ($usuarios as $index => $usuario) {
                    $usuario["index"] = $index + 2;
                    $otrosUsuarios[] = $usuario;
                }
                $data["otros_usuarios"] = $otrosUsuarios;


                $this->presenter->render("rankingView", $data);
            } else {

                die("Error al obtener el ranking.");
            }
        } else {
            // Redirigir si no hay sesión activa
            Redirect::root();
        }

    }
}