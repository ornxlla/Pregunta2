<?php
require_once ('vendor/autoload.php');
require_once('vendor/jpgraph/src/jpgraph.php');
require_once('vendor/jpgraph/src/jpgraph_bar.php');
require_once('vendor/jpgraph/src/jpgraph_pie.php');
require_once('vendor/jpgraph/src/jpgraph_line.php');

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

    public function get()
    {
        $this->mostrarHome();
    }
//-----------------------------------------------------------------------------------*/
public function mostrarHome(){
        $this->presenter->render('homeAdmin');
}

    public function mostrarDatosPorFechas()
    {
        $data = [];

        if (isset($_POST['fechaInicio'], $_POST['fechaFin'])) {
            $fechaInicio = $_POST['fechaInicio'];
            $fechaFin = $_POST['fechaFin'];

            $data['preguntasTotales'] = $this->model->getPreguntasTotales($fechaInicio, $fechaFin);
            $data['cantidad_usuarios'] = $this->model->getTotalUsuarios($fechaInicio, $fechaFin);
            $data['contadorUsuariosPais'] = $this->model->getUsuariosPorPais($fechaInicio, $fechaFin);
            $data['usuariosMenores'] = $this->model->usuariosMenores($fechaInicio, $fechaFin);
            $data['usuariosAdultos'] = $this->model->usuariosAdultos($fechaInicio, $fechaFin);
            $data['usuariosMayores'] = $this->model->usuariosMayores($fechaInicio, $fechaFin);
            $data['nuevosUsuarios'] = $this->model->nuevosUsuarios($fechaInicio, $fechaFin);
            $data['usuariosGeneroF'] = $this->model->usuariosGeneroF($fechaInicio, $fechaFin);
            $data['usuariosGeneroM'] = $this->model->usuariosGeneroM($fechaInicio, $fechaFin);
            $data['preguntasCreadas'] = $this->model->preguntasCreadas($fechaInicio, $fechaFin);
            $data['partidasClasicas'] = $this->model->partidasClasicas($fechaInicio, $fechaFin);
            $data['partidasDuelo'] = $this->model->partidasDuelo($fechaInicio, $fechaFin);
            $data['porcentajeRespUser'] = $this->model->porcentajeRespUser($fechaInicio, $fechaFin);

            $this->generarGraficos($data, $fechaInicio, $fechaFin);
        }
        $this->presenter->render('homeAdmin', $data);
        return $data;
    }

    private function generarGraficos(&$data, $fechaInicio, $fechaFin)
    {
        $this->generarGraficoPreguntasTotales($data['preguntasTotales'], $fechaInicio, $fechaFin);
        $this->generarGraficoTotalUsuarios($data['cantidad_usuarios'], $fechaInicio, $fechaFin);
        $this->generarGraficoUsuariosPorEdad($data['usuariosMenores'], $data['usuariosAdultos'], $data['usuariosMayores']);
        $this->generarGraficoUsuariosPorGenero($data['usuariosGeneroF'], $data['usuariosGeneroM']);
        $this->generarGraficoPreguntasCreadas($data['preguntasCreadas'], $fechaInicio, $fechaFin);
        $this->generarGraficoPartidas($data['partidasClasicas'], $data['partidasDuelo']);
        $this->generarGraficoPorcentajeRespUser($data['porcentajeRespUser']);
        $this->generarGraficoUsuariosPorPais($data['contadorUsuariosPais']);
    }
//------------------------------------------------------------------------------------------------
    public function generarGraficoUsuariosPorEdad($usuariosMenores, $usuariosAdultos, $usuariosMayores)
    {
        // Datos para el gráfico
        $labels = array('Menores', 'Adultos', 'Mayores');
        $data = array(
            intval($usuariosMenores[0]['usuariosMenores'] ?? 0),
            intval($usuariosAdultos[0]['usuariosAdultos'] ?? 0),
            intval($usuariosMayores[0]['usuariosMayores'] ?? 0)
        );

        // Verificar si la suma de los datos es cero
        if (array_sum($data) == 0) {
            echo "Suma de datos es cero";
            return false;
        }
        // Crear el gráfico de barras
        $graph = new Graph(800, 600);
        $graph->SetScale("textlin");

        $barplot = new BarPlot($data);
        $barplot->SetFillColor(array('blue', 'green', 'red')); // Colores para las barras
        $barplot->SetWidth(0.6); // Ancho de las barras

        $graph->Add($barplot);
        $graph->title->Set("Usuarios por Edad");
        $graph->xaxis->SetTickLabels($labels); // Etiquetas para las barras en el eje x
        $graph->xaxis->title->Set("Grupos de Edad");
        $graph->yaxis->title->Set("Cantidad de Usuarios");

        $rutaImagen = $_SERVER['DOCUMENT_ROOT'] . '../public/img/graficoUsuariosPorEdad.png';

        if (file_exists($rutaImagen)) {
            unlink($rutaImagen);
        }

        $graph->Stroke($rutaImagen);
        return '..public/img/graficoUsuariosPorEdad.png';
    }
