<?php
include_once("third-party/phpqrcode/qrlib.php");
class PerfilUsuarioController
{
    private $presenter;
    private $model;

    public function __construct($presenter, $model)
    {
        $this->presenter = $presenter;
        $this->model = $model;
    }

    public function get()
    {
        if(isset($_SESSION["Session_id"])) {
            $data["datos_usuario"] = $this->obtenerDatosUsuario($_SESSION["Session_id"]);
            if($data["datos_usuario"] == false){
                Redirect::root();   //USUARIO NO EXISTE -> Llevar a HOME
            }
            $data["datos_usuario"]["boton-editar"] = "TRUE";
            $this->presenter->render("perfil-usuario", $data);
        }else{
            Redirect::root();   //Error -> Llevar a HOME
        }
    }

    public function buscarUsuario(){
        if(isset($_GET["id"]) && isset($_SESSION["Session_id"])){
            $id_usuario = $_GET["id"];
            $data['partidas'] = $this->model->getPartidas($id_usuario);
            $data["datos_usuario"] = $this->obtenerDatosUsuario($id_usuario);
            if($data["datos_usuario"] == false){
                Redirect::root();   //USUARIO NO EXISTE! Llevarlo a HOME
            }
            $this->presenter->render("perfil-usuario", $data);
        }else{
            Redirect::root();   //Error -> Llevar a HOME
        }
    }

    public function modificarUsuario($errors = null)
    {
        if (isset($_SESSION["Session_id"])) {
            $data["datos_usuario"] = $this->obtenerDatosUsuario($_SESSION["Session_id"]);
            if($data["datos_usuario"] == false){
                Redirect::root();   //USUARIO NO EXISTE! Llevarlo a HOME
            }
            $data["error"] = $errors;
            $this->presenter->render("modificarUsuario", $data);
        } else {
            Redirect::root();    //Error -> Llevar a HOME
        }
    }

    public function obtenerDatosUsuario($id){
        $data = $this->model->getDataUsuario($id);
        if(!empty($data)){
            $data['partidas'] = $this->model->getPartidas($id);
            $data["genero"] = $this->obtenerGenero($data["genero"]);
            $qrLink = "http://localhost/PerfilUsuario/buscarUsuario?id=" . $id;
            $carpeta_destino = "public/img/profile_qrs/";

            if (!file_exists($carpeta_destino)) {
                mkdir($carpeta_destino, 0777, true);
            }
            $qrImg = $carpeta_destino . $id . "_qr.png";

            QRcode::png($qrLink,$qrImg,QR_ECLEVEL_L,8);
            $qr = QRcode::png($qrLink, $qrImg , QR_ECLEVEL_L, 4);
            return $data;
        }
        return false;
    }

    public function procesarModificacion()
    {
        if (isset($_SESSION["Session_id"])) {
            $data = [];
            $error = null;
            if (isset($_POST["enviar"])) {
                $required_fields = ["nombre", "year", "genero", "email", "latitud", "longitud"];
                $valid = true;
                foreach ($required_fields as $field) {
                    if (empty($_POST[$field])) {
                        $data["error"] = "Los campos no pueden estar vacíos";
                        $valid = false;
                        break;
                    }
                }

                if ($valid) {
                    $data["id_usuario"] = $_SESSION["Session_id"];
                    $data["nombre"] = ucfirst($_POST["nombre"]);
                    $data["nacimiento"] = $_POST["year"];
                    $data["genero"] = $this->obtenerLetraGenero($_POST["genero"]);
                    $data["pais"] = $_POST["pais"];
                    $data["ciudad"] = $_POST["ciudad"];
                    $data["latitud"] = $_POST["latitud"];
                    $data["longitud"] =  $_POST["longitud"];

                    $data["correo"] = $_POST["email"];
                    $data["username"] =  $_POST["username"];
                    $data["password"] = $_POST["password"];
                    $data["nombreImagen"] = isset($_FILES["imagen"]["name"]) ? $_FILES["imagen"]["name"] : null;

                    if ($data["nombreImagen"]) {
                        $upload_result = $this->subirArchivo();
                        if ($upload_result !== true) {
                            $error = $upload_result;
                            $this->modificarUsuario($error);
                            return;
                        }
                    }
                    //SOLO EJECUTA SI EL USUARIO INGRESA ALGO EN EL APARTADO DE CONTRASEÑA. significa que debe cambiarse la contraseña
                    if(!empty($data["password"])){
                        $resultadoPassword = $this->model->updatePassword($data["id_usuario"],$data["password"]);
                        if(!$resultadoPassword){
                            $error = "La contraseña no pudo ser actualizada.";
                            $this->modificarUsuario($error);
                            return;
                        }
                    }

                    //SOLO SE EJECUTA SI EL USUARIO CAMBIO EL NOMBRE DE USUARIO
                    if($data["username"] != $_SESSION["Session_username"]){
                        $resultadoUsername = $this->model->updateUsername($data["id_usuario"],$data["username"]);
                        if($resultadoUsername){
                            $_SESSION["Session_username"] = $data["username"];
                        }else{
                            $error = "El nombre de usuario no pudo ser actualizado.";
                            $this->modificarUsuario($error);
                            return;
                        }
                    }

                    //SOLO SE EJECUTA SI EL USUARIO CAMBIO EL CORREO
                    if($data["correo"] != $_SESSION["Session_correo"]){
                        $resultadoCorreo = $this->model->updateCorreo($data["id_usuario"], $data["correo"]);
                        if($resultadoCorreo){
                            $_SESSION["Session_correo"] = $data["correo"];
                        }else{
                            $error = "El correo no pudo ser actualizado.";
                            $this->modificarUsuario($error);
                            return;
                        }
                    }

                    //SOLO SE EJECUTA SI EL USUARIO CAMBIO SU CORREO

                    $resultadoDatos = $this->model->updateDatosUsuario($data["id_usuario"], $data["nombre"], $data["nacimiento"], $data["genero"], $data["nombreImagen"], $data["pais"], $data["ciudad"], $data["latitud"], $data["longitud"]);
                    if ($resultadoDatos) {
                        Redirect::to("/PerfilUsuario");
                    } else {
                        $error = "Los datos del usuario no pudieron ser ingresados";
                        $this->modificarUsuario($error);
                        return;
                    }
                }
            }
            $this->modificarUsuario($error);
        }
    }

    public function iniciarDuelo(){
        if(isset($_GET["id"])){
            $_SESSION["rival_id"] = $_GET["id"];
            Redirect::to("/play/playDuelo");
        }else{
            Redirect::root();
        }
    }



    public function subirArchivo()
    {
        if (isset($_FILES["imagen"]["name"])) {
            $directorioImagen = "./public/img/users/";
            $nombreImagen = $_FILES["imagen"]["name"];
            $imagen = $directorioImagen . $nombreImagen;

            if (move_uploaded_file($_FILES["imagen"]["tmp_name"], $imagen)) {
                return true;
            } else {
                return "Error al subir la imagen";
            }
        }
        return true;
    }

    public function obtenerGenero($letra){
        switch($letra){
            case "M":
                return "Masculino";
                break;
            case "F":
                return "Femenino";
                break;
            default:
                return "Prefiero no cargarlo";
        }
    }

    public function obtenerLetraGenero($genero){
        switch($genero){
            case "Masculino":
                return "M";
                break;
            case "Femenino":
                return "F";
                break;
            default:
                return "X";
        }
    }


}
?>
