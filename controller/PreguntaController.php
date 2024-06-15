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


    public function listadoGeneralPreguntas1()
    {
        $preguntasGenerales = $this->model->obtenerPreguntasGenerales();
        $this->presenter->render('ListadoGeneralPreguntasView', ['preguntas' => $preguntasGenerales]);
    }












}