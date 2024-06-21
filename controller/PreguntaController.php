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


    ///   ******************PreguntaController**************************************************
    ///    EL EDITOR puede revisar las preguntas reportadas, para aprobar o dar de baja
    ///    paso 1


    public function getPreguntasReportadas()
    {
        $preguntasReportadas = $this->model->obtenerPreguntasReportadas();
        $this->presenter->render('PreguntasReportadasView', ['preguntas' => $preguntasReportadas]);
    }

    public function getPanel(){
        $this->presenter->render('homeEditor');
    }

    // paso 2 de aprobar preguntas ok funciona

    public function aprobarPregunta()
    {

        $idPregunta = $_POST['id_pregunta'] ?? null;

        if (!$idPregunta) {
            echo "Error: ID de pregunta no especificado.";
            return;
        }
        $this->model->aprobarPregunta($idPregunta);
        header("Location: /Pregunta/getPreguntasReportadas");
        exit;
    }


    // paso 3 dar de baja preguntada reportada ok funciona

    public function darDeBajaPregunta()
    {
        $idPregunta = $_POST['id_pregunta'] ?? null;

        if (!$idPregunta) {
            echo "Error: ID de pregunta no especificado.";
            return;
        }
        $this->model->darDeBajaPregunta($idPregunta);
        header("Location: /Pregunta/getPreguntasReportadas");
        exit;
    }


    // el editor puede aprobar las preguntas sugeridas por usuarios
    // paso 1
    public function getPreguntasSugeridas()
    {
        $preguntasSugeridas = $this->model->obtenerPreguntasSugeridas();
        $this->presenter->render('PreguntasSugeridasView', ['preguntas' => $preguntasSugeridas]);
    }

// paso 2 que apruebe preguntas sugeridas

    public function aprobarPreguntaSugerida()
    {
        $idPregunta = $_POST['id_pregunta'] ?? null;

        if (!$idPregunta) {
            echo "Error: ID de pregunta no especificado.";
            return;
        }

        $this->model->aprobarPreguntaSugerida($idPregunta);
        header("Location: /Pregunta/getPreguntasSugeridas");
        exit;
    }

// el editor podrá ver los cambios de sus funciones en el listado general de preguntas, es decir que trae las preguntas que no son sugeridas ni reportadas  (porque cuando las aceptó dichos campos pasaron a estar en false)

    public function listadoGeneralPreguntas()
    {
        $preguntasGenerales = $this->model->obtenerPreguntasGenerales();
        $this->presenter->render('listadoGeneralPreguntasView', ['preguntas' => $preguntasGenerales]);
    }


    // consigna: Debe existir un tipo de usuario editor, que le permite dar de alta, baja y modificar las preguntas:

// 1ER PASO: CREAR PREGUNTA Y SE AGREGA AL LISTADO -- este metodo ok


    public function mostrarFormularioCrearPregunta() {
        $dificultades = $this->model->obtenerDificultades();
        $tematicas = $this->model->obtenerTematicas();

        $this->presenter->render('CrearPreguntaView', ['dificultades' => $dificultades,
            'tematicas' => $tematicas
        ]);
    }

    // ok creamos la pregunta y la misma se ve reflejada en el listado como incluida

    public function crearPregunta()
    {

        $pregunta_texto = $_POST['pregunta_texto'];
        $id_tematica = $_POST['id_tematica'];
        $id_dificultad = $_POST['id_dificultad'];

        $insertado = $this->model->insertarPregunta($pregunta_texto, $id_tematica, $id_dificultad);


        header('Location: /Pregunta/homeEditor');
        exit();

    }

// eliminar pregunta del listado

    public function eliminarPregunta()
    {
        $idPregunta = $_POST['id_pregunta'] ?? null;

        if (!$idPregunta) {
            echo "Error: ID de pregunta no especificado.";
            return;
        }

        $this->model->eliminarPregunta($idPregunta);

        header("Location: /Pregunta/listadoGeneralPreguntas");
        exit();
    }

