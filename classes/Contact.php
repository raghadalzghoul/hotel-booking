
<?php

require_once __DIR__ . "/Database.php";

class Contact
{
    private $conn;

    public function __construct()
    {
        $database = new Database();
        $this->conn = $database->connect();
    }

    public function saveMessage($name, $email, $subject, $message)
    {
        $sql = "INSERT INTO contact_messages
                (name, email, subject, message)
                VALUES
                (:name, :email, :subject, :message)";

        $stmt = $this->conn->prepare($sql);

        return $stmt->execute([
            "name" => $name,
            "email" => $email,
            "subject" => $subject,
            "message" => $message
        ]);
    }




    
public function getAllMessages()
{
    $sql = "SELECT * FROM contact_messages
            ORDER BY created_at DESC, id DESC";

    $stmt = $this->conn->prepare($sql);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

public function deleteMessage($id)
{
    $sql = "DELETE FROM contact_messages WHERE id = :id";

    $stmt = $this->conn->prepare($sql);

    $stmt->execute([
        ":id" => $id
    ]);

    return $stmt->rowCount() > 0;
}





}