//-------------------------------------------------------------------------------------------------
    public function generarGraficoPreguntasTotales($preguntasTotales, $fechaInicio, $fechaFin)
    {
        // Crear el gráfico
        $graph = new Graph(800, 600);
        $graph->SetScale("textlin");

        // Obtener los valores para el gráfico
        $valores = array_map(function ($item) {
            return intval($item[0]); // Convertir a entero
        }, $preguntasTotales);

        $barplot = new BarPlot($valores);
        $barplot->SetFillColor("blue");

        $graph->Add($barplot);
        $graph->title->Set("Preguntas Totales por Fecha ($fechaInicio - $fechaFin)");
        $graph->yaxis->title->Set("Cantidad de Preguntas");

        $rutaImagen = $_SERVER['DOCUMENT_ROOT'] . '../public/img/graficoPreguntasTotales.png';

        // Eliminar archivo existente si existe
        if (file_exists($rutaImagen)) {
            unlink($rutaImagen);
        }

        $graph->Stroke($rutaImagen);
        return '..public/img/graficoPreguntasTotales.png';
    }
//-----------------------------------------------------------------------------------------
    public function generarGraficoTotalUsuarios($totalUsuarios, $fechaInicio, $fechaFin)
    {
        // solo los valores numéricos de $totalUsuarios
        $valores = array_map(function ($item) {
            return intval($item[0]); // Convertir a entero
        }, $totalUsuarios);

        // Crear el gráfico
        $graph = new Graph(800, 600);
        $graph->SetScale("textlin");

        $barplot = new BarPlot($valores);
        $barplot->SetFillColor("blue");

        $graph->Add($barplot);
        $graph->title->Set("Usuarios por Fecha ($fechaInicio - $fechaFin)");
        $graph->yaxis->title->Set("Cantidad de Usuarios");

        $rutaImagen = $_SERVER['DOCUMENT_ROOT'] . '../public/img/graficoTotalUsuarios.png';

        if (file_exists($rutaImagen)) {
            unlink($rutaImagen);
        }
        $graph->Stroke($rutaImagen);

        return '../public/img/graficoTotalUsuarios.png';
    }
    //--------------------------------------------------------------------------------------------*/
    public function generarGraficoUsuariosPorGenero($usuariosGeneroF, $usuariosGeneroM)
    {
        // Datos para el gráfico
        $labels = array('Femenino', 'Masculino');
        $data = array(
            intval($usuariosGeneroF[0]['usuariosGeneroF'] ?? 0),
            intval($usuariosGeneroM[0]['usuariosGeneroM'] ?? 0)
        );

        $colors = array('pink', 'blue');

        $graph = new Graph(800, 600);
        $graph->SetScale("textlin");

        // Crear el objeto BarPlot
        $barplot = new BarPlot($data);
        $barplot->SetFillColor($colors); // Establecer colores de las barras

        // Agregar las barras al gráfico
        $graph->Add($barplot);

        // Configurar título y etiquetas
        $graph->title->Set("Usuarios por Género");
        $graph->xaxis->SetTickLabels($labels); // Etiquetas del eje x
        $graph->xaxis->title->Set("Género");
        $graph->yaxis->title->Set("Cantidad de Usuarios");

        // Ruta donde se guardará la imagen del gráfico
        $rutaImagen = $_SERVER['DOCUMENT_ROOT'] . '/public/img/graficoGenero.png';

        if (file_exists($rutaImagen)) {
            unlink($rutaImagen);
        }
        $graph->Stroke($rutaImagen);

        return '/public/img/graficoGenero.png';
    }
