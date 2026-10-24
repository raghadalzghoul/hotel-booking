
<?php

session_start();

require_once __DIR__ . "/../classes/Facility.php";

if (!isset($_SESSION["admin_id"])) {
    header("Location: login.php");
    exit;
}

$facilityObj = new Facility();

$id = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);

if (!$id || $id < 1) {
    die("Invalid facility ID.");
}

$facility = $facilityObj->getFacilityById($id);

if (!$facility) {
    die("Facility not found.");
}

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
    $imageUrl = trim($_POST["image_url"] ?? "");

    if ($name === "" || !$capacity || $capacity < 1) {

        $error = "Please enter a valid name and capacity.";

    } else {

        $updated = $facilityObj->updateFacility(
            $id,
            $name,
            $description,
            $capacity,
            $imageUrl
        );

        if ($updated) {
            header("Location: facilities.php");
            exit;
        }

        $error = "Unable to update facility.";
    }

    // Keep entered values if validation fails
    $facility["name"] = $name;
    $facility["description"] = $description;
    $facility["capacity"] = $capacity;
    $facility["image_url"] = $imageUrl;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Edit Facility</title>

    <link rel="stylesheet" href="../style/global.css">
    <link rel="stylesheet" href="../style/admin.css">
</head>
<body>

<main class="admin-container">

    <h1>Edit Facility</h1>

    <?php if ($error !== ""): ?>

        <p class="error-message">
            <?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?>
        </p>

    <?php endif; ?>

    <form
        method="POST"
        action="edit-facility.php?id=<?= (int)$id ?>"
        class="facility-form"
    >

        <input
            type="hidden"
            name="csrf_token"
            value="<?= htmlspecialchars($_SESSION["csrf_token"], ENT_QUOTES, 'UTF-8') ?>"
        >

        <label for="name">Facility Name</label>

        <input
            type="text"
            name="name"
            id="name"
            value="<?= htmlspecialchars($facility["name"], ENT_QUOTES, 'UTF-8') ?>"
            required
        >

        <label for="description">Description</label>

        <textarea
            name="description"
            id="description"
            rows="4"
        ><?= htmlspecialchars($facility["description"] ?? "", ENT_QUOTES, 'UTF-8') ?></textarea>

        <label for="capacity">Capacity</label>

        <input
            type="number"
            name="capacity"
            id="capacity"
            min="1"
            value="<?= (int)$facility["capacity"] ?>"
            required
        >

        <label for="image_url">Image Path</label>

        <input
            type="text"
            name="image_url"
            id="image_url"
            value="<?= htmlspecialchars($facility["image_url"] ?? "", ENT_QUOTES, 'UTF-8') ?>"
            placeholder="assets/images/sauna.jpg"
        >

        <button type="submit" class="save-btn">
            Save Changes
        </button>

    </form>

    <a href="facilities.php">Back to Facilities</a>

</main>

</body>
</html>
