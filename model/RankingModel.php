<?php

class RankingModel
{
    private $database;

    public function __construct($database)
    {
        $this->database = $database;
    }

    public function getRanking(){
        $query = "SELECT login.username, max(partida.puntos_jugador_1) AS max_puntos, datos_usuario.imagen_perfil, datos_usuario.id_usuario FROM partida join login
                  on partida.id_jugador_1 = login.id_usuario 
                  join datos_usuario on login.id_usuario = datos_usuario.id_usuario
                  group by login.username
                  order by max_puntos DESC LIMIT 10";
        return $this->database->query($query);
    }


}