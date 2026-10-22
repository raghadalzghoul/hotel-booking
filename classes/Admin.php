
<?php

require_once __DIR__ . "/Database.php";

class Admin
{
    private $conn;

    public function __construct()
    {
        $database = new Database();
        $this->conn = $database->connect();
    }

    public function register($name, $email, $password)
    {
        // Check if email already exists
        $sql = "SELECT id FROM admins WHERE email = :email";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            "email" => $email
        ]);

        if ($stmt->fetch()) {
            return false;
        }

        // Hash password before saving
        $hashedPassword = password_hash(
            $password,
            PASSWORD_DEFAULT
        );

        // Insert new admin
        $sql = "INSERT INTO admins (name, email, password)
                VALUES (:name, :email, :password)";

        $stmt = $this->conn->prepare($sql);

        return $stmt->execute([
            "name" => $name,
            "email" => $email,
            "password" => $hashedPassword
        ]);
    }



    
public function hasAdmins()
{
    $stmt = $this->conn->query(
        "SELECT COUNT(*) FROM admins"
    );

    return (int) $stmt->fetchColumn() > 0;
}








public function login($email, $password)
{
    // Find admin by email
    $sql = "SELECT * FROM admins WHERE email = :email";

    $stmt = $this->conn->prepare($sql);

    $stmt->execute([
        "email" => $email
    ]);

    $admin = $stmt->fetch(PDO::FETCH_ASSOC);

    // Check email and password
    if ($admin && password_verify($password, $admin["password"])) {
        return $admin;
    }

    return false;
}



}
