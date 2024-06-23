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

    public function query($sql) {
        $result = mysqli_query($this->conn, $sql);

        if (!is_bool($result))
            return mysqli_fetch_all($result, MYSQLI_BOTH);
        else
            return [];
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

    public function last_insert()
    {
        return mysqli_insert_id($this->conn);
    }

}
