<?php
class AdminModel
{
    private $database;

    public function __construct($database)
    {
        $this->database = $database;
    }

    public function getTotalUsuarios()
    {
        $query = "SELECT COUNT(*) as total_usuarios FROM login";
        return $this->database->query($query);

    }

    public function getCantidadPreguntas(){
        $query = "SELECT COUNT(id_pregunta) AS preguntasTotales FROM preguntas_listado";
        return $this->database->query($query);
    }

    public function getUsuariosPorPais(){
        $query = "SELECT COUNT(id_usuario) AS contadorUsuarios, pais FROM datos_usuario
                                           GROUP BY pais";
        return $this->database->query($query);
    }

    public function usuariosMenores(){
        $query = "SELECT COUNT(id_usuario) AS usuariosMenores FROM datos_usuario WHERE YEAR(CURDATE()) - YEAR(nacimiento) - (DATE_FORMAT(CURDATE(), '%m%d') < DATE_FORMAT(nacimiento, '%m%d')) < 18 AND YEAR(CURDATE()) - YEAR(nacimiento) - (DATE_FORMAT(CURDATE(), '%m%d') < DATE_FORMAT(nacimiento, '%m%d')) > 0";
        return $this->database->query($query);
    }

    public function usuariosAdultos(){
        $query = "SELECT COUNT(id_usuario) AS usuariosAdultos FROM datos_usuario WHERE YEAR(CURDATE()) - YEAR(nacimiento) - (DATE_FORMAT(CURDATE(), '%m%d') < DATE_FORMAT(nacimiento, '%m%d')) >= 18 AND YEAR(CURDATE()) - YEAR(nacimiento) - (DATE_FORMAT(CURDATE(), '%m%d') < DATE_FORMAT(nacimiento, '%m%d')) <= 64";
        return $this->database->query($query);
    }

    public function usuariosMayores(){
        $query = "SELECT COUNT(id_usuario) AS usuariosMayores FROM datos_usuario WHERE YEAR(CURDATE()) - YEAR(nacimiento) - (DATE_FORMAT(CURDATE(), '%m%d') < DATE_FORMAT(nacimiento, '%m%d')) >= 65";
        return $this->database->query($query);
    }
    public function nuevosUsuarios(){
        $query = "SELECT COUNT(id_usuario) AS nuevosUsuarios FROM datos_usuario WHERE fecha_registro >= CURDATE()";
        return $this->database->query($query);
    }

    public function usuariosGeneroF(){
        $query="SELECT COUNT(*) AS usuariosGeneroF FROM datos_usuario WHERE genero = 'F';";
            return $this->database->query($query);
    }

    public function usuariosGeneroM(){
        $query="SELECT COUNT(*) AS usuariosGeneroM FROM datos_usuario WHERE genero = 'M';";
        return $this->database->query($query);
    }

    public function preguntasCreadas(){
        $query="SELECT COUNT(*) AS preguntasCreadas FROM preguntas_sugeridas;";
        return $this->database->query($query);
    }

    public function partidasClasicas(){
        $query="SELECT COUNT(*) AS partidasClasicas FROM partida_clasica_general;";
        return $this->database->query($query);
    }

    public function partidasDuelo(){
        $query="SELECT COUNT(*) AS partidasDuelo FROM partida_duelo_general;";
        return $this->database->query($query);
    }
}

?>

