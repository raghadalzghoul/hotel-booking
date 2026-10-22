
<?php

require_once __DIR__ . "/classes/Session.php";

$sessionObj = new Session();

$facilityId = filter_input(INPUT_GET, "facility_id", FILTER_VALIDATE_INT);

if (!$facilityId || $facilityId < 1) {
    die("Invalid facility.");
}

$sessions = $sessionObj->getSessionsByFacility($facilityId);

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
        <p>Select a date and time for your booking.</p>

        <?php if (empty($sessions)): ?>

            <p>No upcoming sessions available.</p>

        <?php else: ?>

            <?php foreach ($sessions as $session): ?>

                <div class="session-card">

                    <h3>
                        <?= htmlspecialchars($session["session_date"]) ?>
                    </h3>

                    <p>
                        Time:
                        <?= htmlspecialchars($session["start_time"]) ?>
                        -
                        <?= htmlspecialchars($session["end_time"]) ?>
                    </p>

                    <button type="button">
                        Select Session
                    </button>

                </div>

            <?php endforeach; ?>

        <?php endif; ?>

        <a href="facilities.php">Back to Facilities</a>

    </main>

</body>
</html>
