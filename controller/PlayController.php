<?php

class PlayController
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
        $this->presenter->render("playView");
    }

    public function getPartida()
    {
        if (isset($_GET['nombre_usuario'])) {
            $username = $_GET['nombre_usuario'];
            $usuario = $this->model->getUsuarioLogueado($username);
            if ($usuario) {
                $dificultad = 1;
                $pregunta = $this->model->getPregunta($dificultad);
                if ($pregunta) {
                    $this->model->sumarApariciones($pregunta['id_pregunta']);
                    $respuestas = $this->model->getRespuesta($pregunta['id_pregunta']);
                    $this->presenter->render("playView", [
                        'usuario' => $usuario,
                        'preguntas' => $pregunta,
                        'respuestas' => $respuestas
                    ]);
                } else {
                    echo "No hay más preguntas para tu nivel.";
                }
            } else {
                echo "Usuario no encontrado.";
            }
        }
    }

    public function validarRespuesta()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_POST['id_pregunta']) && isset($_POST['id_respuesta'])) {
                $id_pregunta = $_POST['id_pregunta'];
                $id_respuesta = $_POST['id_respuesta'];

                $respuesta_correcta = $this->model->validarRespuesta($id_respuesta);

                if ($respuesta_correcta) {
                    $this->model->contadorDeRespuestasCorrectas($id_pregunta);
                    echo "<p>¡Respuesta correcta!</p>";
                } else {
                    $this->model->contadorDeRespuestasIncorrectas($id_pregunta);
                    echo "<p>¡Lo siento! Has perdido. La partida ha terminado.</p>";
                }
            } else {
                echo "No se recibieron datos de la pregunta y la respuesta.";
            }
        }
    }

}