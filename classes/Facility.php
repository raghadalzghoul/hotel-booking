
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

     


    
public function addFacility($name, $description, $capacity, $imageUrl)
{
    $sql = "INSERT INTO facilities
            (name, description, capacity, image_url)
            VALUES (:name, :description, :capacity, :image_url)";

    $stmt = $this->conn->prepare($sql);

    return $stmt->execute([
        "name" => $name,
        "description" => $description,
        "capacity" => $capacity,
        "image_url" => $imageUrl
    ]);
}





public function getFacilityById($id)
{
    $sql = "SELECT * FROM facilities WHERE id = :id";

    $stmt = $this->conn->prepare($sql);

    $stmt->execute([
        "id" => $id
    ]);

    return $stmt->fetch(PDO::FETCH_ASSOC);
}



public function updateFacility($id, $name, $description, $capacity, $imageUrl)
{
    $sql = "UPDATE facilities
            SET name = :name,
                description = :description,
                capacity = :capacity,
                image_url = :image_url
            WHERE id = :id";

    $stmt = $this->conn->prepare($sql);

    return $stmt->execute([
        "id" => $id,
        "name" => $name,
        "description" => $description,
        "capacity" => $capacity,
        "image_url" => $imageUrl
    ]);
}





public function deleteFacility($id)
{
    $sql = "DELETE FROM facilities WHERE id = :id";

    $stmt = $this->conn->prepare($sql);

    $stmt->execute([
        "id" => $id
    ]);

    return $stmt->rowCount() > 0;
}




}
