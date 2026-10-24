
<?php
require_once __DIR__ . "/../classes/CloudinaryUploader.php";

session_start();

require_once __DIR__ . "/../classes/Facility.php";

if (!isset($_SESSION["admin_id"])) {
    header("Location: login.php");
    exit;
}

$facilityObj = new Facility();
$error = "";

if (empty($_SESSION["csrf_token"])) {
    $_SESSION["csrf_token"] = bin2hex(random_bytes(32));
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    if (!hash_equals(
        $_SESSION["csrf_token"],
        $_POST["csrf_token"] ?? ""
    )) {
        http_response_code(403);
        die("Invalid request.");
    }

    $name = trim($_POST["name"] ?? "");
    $description = trim($_POST["description"] ?? "");
    $capacity = filter_input(INPUT_POST, "capacity", FILTER_VALIDATE_INT);
  

    if ($name === "" || !$capacity || $capacity < 1) {

        $error = "Please enter a facility name and valid capacity.";


} else {

    try {

        // Upload image to Cloudinary
        $uploader = new CloudinaryUploader();

        $imageUrl = $uploader->uploadImage(
            $_FILES["image"] ?? []
        );

        // Save facility in MySQL
        $added = $facilityObj->addFacility(
            $name,
            $description,
            $capacity,
            $imageUrl
        );

        if ($added) {

            $_SESSION["facility_message"] = "Facility added successfully.";

            header("Location: facilities.php");
            exit;
        }

        $error = "Unable to add facility.";

    } catch (Exception $e) {

        error_log($e->getMessage());

        $error = "Unable to upload image or add facility.";
    }
}

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Add Facility</title>

    <link rel="stylesheet" href="../style/global.css">
    <link rel="stylesheet" href="../style/admin.css">
</head>
<body>

<main class="admin-container">

    <h1>Add New Facility</h1>

    <?php if ($error !== ""): ?>
        <p class="error-message">
            <?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?>
        </p>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data" class="facility-form">

        <input
            type="hidden"
            name="csrf_token"
            value="<?= htmlspecialchars($_SESSION["csrf_token"], ENT_QUOTES, 'UTF-8') ?>"
        >

        <label for="name">Facility Name</label>
        <input type="text" name="name" id="name" required>

        <label for="description">Description</label>
        <textarea name="description" id="description" rows="4"></textarea>

        <label for="capacity">Capacity</label>
        <input type="number" name="capacity" id="capacity" min="1" required>
<label for="image">Facility Image</label>

<input
    type="file"
    name="image"
    id="image"
    accept="image/jpeg,image/png,image/webp"
    required
>

        <button type="submit" class="save-btn">
            Add Facility
        </button>

    </form>

    <a href="facilities.php">Back to Facilities</a>

</main>

</body>
</html>
