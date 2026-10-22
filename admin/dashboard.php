
<?php

session_start();

require_once __DIR__ . "/../classes/Booking.php";

// Protect admin dashboard
if (!isset($_SESSION["admin_id"])) {
    header("Location: login.php");
    exit;
}

$bookingObj = new Booking();
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

        <a href="logout.php" class="logout-btn">
            Logout
        </a>

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
