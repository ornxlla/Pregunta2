<?php

class PerfilUsuarioController
{
    private $presenter;
    private $model;

    public function __construct($presenter, $model){
        $this->presenter = $presenter;
        $this->model = $model;
    }

    public function toPerfilUsuario(){
        $this->presenter->render("perfil-usuario");
    }

    public function getUsuario()
    {
        if (isset($_GET['id_usuario'])) {
            $id_usuario = $_GET['id_usuario'];
            $data["usuario"] = $this->model->getUsuarioLogueado($id_usuario);
            if ($data["usuario"]) {
                $this->presenter->render("perfil-usuario", $data);
            } else {
                echo "Usuario no encontrado.";
            }
        } else {
            echo "ID de usuario no encontrado";
        }
    }
    /*METODOS DE PLAY, se ponen aca porque para que se pueda acceder
    a play se tiene que estar logueado o algo esta mal relacionado? SOLO ASI ME ANDA


      public function saludaPlay(){
        echo "hola play";

    }

    public function getVistaPlay(){
        $this->presenter->render("playView");

    }*/
}