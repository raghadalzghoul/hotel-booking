
<?php

require_once __DIR__ . "/classes/Booking.php";

$bookingObj = new Booking();

$booking = null;
$error = "";
$success = "";

$reference = "";
$email = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $reference = strtoupper(trim($_POST["reference"] ?? ""));
    $email = trim($_POST["email"] ?? "");
    $action = $_POST["action"] ?? "find";

    // Validate inputs
    if ($reference === "" || !filter_var($email, FILTER_VALIDATE_EMAIL)) {

        $error = "Please enter a valid booking reference and email.";

    } else {

        // Cancel booking
        if ($action === "cancel") {

            $cancelled = $bookingObj->cancelBooking($reference, $email);

            if ($cancelled) {
                $success = "Booking cancelled successfully!";
            } else {
                $error = "Unable to cancel this booking.";
            }

        } elseif ($action !== "find") {

            $error = "Invalid action.";
        }

        // Find booking
        if ($error === "" || $action === "cancel") {

            $booking = $bookingObj->getBooking($reference, $email);

            if (!$booking && $error === "") {
                $error = "Booking not found. Check your reference and email.";
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

    <title>My Bookings</title>

    <link rel="stylesheet" href="style/global.css?v=3">
    <link rel="stylesheet" href="style/my-bookings.css">

</head>
<body>

<?php include __DIR__ . "/includes/header.php"; ?>

<main class="my-bookings-container">

    <h1>My Bookings</h1>

    <p>
        Enter your booking reference and email
        to find or cancel your reservation.
    </p>

    <!-- Find Booking Form -->

    <form method="POST"  action="my-bookings.php">

        <input type="hidden" name="action" value="find">

        <label for="reference">Booking Reference</label>

        <input
            type="text"
            name="reference"
            id="reference"
            value="<?= htmlspecialchars($reference, ENT_QUOTES, 'UTF-8') ?>"
            placeholder="Enter booking reference"
            required
        >

        <label for="email">Email</label>

        <input
            type="email"
            name="email"
            id="email"
            value="<?= htmlspecialchars($email, ENT_QUOTES, 'UTF-8') ?>"
            placeholder="Enter your email"
            required
        >

        <button type="submit">
            Find Booking
        </button>

    </form>

    <!-- Success Message -->

    <?php if ($success !== ""): ?>

        <p class="success-message">
            <?= htmlspecialchars($success, ENT_QUOTES, 'UTF-8') ?>
        </p>

    <?php endif; ?>

    <!-- Error Message -->

    <?php if ($error !== ""): ?>

        <p class="error-message">
            <?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?>
        </p>

    <?php endif; ?>

    <!-- Booking Details -->

    <?php if ($booking): ?>

        <div class="booking-details">

            <h2>Booking Details</h2>

            <p>
                <strong>Guest Name:</strong>
                <?= htmlspecialchars($booking["guest_name"], ENT_QUOTES, 'UTF-8') ?>
            </p>

            <p>
                <strong>Email:</strong>
                <?= htmlspecialchars($booking["guest_email"], ENT_QUOTES, 'UTF-8') ?>
            </p>

            <p>
                <strong>Facility:</strong>
                <?= htmlspecialchars($booking["facility_name"], ENT_QUOTES, 'UTF-8') ?>
            </p>

            <p>
                <strong>Date:</strong>
                <?= htmlspecialchars($booking["session_date"], ENT_QUOTES, 'UTF-8') ?>
            </p>

            <p>
                <strong>Time:</strong>
                <?= htmlspecialchars($booking["start_time"], ENT_QUOTES, 'UTF-8') ?>
                -
                <?= htmlspecialchars($booking["end_time"], ENT_QUOTES, 'UTF-8') ?>
            </p>

            <p>
                <strong>Booking Reference:</strong>
                <?= htmlspecialchars($booking["booking_reference"], ENT_QUOTES, 'UTF-8') ?>
            </p>

            <p>
                <strong>Status:</strong>
                <?= htmlspecialchars(ucfirst($booking["status"]), ENT_QUOTES, 'UTF-8') ?>
            </p>

            <!-- Cancel Booking Button -->

            <?php if ($booking["status"] === "confirmed"): ?>

                <form
                    method="POST"
                    action="my-bookings.php"
                    onsubmit="return confirm('Are you sure you want to cancel this booking?');"
                >

                    <input
                        type="hidden"
                        name="action"
                        value="cancel"
                    >

                    <input
                        type="hidden"
                        name="reference"
                        value="<?= htmlspecialchars($booking["booking_reference"], ENT_QUOTES, 'UTF-8') ?>"
                    >

                    <input
                        type="hidden"
                        name="email"
                        value="<?= htmlspecialchars($booking["guest_email"], ENT_QUOTES, 'UTF-8') ?>"
                    >

                    <button type="submit" class="cancel-btn">
                        Cancel Booking
                    </button>

                </form>

            <?php endif; ?>

        </div>

    <?php endif; ?>

    <a href="facilities.php">
        Back to Facilities
    </a>

</main>
<?php include __DIR__ . "/includes/footer.php"; ?>
</body>
</html>
