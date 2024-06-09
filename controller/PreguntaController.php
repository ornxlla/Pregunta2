<?php

class PreguntaController
{
    private $model;
    private $presenter;

    public function __construct($presenter, $model)
    {
        $this->presenter = $presenter;
        $this->model = $model;
    }

    public function getPreguntasReportadas()
    {
        // llama al método del modelo para obtener las preguntas reportadas
        $preguntasReportadas = $this->model->getPreguntasReportadas();

        // devuelve las preguntas reportadas al presentador para su renderizado
        return $preguntasReportadas;
    }

    public function getPreguntasSugeridas()
    {
        // llamar al método del modelo para obtener las preguntas sugeridas
        $preguntasSugeridas = $this->model->getPreguntasSugeridas();

        // devuelve las preguntas sugeridas al presentador para su renderizado
        return $preguntasSugeridas;
    }

    public function revisarPreguntasReportadas()
    {
        $preguntasReportadas = $this->getPreguntasReportadas();

        $this->presenter->render('revisarPreguntasReportadas', ['preguntasReportadas' => $preguntasReportadas]);
    }

    public function agregarPregunta()
    {
        // verifica si envio de datos del formulario
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // obtiene los datos
            $pregunta = $_POST["pregunta"];
            $respuesta = $_POST["respuesta"];
            $tematica = $_POST["tematica"];


            $this->model->agregarPregunta($pregunta, $respuesta, $tematica);

            // Redirigir al usuario a la página de inicio
            header("Location: index.php");
            exit();
        } else {
            // Si no se envian datos por POST muestra el formulario para agregar una pregunta
            $this->presenter->render('formularioAgregarPregunta');
        }
    }

    public function eliminarPregunta()
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {

            $preguntaID = $_POST["preguntaID"];

            $this->model->eliminarPregunta($preguntaID);

            header("Location: index.php");
            exit();
        } else {

            $this->presenter->render('confirmarEliminarPregunta', ['preguntaID' => $_GET['preguntaID']]);
        }
    }


    public function buscarPreguntas()
    {

        if ($_SERVER["REQUEST_METHOD"] == "POST") {

            $terminoBusqueda = $_POST["termino_busqueda"];


            $preguntasEncontradas = $this->model->buscarPreguntas($terminoBusqueda);

            $data = [
                'preguntasEncontradas' => $preguntasEncontradas,
                'terminoBusqueda' => $terminoBusqueda
            ];

            $this->presenter->render('listaPreguntas', $data);
        } else {
            // Si no se han enviado datos por POST, mostrar el formulario de búsqueda
            $this->presenter->render('formularioBuscarPreguntas');
        }
    }


    public function procesarModificacion()
    {

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Obtener los datos del formulario
            $idPregunta = $_POST["idPregunta"];
            $pregunta = $_POST["pregunta"];
            $respuesta = $_POST["respuesta"];
            $tematica = $_POST["tematica"];

            $this->model->modificarPregunta($idPregunta, $pregunta, $respuesta, $tematica);

            header("Location: index.php");
            exit();
        }
    }












}