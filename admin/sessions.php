
<?php

session_start();

require_once __DIR__ . "/../classes/Session.php";

// Protect admin page
if (!isset($_SESSION["admin_id"])) {
    header("Location: login.php");
    exit;
}

// Get all sessions
$sessionObj = new Session();
$sessions = $sessionObj->getAllSessions();

// Create CSRF token
if (empty($_SESSION["csrf_token"])) {
    $_SESSION["csrf_token"] = bin2hex(random_bytes(32));
}

// Display message after add, edit, or delete
$message = $_SESSION["session_message"] ?? "";
unset($_SESSION["session_message"]);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Manage Sessions | Grand Haven Hotel</title>

    <link rel="stylesheet" href="../style/global.css?v=3">
    <link rel="stylesheet" href="../style/admin.css?v=7">
</head>

<body>

<div class="admin-layout">

    <!-- ADMIN SIDEBAR -->
    <?php include __DIR__ . "/includes/sidebar.php"; ?>

    <!-- MAIN CONTENT -->
    <main class="admin-main">

        <div class="admin-container">

            <!-- PAGE HEADER -->
            <div class="admin-header">

                <div>
                    <h1>Manage Sessions</h1>
                    <p>View and manage facility booking sessions.</p>
                </div>

                <a href="add-session.php" class="add-facility-btn">
                    + Add New Session
                </a>

            </div>

            <!-- SUCCESS MESSAGE -->
            <?php if ($message !== ""): ?>

                <p class="facility-message">
                    <?= htmlspecialchars(
                        $message,
                        ENT_QUOTES,
                        'UTF-8'
                    ) ?>
                </p>

            <?php endif; ?>

            <!-- SESSIONS SECTION -->
            <section class="bookings-section">

                <h2>All Sessions</h2>

                <?php if (empty($sessions)): ?>

                    <p>No sessions found.</p>

                <?php else: ?>

                    <div class="table-wrapper">

                        <table>

                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Facility</th>
                                    <th>Date</th>
                                    <th>Start Time</th>
                                    <th>End Time</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>

                            <tbody>

                                <?php foreach ($sessions as $session): ?>

                                    <tr>

                                        <!-- SESSION ID -->
                                        <td>
                                            <?= (int) $session["id"] ?>
                                        </td>

                                        <!-- FACILITY NAME -->
                                        <td>
                                            <?= htmlspecialchars(
                                                $session["facility_name"],
                                                ENT_QUOTES,
                                                'UTF-8'
                                            ) ?>
                                        </td>

                                        <!-- SESSION DATE -->
                                        <td>
                                            <?= htmlspecialchars(
                                                $session["session_date"],
                                                ENT_QUOTES,
                                                'UTF-8'
                                            ) ?>
                                        </td>

                                        <!-- START TIME -->
                                        <td>
                                            <?= htmlspecialchars(
                                                $session["start_time"],
                                                ENT_QUOTES,
                                                'UTF-8'
                                            ) ?>
                                        </td>

                                        <!-- END TIME -->
                                        <td>
                                            <?= htmlspecialchars(
                                                $session["end_time"],
                                                ENT_QUOTES,
                                                'UTF-8'
                                            ) ?>
                                        </td>

                                        <!-- ACTIONS -->
                                        <td>

                                            <div class="facility-actions">

                                                <a
                                                    href="edit-session.php?id=<?= (int) $session["id"] ?>"
                                                    class="edit-btn"
                                                >
                                                    Edit
                                                </a>

                                                <form
                                                    method="POST"
                                                    action="delete-session.php"
                                                    onsubmit="return confirm('Are you sure you want to delete this session?');"
                                                >

                                                    <input
                                                        type="hidden"
                                                        name="id"
                                                        value="<?= (int) $session["id"] ?>"
                                                    >

                                                    <input
                                                        type="hidden"
                                                        name="csrf_token"
                                                        value="<?= htmlspecialchars(
                                                            $_SESSION["csrf_token"],
                                                            ENT_QUOTES,
                                                            'UTF-8'
                                                        ) ?>"
                                                    >

                                                    <button
                                                        type="submit"
                                                        class="delete-btn"
                                                    >
                                                        Delete
                                                    </button>

                                                </form>

                                            </div>

                                        </td>

                                    </tr>

                                <?php endforeach; ?>

                            </tbody>

                        </table>

                    </div>

                <?php endif; ?>

            </section>

        </div>

    </main>

</div>

</body>
</html>
