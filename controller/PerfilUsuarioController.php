<?php

class PerfilUsuarioController
{
    public function __construct($presenter, $model){
        $this->presenter = $presenter;
        $this->model = $model;
    }

    public function toPerfilUsuario(){
        $this->presenter->render("perfil-usuario");
    }
    public function getUsuario()
    {
        $id_usuario = $_GET["id_usuario"];
        $this->model->getUsuarioLogueado($id_usuario);
    }

    public function saludaUser()
    {
       echo "hola user";
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