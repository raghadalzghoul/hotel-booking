
<?php

session_start();

require_once __DIR__ . "/../classes/Booking.php";

// Protect admin dashboard
if (!isset($_SESSION["admin_id"])) {
    header("Location: login.php");
    exit;
}


$bookingObj = new Booking();

// Create a CSRF token for admin actions
if (empty($_SESSION["csrf_token"])) {
    $_SESSION["csrf_token"] = bin2hex(random_bytes(32));
}

// Handle admin cancellation
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $token = $_POST["csrf_token"] ?? "";

    if (!hash_equals($_SESSION["csrf_token"], $token)) {
        http_response_code(403);
        die("Invalid request.");
    }

    $bookingId = filter_input(
        INPUT_POST,
        "booking_id",
        FILTER_VALIDATE_INT
    );

    if ($bookingId && $bookingId > 0) {
        $bookingObj->cancelBookingByAdmin($bookingId);
    }

    header("Location: dashboard.php");
    exit;
}

$bookings = $bookingObj->getAllBookings();


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Admin Dashboard</title>

    <link rel="stylesheet" href="../style/global.css">
    <link rel="stylesheet" href="../style/admin.css">
</head>
<body>

<main class="admin-container">

    <div class="admin-header">

        <div>
            <h1>Admin Dashboard</h1>

            <p>
                Welcome,
                <?= htmlspecialchars($_SESSION["admin_name"] ?? "Admin", ENT_QUOTES, 'UTF-8') ?>
            </p>
        </div>
       
<div class="admin-actions">
    <a href="facilities.php" class="logout-btn">
        Manage Facilities
    </a>

    <a href="logout.php" class="logout-btn">
        Logout
    </a>
</div>

    </div>

    <section class="bookings-section">

        <h2>All Bookings</h2>

        <?php if (empty($bookings)): ?>

            <p>No bookings found.</p>

        <?php else: ?>

            <div class="table-wrapper">

                <table>

                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Guest Name</th>
                            <th>Email</th>
                            <th>Facility</th>
                            <th>Date</th>
                            <th>Time</th>
                            <th>Reference</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>

                    <tbody>

                        <?php foreach ($bookings as $booking): ?>

                            <tr>

                                <td>
                                    <?= (int)$booking["id"] ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($booking["guest_name"], ENT_QUOTES, 'UTF-8') ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($booking["guest_email"], ENT_QUOTES, 'UTF-8') ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($booking["facility_name"], ENT_QUOTES, 'UTF-8') ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($booking["session_date"], ENT_QUOTES, 'UTF-8') ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($booking["start_time"], ENT_QUOTES, 'UTF-8') ?>
                                    -
                                    <?= htmlspecialchars($booking["end_time"], ENT_QUOTES, 'UTF-8') ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($booking["booking_reference"], ENT_QUOTES, 'UTF-8') ?>
                                </td>

                                <td>
                                    <span class="status <?= $booking["status"] === "confirmed" ? "confirmed" : "cancelled" ?>">
                                        <?= htmlspecialchars(ucfirst($booking["status"]), ENT_QUOTES, 'UTF-8') ?>
                                    </span>
                                </td>

                                
<td>

    <?php if ($booking["status"] === "confirmed"): ?>

        <form
            method="POST"
            action="dashboard.php"
            onsubmit="return confirm('Are you sure you want to cancel this booking?');"
        >

            <input
                type="hidden"
                name="booking_id"
                value="<?= (int)$booking["id"] ?>"
            >

            <input
                type="hidden"
                name="csrf_token"
                value="<?= htmlspecialchars($_SESSION["csrf_token"], ENT_QUOTES, 'UTF-8') ?>"
            >

            <button type="submit" class="cancel-booking-btn">
                Cancel
            </button>

        </form>

    <?php else: ?>

        <span class="no-action">—</span>

    <?php endif; ?>

</td>


                            </tr>

                        <?php endforeach; ?>

                    </tbody>

                </table>

            </div>

        <?php endif; ?>

    </section>

</main>

</body>
</html>