// 19/6 crear pregunta sugerida  ok ******

    public function mostrarFormularioCrearPreguntaSugerida() {
        $dificultades = $this->model->obtenerDificultades();
        $tematicas = $this->model->obtenerTematicas();

        $this->presenter->render('CrearPreguntaSugeridaView', ['dificultades' => $dificultades, 'tematicas' => $tematicas]);
    }

    public function crearPreguntaSugerida()
    {
        $pregunta_texto = $_POST['pregunta_texto'];
        $id_tematica = $_POST['id_tematica'];
        $id_dificultad = $_POST['id_dificultad'];
        $respuestas = $_POST['respuestas'];
        $respuesta_correcta = $_POST['respuesta_correcta'];

        if ($pregunta_texto && $id_tematica && $id_dificultad && count($respuestas) === 4 && $respuesta_correcta !== null) {
            $idPregunta = $this->model->insertarPreguntaSugerida($pregunta_texto, $id_dificultad, $id_tematica);

            if ($idPregunta) {
                foreach ($respuestas as $index => $respuesta_texto) {
                    $es_correcta = ($index == $respuesta_correcta) ? 1 : 0;
                    $this->model->insertarRespuesta($idPregunta, $respuesta_texto, $es_correcta);
                }

                header('Location: /Pregunta/listadoGeneralPreguntas');
                exit();
            } else {
                echo "Error: No se pudo insertar la pregunta.";
            }
        } else {
            echo "Error: Por favor, complete todos los campos y asegúrese de marcar una respuesta correcta.";
        }
    }


// mostrar modificar pregunta y respuesta ok


    public function mostrarFormularioModificarPreguntaYRespuesta()
    {

        if (isset($_POST['id_pregunta'])) {
            $idPregunta = $_POST['id_pregunta'];

            $pregunta = $this->model->obtenerPreguntaPorId($idPregunta);
            $respuestas = $this->model->obtenerRespuestasPorIdPregunta($idPregunta);
            $dificultades = $this->model->obtenerDificultades();
            $tematicas = $this->model->obtenerTematicas();

            if ($pregunta && $respuestas) {
                foreach ($tematicas as &$tematica) {
                    if ($tematica['id_tematica'] == $pregunta['id_tematica']) {
                        $tematica['selected'] = true;
                    }
                }
                foreach ($dificultades as &$dificultad) {
                    if ($dificultad['id'] == $pregunta['id_dificultad']) {
                        $dificultad['selected'] = true;
                    }
                }

                $this->presenter->render('ModificarPreguntaYRespuestaView', [
                    'pregunta' => $pregunta,
                    'respuestas' => $respuestas,
                    'dificultades' => $dificultades,
                    'tematicas' => $tematicas
                ]);
            } else {
                echo "Error: No se encontró la pregunta o las respuestas.";
            }
        } else {
            echo "Error: ID de la pregunta no especificado.";
        }
    }



//metodo a revisar


    public function actualizarPreguntaYRespuestas()
    {
        if (
            isset($_POST['id_pregunta']) &&
            isset($_POST['pregunta_texto']) &&
            isset($_POST['id_tematica']) &&
            isset($_POST['id_dificultad']) &&
            isset($_POST['respuesta_correcta']) &&
            isset($_POST['respuesta']) &&
            isset($_POST['id_respuesta'])
        ) {
            $idPregunta = $_POST['id_pregunta'];
            $preguntaTexto = $_POST['pregunta_texto'];
            $idTematica = $_POST['id_tematica'];
            $idDificultad = $_POST['id_dificultad'];
            $respuestaCorrecta = $_POST['respuesta_correcta'];
            $respuestas = $_POST['respuesta'];
            $idRespuestas = $_POST['id_respuesta'];

            // Actualizar la pregunta
            $resultadoPregunta = $this->model->actualizarPregunta($idPregunta, $preguntaTexto, $idTematica, $idDificultad);

            // Verificar si la pregunta se actualizó correctamente
            if ($resultadoPregunta) {
                // Iterar sobre las respuestas y actualizar cada una
                foreach ($respuestas as $index => $respuesta) {
                    $idRespuesta = $idRespuestas[$index];
                    $respuestaTexto = $respuesta;
                    $esCorrecta = ($index == $respuestaCorrecta) ? 1 : 0;

                    $resultadoRespuesta = $this->model->actualizarRespuesta($idRespuesta, $respuestaTexto, $esCorrecta);

                    if (!$resultadoRespuesta) {
                        echo "Error al actualizar la respuesta con ID: $idRespuesta";
                        return;
                    }
                }

                echo "Pregunta y respuestas actualizadas correctamente.";
            } else {
                echo "Error al actualizar la pregunta.";
            }
        } else {
            echo "Error: Faltan parámetros para actualizar la pregunta y respuestas.";
        }
    }


















}