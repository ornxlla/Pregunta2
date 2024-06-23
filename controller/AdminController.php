<?php
class AdminController
{
    private $model;
    private $presenter;

    public function __construct($presenter, $model)
    {
        $this->presenter = $presenter;
        $this->model = $model;
    }

    public function get (){
        $this->mostrarDatos();
    }


    public function mostrarDatos()
    {
        $totalUsuarios = $this->model->getTotalUsuarios();
        $preguntasTotales= $this->model->getCantidadPreguntas();
        $contadorUsuariosPais = $this->model->getUsuariosPorPais();
        $usuariosMenores=$this->model->usuariosMenores();
        $usuariosAdultos=$this->model->usuariosAdultos();
        $usuariosMayores=$this->model->usuariosMayores();

        $data = array(
            "cantidad_usuarios" => $totalUsuarios,
            "preguntasTotales"=>$preguntasTotales,
            "contadorUsuariosPais"=>$contadorUsuariosPais,
            "usuariosMenores"=>$usuariosMenores,
            "usuariosAdultos"=>$usuariosAdultos,
            "usuariosMayores"=>$usuariosMayores,
        );
        $this->presenter->render('homeAdmin', $data);
    }
}
?>
