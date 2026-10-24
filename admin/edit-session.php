
<?php

session_start();

require_once __DIR__ . "/../classes/Session.php";
require_once __DIR__ . "/../classes/Facility.php";

if (!isset($_SESSION["admin_id"])) {
    header("Location: login.php");
    exit;
}

$sessionObj = new Session();
$facilityObj = new Facility();

$id = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);

if (!$id || $id < 1) {
    die("Invalid session ID.");
}

$session = $sessionObj->getSessionById($id);

if (!$session) {
    die("Session not found.");
}

$facilities = $facilityObj->getAllFacilities();
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

    $facilityId = filter_input(
        INPUT_POST,
        "facility_id",
        FILTER_VALIDATE_INT
    );

    $date = $_POST["session_date"] ?? "";
    $startTime = $_POST["start_time"] ?? "";
    $endTime = $_POST["end_time"] ?? "";

    $validFacility = false;

    foreach ($facilities as $facility) {
        if ((int)$facility["id"] === $facilityId) {
            $validFacility = true;
            break;
        }
    }

    $dateObj = DateTime::createFromFormat("!Y-m-d", $date);

    $validDate = $dateObj &&
        $dateObj->format("Y-m-d") === $date;

    $validStart = preg_match(
        "/^([01][0-9]|2[0-3]):[0-5][0-9]$/",
        $startTime
    );

    $validEnd = preg_match(
        "/^([01][0-9]|2[0-3]):[0-5][0-9]$/",
        $endTime
    );


if ($sessionObj->hasBookings($id)) {

    $error = "Cannot edit this session because it already has bookings.";

} elseif (
    !$validFacility ||
    !$validDate ||
    !$validStart ||
    !$validEnd
) {

    $error = "Please enter valid session details.";

} elseif ($startTime >= $endTime) {

    $error = "End time must be after start time.";

} elseif (
    strtotime($date . " " . $startTime) <= time()
) {

    $error = "Session must start in the future.";

} else {

    try {

        $updated = $sessionObj->updateSession(
            $id,
            $facilityId,
            $date,
            $startTime,
            $endTime
        );

        if ($updated) {
            header("Location: sessions.php");
            exit;
        }

        $error = "Unable to update session.";

    } catch (PDOException $e) {

        error_log($e->getMessage());
        $error = "Unable to update session.";
    }
}


    // Keep entered values when validation fails
    $session["facility_id"] = $facilityId;
    $session["session_date"] = $date;
    $session["start_time"] = $startTime;
    $session["end_time"] = $endTime;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Edit Session</title>

    <link rel="stylesheet" href="../style/global.css">
    <link rel="stylesheet" href="../style/admin.css">
</head>
<body>

<main class="admin-container">

    <h1>Edit Session</h1>

    <?php if ($error !== ""): ?>
        <p class="error-message">
            <?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?>
        </p>
    <?php endif; ?>

    <form
        method="POST"
        action="edit-session.php?id=<?= (int)$id ?>"
        class="facility-form"
    >

        <input
            type="hidden"
            name="csrf_token"
            value="<?= htmlspecialchars($_SESSION["csrf_token"], ENT_QUOTES, 'UTF-8') ?>"
        >

        <label for="facility_id">Facility</label>

        <select
            name="facility_id"
            id="facility_id"
            required
        >
            <option value="">Select Facility</option>

            <?php foreach ($facilities as $facility): ?>
                <option
                    value="<?= (int)$facility["id"] ?>"
                    <?= (int)$session["facility_id"] === (int)$facility["id"] ? "selected" : "" ?>
                >
                    <?= htmlspecialchars($facility["name"], ENT_QUOTES, 'UTF-8') ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label for="session_date">Session Date</label>

        <input
            type="date"
            name="session_date"
            id="session_date"
            min="<?= date("Y-m-d") ?>"
            value="<?= htmlspecialchars($session["session_date"], ENT_QUOTES, 'UTF-8') ?>"
            required
        >

        <label for="start_time">Start Time</label>

        <input
            type="time"
            name="start_time"
            id="start_time"
            value="<?= htmlspecialchars(substr($session["start_time"], 0, 5), ENT_QUOTES, 'UTF-8') ?>"
            required
        >

        <label for="end_time">End Time</label>

        <input
            type="time"
            name="end_time"
            id="end_time"
            value="<?= htmlspecialchars(substr($session["end_time"], 0, 5), ENT_QUOTES, 'UTF-8') ?>"
            required
        >

        <button type="submit" class="save-btn">
            Save Changes
        </button>

    </form>

    <a href="sessions.php">Back to Sessions</a>

</main>

</body>
</html>
