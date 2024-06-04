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
}