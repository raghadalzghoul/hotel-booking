
<?php

require_once __DIR__ . "/classes/Session.php";
require_once __DIR__ . "/classes/Booking.php";

$sessionObj = new Session();
$bookingObj = new Booking();

$facilityId = filter_input(INPUT_GET, "facility_id", FILTER_VALIDATE_INT);

if (!$facilityId || $facilityId < 1) {
    die("Invalid facility.");
}

$sessions = $sessionObj->getSessionsByFacility($facilityId);

$message = "";
$reference = "";
$selectedSessionId = 0;

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $selectedSessionId = filter_input(
        INPUT_POST,
        "session_id",
        FILTER_VALIDATE_INT
    );

    $guestName = trim($_POST["guest_name"] ?? "");
    $guestEmail = trim($_POST["guest_email"] ?? "");

    if (
        !$selectedSessionId ||
        $guestName === "" ||
        !filter_var($guestEmail, FILTER_VALIDATE_EMAIL)
    ) {
        $message = "Please enter valid booking information.";
    } else {

        // Check that the selected session belongs to this facility
        $validSession = false;

        foreach ($sessions as $session) {
            if ((int)$session["id"] === (int)$selectedSessionId) {
                $validSession = true;
                break;
            }
        }

        if (!$validSession) {
            $message = "Invalid session selected.";
        } else {

            $result = $bookingObj->createBooking(
                $selectedSessionId,
                $guestName,
                $guestEmail
            );

            if ($result["success"]) {
                $reference = $result["reference"];
                $message = "Booking confirmed successfully!";
            } else {
                $message = $result["message"];
            }
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Book Facility</title>

    <link rel="stylesheet" href="style/global.css">
    <link rel="stylesheet" href="style/booking.css">
</head>
<body>

<main class="booking-container">

    <h1>Available Sessions</h1>
    <p>Select a session to make your reservation.</p>

    <?php if ($message !== ""): ?>
        <p class="booking-message">
            <?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8') ?>
        </p>
    <?php endif; ?>

    <?php if ($reference !== ""): ?>

        <div class="booking-success">
            <h2>Booking Confirmed!</h2>

            <p>Your booking reference:</p>

            <h3>
                <?= htmlspecialchars($reference, ENT_QUOTES, 'UTF-8') ?>
            </h3>

            <p>Save this reference for later.</p>
        </div>

    <?php elseif (empty($sessions)): ?>

        <p>No upcoming sessions available.</p>

    <?php else: ?>

        <form
            method="POST"
            action="booking.php?facility_id=<?= (int)$facilityId ?>"
        >

            <label for="session_id">Select Session</label>

            <select name="session_id" id="session_id" required>

                <option value="">Choose a session</option>

                <?php foreach ($sessions as $session): ?>

                    <option
                        value="<?= (int)$session["id"] ?>"
                        <?= (int)$selectedSessionId === (int)$session["id"] ? "selected" : "" ?>
                    >
                        <?= htmlspecialchars($session["session_date"]) ?>
                        |
                        <?= htmlspecialchars($session["start_time"]) ?>
                        -
                        <?= htmlspecialchars($session["end_time"]) ?>
                    </option>

                <?php endforeach; ?>

            </select>

            <label for="guest_name">Full Name</label>

            <input
                type="text"
                name="guest_name"
                id="guest_name"
                value="<?= htmlspecialchars($guestName ?? '', ENT_QUOTES, 'UTF-8') ?>"
                required
            >

            <label for="guest_email">Email</label>

            <input
                type="email"
                name="guest_email"
                id="guest_email"
                value="<?= htmlspecialchars($guestEmail ?? '', ENT_QUOTES, 'UTF-8') ?>"
                required
            >

            <button type="submit">
                Confirm Booking
            </button>

        </form>

    <?php endif; ?>

    <a href="facilities.php">Back to Facilities</a>

</main>

</body>
</html>
