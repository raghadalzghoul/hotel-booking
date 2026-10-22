
<?php

require_once __DIR__ . "/Database.php";

class Booking
{
    private $conn;

    public function __construct()
    {
        $database = new Database();
        $this->conn = $database->connect();
    }

    public function createBooking($sessionId, $guestName, $guestEmail)
    {
        try {
            // Start transaction
            $this->conn->beginTransaction();

            // Get session capacity and lock the session
            $sql = "SELECT s.id, f.capacity
                    FROM sessions s
                    JOIN facilities f ON s.facility_id = f.id
                    WHERE s.id = :session_id
                    AND (
                        s.session_date > CURDATE()
                        OR (
                            s.session_date = CURDATE()
                            AND s.start_time > CURTIME()
                        )
                    )
                    FOR UPDATE";

            $stmt = $this->conn->prepare($sql);

            $stmt->execute([
                "session_id" => $sessionId
            ]);

            $session = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$session) {
                $this->conn->rollBack();

                return [
                    "success" => false,
                    "message" => "Session not found or already started."
                ];
            }

            // Count confirmed bookings
            $sql = "SELECT COUNT(*) FROM bookings
                    WHERE session_id = :session_id
                    AND status = 'confirmed'";

            $stmt = $this->conn->prepare($sql);

            $stmt->execute([
                "session_id" => $sessionId
            ]);

            $bookedCount = (int) $stmt->fetchColumn();

            // Check capacity
            if ($bookedCount >= (int) $session["capacity"]) {
                $this->conn->rollBack();

                return [
                    "success" => false,
                    "message" => "This session is fully booked."
                ];
            }

            // Generate booking reference
            $reference = strtoupper(bin2hex(random_bytes(6)));

            // Save booking
            $sql = "INSERT INTO bookings
                    (session_id, guest_name, guest_email,
                     booking_reference, status)
                    VALUES
                    (:session_id, :guest_name, :guest_email,
                     :reference, 'confirmed')";

            $stmt = $this->conn->prepare($sql);

            $stmt->execute([
                "session_id" => $sessionId,
                "guest_name" => $guestName,
                "guest_email" => $guestEmail,
                "reference" => $reference
            ]);

            // Save transaction
            $this->conn->commit();

            return [
                "success" => true,
                "reference" => $reference
            ];

        } catch (Exception $e) {

            if ($this->conn->inTransaction()) {
                $this->conn->rollBack();
            }

            error_log($e->getMessage());

            return [
                "success" => false,
                "message" => "Booking failed. Please try again."
            ];
        }
    }









    
public function getBooking($reference, $email)
{
    $sql = "SELECT
                b.id,
                b.guest_name,
                b.guest_email,
                b.booking_reference,
                b.status,
                f.name AS facility_name,
                s.session_date,
                s.start_time,
                s.end_time
            FROM bookings b
            JOIN sessions s ON b.session_id = s.id
            JOIN facilities f ON s.facility_id = f.id
            WHERE b.booking_reference = :reference
            AND b.guest_email = :email";

    $stmt = $this->conn->prepare($sql);

    $stmt->execute([
        "reference" => $reference,
        "email" => $email
    ]);

    return $stmt->fetch(PDO::FETCH_ASSOC);
}










public function cancelBooking($reference, $email)
{
    $sql = "UPDATE bookings
            SET status = 'cancelled'
            WHERE booking_reference = :reference
            AND guest_email = :email
            AND status = 'confirmed'";

    $stmt = $this->conn->prepare($sql);

    $stmt->execute([
        "reference" => $reference,
        "email" => $email
    ]);

    return $stmt->rowCount() > 0;
}











public function getAllBookings()
{
    $sql = "SELECT
                b.id,
                b.guest_name,
                b.guest_email,
                b.booking_reference,
                b.status,
                f.name AS facility_name,
                s.session_date,
                s.start_time,
                s.end_time
            FROM bookings b
            JOIN sessions s ON b.session_id = s.id
            JOIN facilities f ON s.facility_id = f.id
            ORDER BY s.session_date DESC, s.start_time DESC";

    $stmt = $this->conn->prepare($sql);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

}
