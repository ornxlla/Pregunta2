<?php
class AdminController
{
    private $adminModel;
    private $presenter;

    public function __construct($presenter, $adminModel)
    {
        $this->presenter = $presenter;
        $this->adminModel = $adminModel;
    }


    public function mostrarDatos()
    {
        $totalUsers = $this->adminModel->getTotalUsers();
        $preguntasTotales= $this->adminModel->getCantidadPreguntas();
        $contadorUsuariosPais = $this->adminModel->getUsuariosPorPais();

        $data = array(
            "cantidad_usuarios" => $totalUsers,"preguntasTotales"=>$preguntasTotales,"contadosUsuariosPais"=>$contadorUsuariosPais,

        );


        $this->presenter->render('homeAdminView', $data);
    }
}
?>
