<?php
class AdminModel
{
    private $database;

    public function __construct($database)
    {
        $this->database = $database;
    }

    public function getTotalUsers()
    {
        $query = "SELECT COUNT(*) as total_users FROM login";
        return $this->database->query($query);

    }

    public function getCantidadPreguntas(){
        $query = "SELECT COUNT(id_pregunta) AS preguntasTotales FROM preguntas";
        return $this->database->query($query);
    }

    public function getUsuariosPorPais(){
        $query = "SELECT COUNT(id) AS contadorUsuarios, pais FROM datos_usuario
                                           GROUP BY pais";
        return $this->database->query($query);
    }
}
?>