//--------------------------------------------------------------
    public function generarGraficoPreguntasCreadas($preguntasCreadas, $fechaInicio, $fechaFin)
    {
        // Obtener solo los valores numéricos de $preguntasTotales
        $valores = array_map(function ($item) {
            return intval($item[0]); // Convertir a entero
        }, $preguntasCreadas);

        // Crear el gráfico
        $graph = new Graph(800, 600);
        $graph->SetScale("textlin");

        $barplot = new BarPlot($valores); // solo los valores numéricos
        $barplot->SetFillColor("blue");

        $graph->Add($barplot);
        $graph->title->Set("Preguntas Creadas por Fecha ($fechaInicio - $fechaFin)");
        $graph->yaxis->title->Set("Cantidad de Preguntas");

        $rutaImagen = $_SERVER['DOCUMENT_ROOT'] . '../public/img/graficoPreguntasCreadas.png';
        if (file_exists($rutaImagen)) {
            unlink($rutaImagen);
        }
        $graph->Stroke($rutaImagen);

        return '../public/img/graficoPreguntasCreadas.png';
    }
//--------------------------------------------------------------------
    public function generarGraficoPartidas($partidasClasicas, $partidasDuelo)
    {
        // Datos para el gráfico
        $labels = array('Partidas Clásicas', 'Partidas Duelo');
        $data = array(
            intval($partidasClasicas[0]['partidasClasicas'] ?? 0),
            intval($partidasDuelo[0]['partidasDuelo'] ?? 0)
        );
        // Crear el gráfico de barras agrupadas
        $graph = new Graph(800, 600);
        $graph->SetScale("textlin");

        $bplot = new BarPlot($data);
        $bplot->SetFillColor(array('blue', 'green'));
        $bplot->SetWidth(0.6); // Ancho de las barras

        $graph->Add($bplot);
        $graph->title->Set("Cantidad de Partidas Clásicas y Duelo");
        $graph->xaxis->SetTickLabels($labels); // Etiquetas para las barras
        $graph->xaxis->title->Set("Tipo de Partida");
        $graph->yaxis->title->Set("Cantidad de Partidas");

        $rutaImagen = $_SERVER['DOCUMENT_ROOT'] . '../public/img/graficoPartidas.png';

        if (file_exists($rutaImagen)) {
            unlink($rutaImagen);
        }
        $graph->Stroke($rutaImagen);

        return '../public/img/graficoPartidas.png';
    }
