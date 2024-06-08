<?php
class Database
{
    private $conn;

    public function __construct($servername, $username, $password, $dbname)
    {
        $this->conn = mysqli_connect($servername, $username, $password, $dbname);

        if (!$this->conn) {
            die("Connection failed: " . mysqli_connect_error());
        }
    }

    public function query($sql): array
    {
        $result = mysqli_query($this->conn, $sql);

        if ($result === false) {
            // La consulta falló, retornar un array vacío o lanzar una excepción, según lo que prefieras
            return [];
            // O puedes lanzar una excepción para notificar sobre el error
            // throw new Exception("Error executing query: " . mysqli_error($this->conn));
        }

        return mysqli_fetch_all($result, MYSQLI_ASSOC);
    }
    public function execute($sql)
    {
        mysqli_query($this->conn, $sql);
    }

    public function __destruct()
    {
        mysqli_close($this->conn);
    }

    public function prepare($query)
    {
        return $this->conn->prepare($query);
    }

}
