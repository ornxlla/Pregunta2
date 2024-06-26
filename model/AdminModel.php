<?php
class AdminModel
{
    private $database;

    public function __construct($database)
    {
        $this->database = $database;
    }

    public function getTotalUsuarios($fechaInicio,$fechaFin)
    {
        $query = "SELECT COUNT(*) as total_usuarios FROM datos_usuario WHERE fecha_registro BETWEEN '$fechaInicio' AND '$fechaFin'";
        return $this->database->query($query);

    }

    public function getPreguntasTotales($fechaInicio, $fechaFin)
    {
        $query = "SELECT COUNT(id_pregunta) AS preguntasTotales FROM preguntas_listado WHERE fecha_creacion BETWEEN '$fechaInicio' AND '$fechaFin'";
        return $this->database->query($query);

    }


    public function getUsuariosPorPais($fechaInicio,$fechaFin){
        $query = "SELECT COUNT(id_usuario) AS contadorUsuarios, pais FROM datos_usuario WHERE fecha_registro BETWEEN '$fechaInicio' AND '$fechaFin'
                                           GROUP BY pais";
        return $this->database->query($query);
    }

    public function usuariosMenores($fechaInicio,$fechaFin){
        $query = "SELECT COUNT(id_usuario) AS usuariosMenores FROM datos_usuario WHERE YEAR(CURDATE()) - YEAR(nacimiento) - (DATE_FORMAT(CURDATE(), '%m%d') < DATE_FORMAT(nacimiento, '%m%d')) < 18 AND YEAR(CURDATE()) - YEAR(nacimiento) - (DATE_FORMAT(CURDATE(), '%m%d') < DATE_FORMAT(nacimiento, '%m%d')) > 0
                  AND fecha_registro BETWEEN '$fechaInicio' AND '$fechaFin'";
        return $this->database->query($query);
    }

    public function usuariosAdultos($fechaInicio,$fechaFin){
        $query = "SELECT COUNT(id_usuario) AS usuariosAdultos FROM datos_usuario WHERE YEAR(CURDATE()) - YEAR(nacimiento) - (DATE_FORMAT(CURDATE(), '%m%d') < DATE_FORMAT(nacimiento, '%m%d')) >= 18 AND YEAR(CURDATE()) - YEAR(nacimiento) - (DATE_FORMAT(CURDATE(), '%m%d') < DATE_FORMAT(nacimiento, '%m%d')) <= 64
                  AND fecha_registro BETWEEN '$fechaInicio' AND '$fechaFin'";
        return $this->database->query($query);
    }

    public function usuariosMayores($fechaInicio,$fechaFin){
        $query = "SELECT COUNT(id_usuario) AS usuariosMayores FROM datos_usuario WHERE YEAR(CURDATE()) - YEAR(nacimiento) - (DATE_FORMAT(CURDATE(), '%m%d') < DATE_FORMAT(nacimiento, '%m%d')) >= 65
                  AND fecha_registro BETWEEN '$fechaInicio' AND '$fechaFin'";
        return $this->database->query($query);
    }
    public function nuevosUsuarios($fechaInicio,$fechaFin){
        $query = "SELECT COUNT(id_usuario) AS nuevosUsuarios FROM datos_usuario WHERE fecha_registro >= CURDATE() AND fecha_registro BETWEEN '$fechaInicio' AND '$fechaFin'";
        return $this->database->query($query);
    }

    public function usuariosGeneroF($fechaInicio,$fechaFin){
        $query="SELECT COUNT(*) AS usuariosGeneroF FROM datos_usuario WHERE genero = 'F'AND fecha_registro BETWEEN '$fechaInicio' AND '$fechaFin'";
            return $this->database->query($query);
    }

    public function usuariosGeneroM($fechaInicio,$fechaFin){
        $query="SELECT COUNT(*) AS usuariosGeneroM FROM datos_usuario WHERE genero = 'M' AND fecha_registro BETWEEN '$fechaInicio' AND '$fechaFin'";
        return $this->database->query($query);
    }

    public function preguntasCreadas($fechaInicio,$fechaFin){
        $query="SELECT COUNT(*) AS preguntasCreadas FROM preguntas_sugeridas WHERE fecha BETWEEN '$fechaInicio' AND '$fechaFin'";
        return $this->database->query($query);
    }

    public function partidasClasicas($fechaInicio,$fechaFin){
        $query="SELECT COUNT(*) AS partidasClasicas FROM partida_clasica_general WHERE fecha BETWEEN '$fechaInicio' AND '$fechaFin';";
        return $this->database->query($query);
    }

    public function partidasDuelo($fechaInicio,$fechaFin){
        $query="SELECT COUNT(*) AS partidasDuelo FROM partida_duelo_general WHERE fecha BETWEEN '$fechaInicio' AND '$fechaFin';";
        return $this->database->query($query);
    }

    public function porcentajeRespUser($fechaInicio,$fechaFin) {
        $query = "SELECT du.id_usuario, du.nombre AS nombre_usuario, du.imagen_perfil,COUNT(pr.acertado = 1 OR NULL) AS respuestas_correctas,
                COUNT(pr.id_jugador) AS total_respuestas, ROUND((COUNT(pr.acertado = 1 OR NULL) / COUNT(pr.id_jugador)) * 100, 2) AS porcentaje_correctas
                FROM partida_clasica_respuestas pr 
                INNER JOIN partida_clasica_general pg ON pr.id_partida = pg.id 
                INNER JOIN datos_usuario du ON pr.id_jugador = du.id_usuario
                LEFT JOIN respuesta_listado rl ON pr.id_pregunta = rl.id_pregunta AND pr.acertado = rl.correcta
                WHERE pg.hora_final != '0000-00-00 00:00:00'  -- Excluir partidas incompletas
                 AND pg.fecha BETWEEN '$fechaInicio' AND '$fechaFin' 
                GROUP BY du.id_usuario, du.nombre, du.imagen_perfil  ORDER BY 
porcentaje_correctas DESC";

        return $this->database->query($query);
    }

}
?>

