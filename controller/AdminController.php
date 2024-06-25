<?php
require_once __DIR__ . '/../vendor/autoload.php';

use Dompdf\Dompdf;
use Dompdf\Options;
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
        $nuevosUsuarios=$this->model->nuevosUsuarios();
        $usuariosGeneroF=$this->model->usuariosGeneroF();
        $usuariosGeneroM=$this->model->usuariosGeneroM();
        $preguntasCreadas=$this->model->preguntasCreadas();
        $partidasClasicas=$this->model->partidasClasicas();
        $partidasDuelo=$this->model->partidasDuelo();
        $porcentajeRespUser=$this->model->porcentajeRespUser();

        $data = array(
            "cantidad_usuarios" => $totalUsuarios,
            "preguntasTotales"=>$preguntasTotales,
            "contadorUsuariosPais"=>$contadorUsuariosPais,
            "usuariosMenores"=>$usuariosMenores,
            "usuariosAdultos"=>$usuariosAdultos,
            "usuariosMayores"=>$usuariosMayores,
            "nuevosUsuarios"=>$nuevosUsuarios,
            "usuariosGeneroF"=>$usuariosGeneroF,
            "usuariosGeneroM"=>$usuariosGeneroM,
            "preguntasCreadas"=>$preguntasCreadas,
            "partidasClasicas"=>$partidasClasicas,
            "partidasDuelo"=>$partidasDuelo,
            "porcentajeRespUser"=>$porcentajeRespUser,
        );
        $this->presenter->render('homeAdmin', $data);
    }



    public function generarPDF()
    {
        // Obtener datos necesarios para el PDF desde el modelo
        $data = [
            'title' => 'Reporte de Usuarios',
            'totalUsuarios' => $this->model->getTotalUsuarios(),
            'preguntasTotales' => $this->model->getCantidadPreguntas(),
            'usuariosPorPais' => $this->model->getUsuariosPorPais(),
            'usuariosMenores' => $this->model->usuariosMenores(),
            'usuariosAdultos' => $this->model->usuariosAdultos(),
            'usuariosMayores' => $this->model->usuariosMayores(),
            'nuevosUsuarios' => $this->model->nuevosUsuarios(),
            'usuariosGeneroF' => $this->model->usuariosGeneroF(),
            'usuariosGeneroM' => $this->model->usuariosGeneroM(),
            'preguntasCreadas' => $this->model->preguntasCreadas(),
            'partidasClasicas' => $this->model->partidasClasicas(),
            'partidasDuelo' => $this->model->partidasDuelo(),
        ];

        // Configuracion
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $dompdf = new Dompdf($options);


        $html = $this->renderToString('homeAdmin', $data); // Ejemplo de método en el presentador para renderizar vista

        $dompdf->loadHtml($html);


        $dompdf->setPaper('A4', 'portrait');


        $dompdf->render();


        $dompdf->stream('reporte_usuarios.pdf', [
            'Attachment' => true // Forzar la descarga
        ]);
    }

    private function renderToString($viewName, $data)
    {
        $mustache = new Mustache_Engine([
            'loader' => new Mustache_Loader_FilesystemLoader(__DIR__ . '/../view'),
        ]);

        $template = $mustache->loadTemplate($viewName . '.mustache');
        return $template->render($data);
    }


    // ReportesController.php
/*
    public function graficoVentas() {
        // Aquí se genera el gráfico usando JPGraph

        // 1. Incluir la librería de JPGraph
        require_once "../vendor/jpgraph-example/jpgraph/src/jpgraph.php";
        require_once "../vendor/jpgraph-example/jpgraph/src/jpgraph_bar.php"; // Ejemplo con gráfico de barras, ajustar según el tipo de gráfico que necesites

        // 2. Crear los datos del gráfico (ejemplo)
        $data = array(50, 80, 160, 240, 200);

        // 3. Crear el objeto del gráfico
        $graph = new Graph(400, 300);
        $graph->SetScale("textlin");

        // 4. Crear el objeto del gráfico de barras
        $barplot = new BarPlot($data);

        // 5. Añadir el gráfico de barras al gráfico
        $graph->Add($barplot);

        // 6. Configurar el título y etiquetas
        $graph->title->Set("Ventas por mes");
        $graph->xaxis->title->Set("Mes");
        $graph->yaxis->title->Set("Ventas");

        // 7. Salida del gráfico
        $graph->Stroke();
    }*/



}

?>
