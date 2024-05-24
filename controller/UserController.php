<?php

namespace controller;

class UserController
{

    private $model;
    private $presenter;

    public function __construct($model, $presenter)
    {
        $this->model = $model;
        $this->presenter = $presenter;
    }

    public function show()
    {
        $this->presenter->render("iniciarSesion");
    }

    //NO TERMINADO!!!! empece con usuario pero es con mail!!!1

  /*  public function procesar()
    {
        if (isset($_POST["enviar"])) {
            if (isset($_POST["nombre_usuario"]) && isset($_POST["contrasenia"]) && !empty($_POST["nombre_usuario"]) && !empty($_POST["contrasenia"])) {
                $nombre_usuario = $_POST["nombreUser"];
                $contrasenia = $_POST["pw"];
                $resultados = $this->UserModel->validarCredenciales($nombre_usuario, $contrasenia);

                if (!empty($resultados)) {
                    $credencialesValidas = false;
                    foreach ($resultados as $fila) {
                        if ($fila["nombre_usuario"] === $nombre_usuario && $fila["contrasenia"] === $pw) {
                            // Las credenciales son válidas
                            $credencialesValidas = true;
                            // Iniciar sesión
                            $sesionIniciada = $this->iniciarSesion($nombre_usuario, $contrasenia);
                            if ($sesionIniciada === PHP_SESSION_ACTIVE) {
                                // Redirigir según el rol
                                if ($fila["rol"] === 'c') {
                                    $data["sesionOk"] = "Bienvenido, " . $_SESSION["nombre_usuario"];
                                    $data["pokemon"] = $this->iniciarSesionModel->getPokemons();
                                    $this->renderer->render("homeLogueadoUsuarioComun", $data);

                                } elseif ($fila["rol"] === 'a') {
                                    $data["sesionOk"] = "Bienvenido administrador, " . $_SESSION["nombre_usuario"];
                                    $data["preguntados"] = $this->iniciarSesionModel->getPokemons();
                                    $this->renderer->render("homeLogueadoAdmin", $data);
                                }
                            } else {
                                $data["error"] = "Error al iniciar sesión";
                            }

                        }
                    }
                    // Si las credenciales no coinciden con ningún registro en la base de datos
                    if (!$credencialesValidas) {
                        $data["error"] = "Las credenciales son incorrectas";
                        $this->renderer->render("iniciarSesion", $data);
                    }
                } else {
                    // Si no se encontraron resultados en la base de datos
                    $data["error"] = "Las credenciales son incorrectas";
                    $this->renderer->render("iniciarSesion", $data);
                }
            } else {
                // Si los campos del formulario están vacíos
                $data["error"] = "Los campos no pueden estar vacíos";
                $this->renderer->render("iniciarSesion", $data);
            }
        }
    }

*/

    public function getUsuario(){
        $data["preguntados"] = $this->model->getUsuarios();
        $this->presenter->render("pokemon", $data);
    }
    public function iniciarSesion($mail, $contrasenia)
    {

        $_SESSION["mail"] = $mail;
        $_SESSION["contrasenia"] = $contrasenia;
        return session_status();
    }

  /*  public function cerrarSesion()
    {
        session_destroy();
        Redirect::root();
    }*/
}