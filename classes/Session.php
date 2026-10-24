
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



    
public function getAllSessions()
{
    $sql = "SELECT
                sessions.id,
                facilities.name AS facility_name,
                sessions.session_date,
                sessions.start_time,
                sessions.end_time
            FROM sessions
            JOIN facilities
                ON sessions.facility_id = facilities.id
            ORDER BY sessions.session_date DESC,
                     sessions.start_time DESC";

    $stmt = $this->conn->prepare($sql);

    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}




public function addSession($facilityId, $date, $startTime, $endTime)
{
    $sql = "INSERT INTO sessions
            (facility_id, session_date, start_time, end_time)
            VALUES (:facility_id, :session_date, :start_time, :end_time)";

    $stmt = $this->conn->prepare($sql);

    return $stmt->execute([
        "facility_id" => $facilityId,
        "session_date" => $date,
        "start_time" => $startTime,
        "end_time" => $endTime
    ]);
}





public function getSessionById($id)
{
    $sql = "SELECT * FROM sessions WHERE id = :id";

    $stmt = $this->conn->prepare($sql);

    $stmt->execute([
        "id" => $id
    ]);

    return $stmt->fetch(PDO::FETCH_ASSOC);
}




public function updateSession($id, $facilityId, $date, $startTime, $endTime)
{
    // Do not edit sessions with existing bookings
    if ($this->hasBookings($id)) {
        return false;
    }

    $sql = "UPDATE sessions
            SET facility_id = :facility_id,
                session_date = :session_date,
                start_time = :start_time,
                end_time = :end_time
            WHERE id = :id
              AND NOT EXISTS (
                  SELECT 1
                  FROM bookings
                  WHERE bookings.session_id = sessions.id
              )";

    $stmt = $this->conn->prepare($sql);

    $stmt->execute([
        "id" => $id,
        "facility_id" => $facilityId,
        "session_date" => $date,
        "start_time" => $startTime,
        "end_time" => $endTime
    ]);

    return $stmt->rowCount() > 0;
}




public function deleteSession($id)
{
    $sql = "DELETE FROM sessions WHERE id = :id";

    $stmt = $this->conn->prepare($sql);

    $stmt->execute([
        "id" => $id
    ]);

    return $stmt->rowCount() > 0;
}




public function hasBookings($sessionId)
{
    $sql = "SELECT COUNT(*)
            FROM bookings
            WHERE session_id = :session_id";

    $stmt = $this->conn->prepare($sql);

    $stmt->execute([
        "session_id" => $sessionId
    ]);

    return $stmt->fetchColumn() > 0;
}

}
