
<?php

require_once __DIR__ . "/Database.php";

class Session
{
    private $conn;

    public function __construct()
    {
        $database = new Database();
        $this->conn = $database->connect();
    }

    public function getSessionsByFacility($facilityId)
    {
        $sql = "SELECT * FROM sessions
                WHERE facility_id = :facility_id
                AND session_date >= CURDATE()
                ORDER BY session_date, start_time";

        $stmt = $this->conn->prepare($sql);

        $stmt->execute([
            "facility_id" => $facilityId
        ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
