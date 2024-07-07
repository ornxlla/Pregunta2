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

    public function get()
    {
        $this->presenter->render('homeEditor');
    }


    public function listadoGeneralPreguntas()
    {
        $data['preguntas'] = $this->model->obtenerPreguntasGenerales();
        $this->presenter->render('ListadoGeneralPreguntasView', $data);
    }


    public function reportarPregunta()
    {
        $idPregunta = $_POST['id_pregunta'] ?? null;

        if ($idPregunta === null) {
            throw new \Exception('ID de pregunta no válido.');
        }

        // Llamar al método del modelo para reportar la pregunta
        $reporteExitoso = $this->model->reportarPregunta($idPregunta);

        Redirect::root();
        exit;
    }

    public function getPreguntasReportadas()
    {
        $data['preguntas_reportadas'] = $this->model->obtenerPreguntasReportadas();
        $this->presenter->render('PreguntasReportadasView', $data);
    }

    public function aprobarPregunta()
    {
        $idPregunta = $_POST['id_pregunta'] ?? null;

        if (!$idPregunta) {
            echo "Error: ID de pregunta no especificado.";
            return;
        }

        $this->model->aprobarPregunta($idPregunta);
        Redirect::to("/Pregunta/getPreguntasReportadas");
    }


    // paso 3 dar de baja preguntada reportada ok funciona

    public function darDeBajaPregunta()
    {
        $idPregunta = $_POST['id_pregunta'] ?? null;

        if (!$idPregunta) {
            echo "Error: ID de pregunta no especificado.";
            return;
        }

        // Aprobar la pregunta (eliminar de preguntas_reportadas si existe)
        $this->model->aprobarPregunta($idPregunta);

        // Dar de baja la pregunta (eliminar de preguntas_listado)
        $this->model->darDeBajaPregunta($idPregunta);

        // Redireccionar después de realizar las operaciones
        Redirect::to("Location: /Pregunta/getPreguntasReportadas");
    }

    public function mostrarFormularioCrearPreguntaSugerida()
    {
        $dificultades = $this->model->obtenerDificultades();
        $tematicas = $this->model->obtenerTematicas();

        $this->presenter->render('CrearPreguntaSugeridaView', ['dificultades' => $dificultades, 'tematicas' => $tematicas]);
    }

    public function crearPreguntaSugerida()
    {
        $pregunta_texto = $_POST['pregunta_texto'];
        $id_tematica = $_POST['id_tematica'];
        $respuestas = $_POST['respuestas'];
        $respuesta_correcta_index = $_POST['respuesta_correcta'];

        // Validar que haya al menos una respuesta y que se haya seleccionado la respuesta correcta
        if ($pregunta_texto && $id_tematica && count($respuestas) > 1 && $respuesta_correcta_index !== null) {
            $idPregunta = $this->model->insertarPreguntaSugerida($pregunta_texto, $id_tematica);

            if ($idPregunta) {
                foreach ($respuestas as $index => $respuesta_texto) {
                    $es_correcta = ($index == $respuesta_correcta_index) ? 1 : 0;
                    if (!$this->model->insertarRespuesta($idPregunta, $respuesta_texto, $es_correcta)) {
                        echo "Error: No se pudo insertar la respuesta.";
                        return;
                    }
                }

                Redirect::root();
            } else {
                echo "Error: No se pudo insertar la pregunta.";
            }
        } else {
            echo "Error: Por favor, complete todos los campos y asegúrese de marcar una respuesta correcta.";
        }
    }

    public function getPreguntasSugeridas()
    {
        $data['preguntas_sugeridas'] = $this->model->listarPreguntasSugeridas();

        // Si no hay preguntas sugeridas, se asigna un array vacío
        if (empty($data['preguntas_sugeridas'])) {
            $data['preguntas_sugeridas'] = [];
        }

        $this->presenter->render('PreguntasSugeridasView', $data);
    }

    public function aprobarPreguntaSugerida()
    {
        $idPregunta = $_POST['id_pregunta'] ?? null;

        if (!$idPregunta) {
            echo "Error: ID de pregunta no especificado.";
            return;
        }

        $this->model->aprobarPreguntaSugerida($idPregunta);

        // Redireccionar después de realizar las operaciones
        Redirect::to("/Pregunta/getPreguntasSugeridas");
    }

    public function mostrarFormularioModificarPreguntaYRespuesta()
    {
        if (isset($_POST['id_pregunta'])) {
            $idPregunta = $_POST['id_pregunta'];

            $pregunta = $this->model->obtenerPreguntaPorId($idPregunta);
            $respuestas = $this->model->obtenerRespuestasPorIdPregunta($idPregunta);
            $tematicas = $this->model->obtenerTematicas();

            if ($pregunta && $respuestas) {
                foreach ($tematicas as &$tematica) {
                    if ($tematica['id_tematica'] == $pregunta['id_tematica']) {
                        $tematica['is_selected'] = true;
                    } else {
                        $tematica['is_selected'] = false;
                    }
                }


                $this->presenter->render('ModificarPreguntaYRespuestaView', [
                    'pregunta' => $pregunta,
                    'respuestas' => $respuestas,
                    'tematicas' => $tematicas
                ]);
            } else {
                echo "Error: No se encontró la pregunta o las respuestas.";
            }
        } else {
            echo "Error: ID de la pregunta no especificado.";
        }
    }


    public function actualizarPreguntaYRespuestas()
    {
        if (
            isset($_POST['id_pregunta']) &&
            isset($_POST['pregunta_texto']) &&
            isset($_POST['id_tematica']) &&
            isset($_POST['respuesta_correcta']) &&
            isset($_POST['respuesta']) &&
            isset($_POST['id_respuesta'])
        ) {
            $idPregunta = $_POST['id_pregunta'];
            $preguntaTexto = $_POST['pregunta_texto'];
            $idTematica = $_POST['id_tematica'];
            $respuestaCorrecta = $_POST['respuesta_correcta'];
            $respuestas = $_POST['respuesta'];
            $idRespuestas = $_POST['id_respuesta'];

            // Actualizar la pregunta
            $resultadoPregunta = $this->model->actualizarPregunta($idPregunta, $preguntaTexto, $idTematica);

            // Verificar si la pregunta se actualizó correctamente
            if ($resultadoPregunta) {
                // Iterar sobre las respuestas y actualizar cada una
                foreach ($respuestas as $index => $respuesta) {
                    $idRespuesta = $idRespuestas[$index];
                    $respuestaTexto = $respuesta;
                    $esCorrecta = ($idRespuesta == $respuestaCorrecta) ? 1 : 0;

                    $resultadoRespuesta = $this->model->actualizarRespuesta($idRespuesta, $respuestaTexto, $esCorrecta);

                    if (!$resultadoRespuesta) {
                        echo "Error al actualizar la respuesta con ID: $idRespuesta";
                        return;
                    }
                }
                Redirect::to("/Pregunta/listadoGeneralPreguntas");
            } else {
                echo "Error al actualizar la pregunta.";
            }
        } else {
            echo "Error: Faltan parámetros para actualizar la pregunta y respuestas.";
        }
    }

    public function eliminarPregunta()
    {
        $idPregunta = $_POST['id_pregunta'] ?? null;

        if (!$idPregunta) {
            echo "Error: ID de pregunta no especificado.";
            return;
        }


        // Dar de baja la pregunta (eliminar de preguntas_listado)
        $this->model->darDeBajaPregunta($idPregunta);

        // Redireccionar después de realizar las operaciones
        Redirect::to("/Pregunta/listadoGeneralPreguntas");
    }

}
