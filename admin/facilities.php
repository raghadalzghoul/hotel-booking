
<?php

session_start();

require_once __DIR__ . "/../classes/Facility.php";

// Protect admin page
if (!isset($_SESSION["admin_id"])) {
    header("Location: login.php");
    exit;
}

// Get facilities
$facilityObj = new Facility();
$facilities = $facilityObj->getAllFacilities();

// Create CSRF token
if (empty($_SESSION["csrf_token"])) {
    $_SESSION["csrf_token"] = bin2hex(random_bytes(32));
}

// Display message after add, edit, or delete
$message = $_SESSION["facility_message"] ?? "";
unset($_SESSION["facility_message"]);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Manage Facilities | Grand Haven Hotel</title>

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
                    <h1>Manage Facilities</h1>
                    <p>View and manage hotel facilities.</p>
                </div>

                <a href="add-facility.php" class="add-facility-btn">
                    + Add New Facility
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

            <!-- FACILITIES SECTION -->
            <section class="bookings-section">

                <h2>All Facilities</h2>

                <?php if (empty($facilities)): ?>

                    <p>No facilities found.</p>

                <?php else: ?>

                    <div class="table-wrapper">

                        <table>

                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Facility Name</th>
                                    <th>Description</th>
                                    <th>Capacity</th>
                                    <th>Image</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>

                            <tbody>

                                <?php foreach ($facilities as $facility): ?>

                                    <tr>

                                        <!-- ID -->
                                        <td>
                                            <?= (int) $facility["id"] ?>
                                        </td>

                                        <!-- NAME -->
                                        <td>
                                            <?= htmlspecialchars(
                                                $facility["name"],
                                                ENT_QUOTES,
                                                'UTF-8'
                                            ) ?>
                                        </td>

                                        <!-- DESCRIPTION -->
                                        <td>
                                            <?= htmlspecialchars(
                                                $facility["description"] ?? "",
                                                ENT_QUOTES,
                                                'UTF-8'
                                            ) ?>
                                        </td>

                                        <!-- CAPACITY -->
                                        <td>
                                            <?= (int) $facility["capacity"] ?>
                                        </td>

                                        <!-- IMAGE -->
                                        <td>

                                            <?php if (!empty($facility["image_url"])): ?>

                                                <img
                                                    src="<?= htmlspecialchars(
                                                        $facility["image_url"],
                                                        ENT_QUOTES,
                                                        'UTF-8'
                                                    ) ?>"
                                                    alt="<?= htmlspecialchars(
                                                        $facility["name"],
                                                        ENT_QUOTES,
                                                        'UTF-8'
                                                    ) ?>"
                                                    class="admin-facility-image"
                                                    loading="lazy"
                                                >

                                            <?php else: ?>

                                                <span class="no-action">
                                                    No image
                                                </span>

                                            <?php endif; ?>

                                        </td>

                                        <!-- ACTIONS -->
                                        <td>

                                            <div class="facility-actions">

                                                <a
                                                    href="edit-facility.php?id=<?= (int) $facility["id"] ?>"
                                                    class="edit-btn"
                                                >
                                                    Edit
                                                </a>

                                                <form
                                                    method="POST"
                                                    action="delete-facility.php"
                                                    onsubmit="return confirm('Are you sure you want to delete this facility?');"
                                                >

                                                    <input
                                                        type="hidden"
                                                        name="id"
                                                        value="<?= (int) $facility["id"] ?>"
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
