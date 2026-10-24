
<?php

session_start();

require_once __DIR__ . "/../classes/Database.php";

// Protect admin dashboard
if (!isset($_SESSION["admin_id"])) {
    header("Location: login.php");
    exit;
}

// Connect to database
$database = new Database();
$conn = $database->connect();

// Get dashboard statistics
$totalFacilities = (int) $conn
    ->query("SELECT COUNT(*) FROM facilities")
    ->fetchColumn();

$totalSessions = (int) $conn
    ->query("SELECT COUNT(*) FROM sessions")
    ->fetchColumn();

$totalBookings = (int) $conn
    ->query("SELECT COUNT(*) FROM bookings")
    ->fetchColumn();

$totalMessages = (int) $conn
    ->query("SELECT COUNT(*) FROM contact_messages")
    ->fetchColumn();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Admin Dashboard | Grand Haven Hotel</title>

    <link rel="stylesheet" href="../style/global.css?v=3">
    <link rel="stylesheet" href="../style/admin.css?v=5">
</head>

<body>

<div class="admin-layout">

    <!-- ADMIN SIDEBAR -->
    <?php include __DIR__ . "/includes/sidebar.php"; ?>

    <!-- MAIN CONTENT -->
    <main class="admin-main">

        <div class="admin-container">

            <!-- DASHBOARD HEADER -->
            <div class="admin-header">

                <div>
                    <h1>Admin Dashboard</h1>

                    <p>
                        Welcome,
                        <?= htmlspecialchars(
                            $_SESSION["admin_name"] ?? "Admin",
                            ENT_QUOTES,
                            'UTF-8'
                        ) ?>
                    </p>
                </div>

            </div>

            <!-- DASHBOARD STATISTICS -->
            <section class="dashboard-stats">

                <!-- FACILITIES -->
                <div class="stat-card">

                    <div class="stat-icon">🏨</div>

                    <div>
                        <h3><?= $totalFacilities ?></h3>
                        <p>Total Facilities</p>
                    </div>

                </div>

                <!-- SESSIONS -->
                <div class="stat-card">

                    <div class="stat-icon">📅</div>

                    <div>
                        <h3><?= $totalSessions ?></h3>
                        <p>Total Sessions</p>
                    </div>

                </div>

                <!-- BOOKINGS -->
                <div class="stat-card">

                    <div class="stat-icon">📋</div>

                    <div>
                        <h3><?= $totalBookings ?></h3>
                        <p>Total Bookings</p>
                    </div>

                </div>

                <!-- MESSAGES -->
                <div class="stat-card">

                    <div class="stat-icon">✉️</div>

                    <div>
                        <h3><?= $totalMessages ?></h3>
                        <p>Guest Messages</p>
                    </div>

                </div>

            </section>

            <!-- QUICK ACTIONS -->
            <section class="dashboard-shortcuts">

                <h2>Quick Actions</h2>

                <p class="shortcuts-description">
                    Manage your hotel facilities, sessions,
                    guest reservations, and contact messages.
                </p>

                <div class="shortcuts-links">

                    <a href="facilities.php">
                        Manage Facilities
                    </a>

                    <a href="sessions.php">
                        Manage Sessions
                    </a>

                    <a href="bookings.php">
                        Manage Bookings
                    </a>

                    <a href="messages.php">
                        View Messages
                    </a>

                </div>

            </section>

        </div>

    </main>

</div>

</body>
</html>
