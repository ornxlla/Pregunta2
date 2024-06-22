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

    public function getPanel(){
        $this->presenter->render('homeEditor');
    }


    public function listadoGeneralPreguntas() {
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

        header('Location: /');
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

            // Aprobar la pregunta (eliminar de preguntas_reportadas si existe)
            $this->model->aprobarPregunta($idPregunta);

            // Dar de baja la pregunta (eliminar de preguntas_listado)
            $this->model->darDeBajaPregunta($idPregunta);

            // Redireccionar después de realizar las operaciones
            header("Location: /Pregunta/getPreguntasReportadas");
            exit;

    }
    /*   public function getPreguntasSugeridas()
       {

           $data['preguntas_sugeridas'] = $this->model->obtenerPreguntasSugeridas();
           $this->presenter->render('PreguntasSugeridasView', $data);
       }

   // paso 2 que apruebe preguntas sugeridas



       public function aprobarPreguntaSugerida() {
           var_dump($_POST);
           $idPreguntaSugerida = intval($_POST['id_pregunta_sugerida'] ?? 0);
           $pregunta = $_POST['pregunta'] ?? '';
           $respuestaCorrecta = $_POST['respuesta_correcta'] ?? '';
           $primeraRespuestaIncorrecta = $_POST['primera_respuesta_incorrecta'] ?? '';
           $segundaRespuestaIncorrecta = $_POST['segunda_respuesta_incorrecta'] ?? '';
           $terceraRespuestaIncorrecta = $_POST['tercera_respuesta_incorrecta'] ?? '';
           $idTematica = intval($_POST['id_tematica'] ?? 0);
           $idDificultad = intval($_POST['id_dificultad'] ?? 0);

           if ($idPreguntaSugerida === 0) {
               echo "Error: ID de pregunta sugerida no especificado o no válido.";
               return;
           }

           try {
               // Aprobar la pregunta sugerida
               $this->model->aprobarPreguntaSugerida($idPreguntaSugerida);

               // Agregar la pregunta a preguntas_listado y las respuestas a respuesta_listado
               $idNuevaPregunta = $this->model->agregarPregunta($pregunta, $idTematica, $idDificultad);

               // Obtener las respuestas desde las variables POST
               $respuestas = [
                   ['texto' => $respuestaCorrecta, 'correcta' => 1],
                   ['texto' => $primeraRespuestaIncorrecta, 'correcta' => 0],
                   ['texto' => $segundaRespuestaIncorrecta, 'correcta' => 0],
                   ['texto' => $terceraRespuestaIncorrecta, 'correcta' => 0]
               ];

               // Agregar las respuestas a respuesta_listado
               $this->model->agregarRespuestas($idNuevaPregunta, $respuestas);

               // Redirigir después de completar la operación
               header("Location: /Pregunta/getPreguntasSugeridas");
               exit;
           } catch (Exception $e) {
               echo "Error al procesar la pregunta sugerida: " . $e->getMessage();
           }
       }



       //

      ///   ******************PreguntaController**************************************************
      ///    EL EDITOR puede revisar las preguntas reportadas, para aprobar o dar de baja
      ///    paso 1







      // consigna: Debe existir un tipo de usuario editor, que le permite dar de alta, baja y modificar las preguntas:

   // 1ER PASO: CREAR PREGUNTA Y SE AGREGA AL LISTADO -- este metodo ok


     /* public function mostrarFormularioCrearPregunta() {
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
     }*/
















}