
<?php

class Database
{
    private $host = "localhost";
    private $dbName = "hotel_booking";
    private $username = "root";
    private $password = "";

    private $conn;

    public function connect()
    {
        try {
            $this->conn = new PDO(
                "mysql:host={$this->host};dbname={$this->dbName};charset=utf8mb4",
                $this->username,
                $this->password
            );

            $this->conn->setAttribute(
                PDO::ATTR_ERRMODE,
                PDO::ERRMODE_EXCEPTION
            );

            return $this->conn;

        } catch (PDOException $e) {
            error_log($e->getMessage());
            throw new RuntimeException("Database connection failed.");
        }
    }
}