//------------------------------------------------------------------------
    public function generarGraficoPorcentajeRespUser($porcentajesUsuarios)
    {

        $labels = array();
        $data = array();
        $colors = array();

        // Procesar cada usuario y su porcentaje
        foreach ($porcentajesUsuarios as $usuario) {
            $labels[] = $usuario['nombre_usuario']; // Nombre del usuario
            $data[] = floatval($usuario['porcentaje_correctas']); // Porcentaje de respuestas correctas

            // Determinar el color según el porcentaje
            $porcentaje = floatval($usuario['porcentaje_correctas']);
            if ($porcentaje >= 70) {
                $colors[] = 'green';
            } elseif ($porcentaje >= 50) {
                $colors[] = 'yellow';
            } else {
                $colors[] = 'red';
            }
        }
        // Crear el gráfico de barras agrupadas
        $graph = new Graph(800, 600);
        $graph->SetScale("textlin");


        $bplot = new BarPlot($data);
        $bplot->SetWidth(0.6); // Ancho de las barras
        $bplot->SetFillColor($colors);


        $bplot->value->Show(); // Mostrar valores
        $bplot->value->SetFont(FF_ARIAL, FS_NORMAL, 12); // Fuente de los valores
        $bplot->value->SetColor('black'); // Color de los valores
        $bplot->value->SetFormat('%2.1f%%'); // Formato del valor (porcentaje)

        // Añadir el BarPlot al gráfico
        $graph->Add($bplot);

        // títulos y etiquetas
        $graph->title->Set("Porcentaje de Respuestas Correctas por Usuario");
        $graph->xaxis->SetTickLabels($labels); // Nombres de los usuarios en el eje x
        $graph->xaxis->title->Set("Usuarios");
        $graph->yaxis->title->Set("Porcentaje (%)");


        $rutaImagen = $_SERVER['DOCUMENT_ROOT'] . '../public/img/graficoPorcentajeRespUser.png';
        if (file_exists($rutaImagen)) {
            unlink($rutaImagen);
        }
        $graph->Stroke($rutaImagen);

        return '../public/img/graficoPorcentajeRespUser.png';
    }
    //--------------------------------------------------------------------------------------------
    public function generarGraficoUsuariosPorPais($datosUsuariosPorPais)
    {
        // datos necesarios para el gráfico
        $labels = [];
        $data = [];

        foreach ($datosUsuariosPorPais as $dato) {
            $labels[] = $dato['pais']; // Etiqueta (nombre del país)
            $data[] = intval($dato['contadorUsuarios']); // Cantidad de usuarios por país
        }

        // Verificar si no hay datos para mostrar
        if (count($labels) === 0 || count($data) === 0) {
            echo "No hay datos disponibles para mostrar el gráfico de usuarios por país.";
            return false;
        }

        $graph = new Graph(800, 600);
        $graph->SetScale("textlin");

        $barplot = new BarPlot($data);
        $barplot->SetFillColor('blue');

        $graph->Add($barplot);
        $graph->title->Set("Usuarios por País");
        $graph->xaxis->SetTickLabels($labels); // Etiquetas para las barras en el eje x
        $graph->xaxis->title->Set("Países");
        $graph->yaxis->title->Set("Cantidad de Usuarios");
        $rutaImagen = $_SERVER['DOCUMENT_ROOT'] . '../public/img/graficoUsuariosPorPais.png';

        if (file_exists($rutaImagen)) {
            unlink($rutaImagen);
        }
        $graph->Stroke($rutaImagen);
        return '../public/img/graficoUsuariosPorPais.png';
    }
    //---------------------------------------------------------------------------------------------
    public function generarPdf()
    {
        $data = [];

        if (isset($_POST['fechaInicio'], $_POST['fechaFin'])) {
            $fechaInicio = $_POST['fechaInicio'];
            $fechaFin = $_POST['fechaFin'];

            $data['preguntasTotales'] = $this->model->getPreguntasTotales($fechaInicio, $fechaFin);
            $data['cantidad_usuarios'] = $this->model->getTotalUsuarios($fechaInicio, $fechaFin);
            $data['contadorUsuariosPais'] = $this->model->getUsuariosPorPais($fechaInicio, $fechaFin);
            $data['usuariosMenores'] = $this->model->usuariosMenores($fechaInicio, $fechaFin);
            $data['usuariosAdultos'] = $this->model->usuariosAdultos($fechaInicio, $fechaFin);
            $data['usuariosMayores'] = $this->model->usuariosMayores($fechaInicio, $fechaFin);
            $data['nuevosUsuarios'] = $this->model->nuevosUsuarios($fechaInicio, $fechaFin);
            $data['usuariosGeneroF'] = $this->model->usuariosGeneroF($fechaInicio, $fechaFin);
            $data['usuariosGeneroM'] = $this->model->usuariosGeneroM($fechaInicio, $fechaFin);
            $data['preguntasCreadas'] = $this->model->preguntasCreadas($fechaInicio, $fechaFin);
            $data['partidasClasicas'] = $this->model->partidasClasicas($fechaInicio, $fechaFin);
            $data['partidasDuelo'] = $this->model->partidasDuelo($fechaInicio, $fechaFin);
            $data['porcentajeRespUser'] = $this->model->porcentajeRespUser($fechaInicio, $fechaFin);
        }

        $vista = __DIR__ . '/../view/vistaPdf.mustache';

        if (file_exists($vista)) {

            $mustache = new Mustache_Engine;
            $html = file_get_contents($vista);
            $rendered = $mustache->render($html, $data);

            $dompdf = new Dompdf();
            $dompdf->loadHtml($rendered);
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();

            $dompdf->stream("reporte_administrador.pdf", [
                "Attachment" => true
            ]);
        } else {
            echo "Error: No se encontró el archivo de la vista.";
        }
    }
//-----------------------------------------------------------------------------------------------
    public function mostrarGraficos(){
        $this->presenter->render('reportesGraficos');
    }
}
?>
