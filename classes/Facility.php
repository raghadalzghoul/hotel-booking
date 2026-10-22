
<?php

require_once __DIR__ . "/Database.php";

class Facility
{
    private $conn;

    public function __construct()
    {
        $database = new Database();
        $this->conn = $database->connect();
    }

    public function getAllFacilities()
    {
        $sql = "SELECT * FROM facilities";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
