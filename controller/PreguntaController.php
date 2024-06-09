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
        // Llama al método del modelo para obtener las preguntas reportadas
        $preguntasReportadas = $this->model->getPreguntasReportadas();

        // Devuelve las preguntas reportadas al presentador para su renderizado
        return $preguntasReportadas;
    }

    public function getPreguntasSugeridas()
    {
        // Llamar al método del modelo para obtener las preguntas sugeridas
        $preguntasSugeridas = $this->model->getPreguntasSugeridas();

        // Devuelve las preguntas sugeridas al presentador para su renderizado
        return $preguntasSugeridas;
    }

    public function revisarPreguntasReportadas()
    {
        $preguntasReportadas = $this->getPreguntasReportadas();

        $this->presenter->render('revisarPreguntasReportadas', ['preguntasReportadas' => $preguntasReportadas]);
    }

    public function agregarPregunta()
    {
        // Verifica si se está accediendo al formulario de agregado de pregunta
        if ($_SERVER["REQUEST_METHOD"] === "GET") {
            // Muestra el formulario para agregar una pregunta
            $this->presenter->render('formularioAgregarPregunta');
            return;
        }

        // Obtiene los datos del formulario
        $pregunta = $_POST["pregunta"];
        $respuesta = $_POST["respuesta"];
        $tematica = $_POST["tematica"];

        $this->model->agregarPregunta($pregunta, $respuesta, $tematica);

        // Redirige al usuario a la página de inicio
        header("Location: index.php");
        exit();
    }

    public function eliminarPregunta()
    {
        // Verifica si se está accediendo a la página de confirmación para eliminar una pregunta
        if ($_SERVER["REQUEST_METHOD"] === "GET") {
            $this->presenter->render('confirmarEliminarPregunta', ['preguntaID' => $_GET['preguntaID']]);
            return;
        }

        // Elimina la pregunta
        $preguntaID = $_POST["preguntaID"];
        $this->model->eliminarPregunta($preguntaID);

        // Redirige al usuario a la página de inicio
        header("Location: index.php");
        exit();
    }

    public function buscarPreguntas()
    {
        // Verifica si se está accediendo al formulario de búsqueda de preguntas
        if ($_SERVER["REQUEST_METHOD"] === "GET") {
            $this->presenter->render('formularioBuscarPreguntas');
            return;
        }

        // Procesa la búsqueda de preguntas
        $terminoBusqueda = $_POST["termino_busqueda"];
        $preguntasEncontradas = $this->model->buscarPreguntas($terminoBusqueda);

        $data = [
            'preguntasEncontradas' => $preguntasEncontradas,
            'terminoBusqueda' => $terminoBusqueda
        ];

        $this->presenter->render('listaPreguntas', $data);
    }

    public function procesarModificacion()
    {
        // Verifica si se está procesando la modificación de una pregunta
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            // Obtiene los datos del formulario
            $idPregunta = $_POST["idPregunta"];
            $pregunta = $_POST["pregunta"];
            $respuesta = $_POST["respuesta"];
            $tematica = $_POST["tematica"];

            $this->model->modificarPregunta($idPregunta, $pregunta, $respuesta, $tematica);

            // Redirige al usuario a la página de inicio
            header("Location: index.php");
            exit();
        }
    }




}