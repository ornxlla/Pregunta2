<?php

class RankingModel
{
    private $database;

    public function __construct($database)
    {
        $this->database = $database;
    }


    public function getRanking()
{
    $query = "SELECT login.username, MAX(partida.puntos_jugador_1) AS max_puntos, datos_usuario.imagen_perfil, datos_usuario.id_usuario 
              FROM partida 
              JOIN login ON partida.id_jugador_1 = login.id_usuario 
              JOIN datos_usuario ON login.id_usuario = datos_usuario.id_usuario
              GROUP BY login.username
              ORDER BY max_puntos DESC 
              LIMIT 10";

    // Preparar la consulta
    $stmt = $this->database->prepare($query);
    if (!$stmt) {
        echo "Error preparando la consulta: " . $this->database->error;
        return false;
    }

    // Ejecutar la consulta
    if (!$stmt->execute()) {
        echo "Error ejecutando la consulta: " . $stmt->error;
        return false;
    }

    // Obtener el resultado de la consulta
    $result = $stmt->get_result();
    if (!$result) {
        echo "Error obteniendo el resultado: " . $stmt->error;
        return false;
    }

    // Obtener todos los resultados como un array asociativo
    $usuarios = $result->fetch_all(MYSQLI_ASSOC);

    // Liberar el resultado y cerrar la consulta
    $stmt->close();

    // Devolver el array de usuarios
    return $usuarios;
}

}