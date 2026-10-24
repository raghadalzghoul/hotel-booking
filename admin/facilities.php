
<?php

session_start();

require_once __DIR__ . "/../classes/Facility.php";

// Only logged-in admins can access this page
if (!isset($_SESSION["admin_id"])) {
    header("Location: login.php");
    exit;
}

$facilityObj = new Facility();

$facilities = $facilityObj->getAllFacilities();




if (empty($_SESSION["csrf_token"])) {
    $_SESSION["csrf_token"] = bin2hex(random_bytes(32));
}

$message = $_SESSION["facility_message"] ?? "";
unset($_SESSION["facility_message"]);
 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Manage Facilities</title>

    <link rel="stylesheet" href="../style/global.css">
    <link rel="stylesheet" href="../style/admin.css">
</head>
<body>

<main class="admin-container">

    <div class="admin-header">

        <div>
            <h1>Manage Facilities</h1>
            <p>View and manage hotel facilities.</p>
        </div>

        <a href="dashboard.php" class="logout-btn">
            Back to Dashboard
        </a>

    </div>

    <section class="bookings-section">

        <h2>All Facilities</h2>
        <a href="add-facility.php" class="add-facility-btn">
    + Add New Facility
</a>


<?php if ($message !== ""): ?>
    <p class="facility-message">
        <?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8') ?>
    </p>
<?php endif; ?>

 
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

                                <td>
                                    <?= (int)$facility["id"] ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($facility["name"], ENT_QUOTES, 'UTF-8') ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($facility["description"] ?? "", ENT_QUOTES, 'UTF-8') ?>
                                </td>

                                <td>
                                    <?= (int)$facility["capacity"] ?>
                                </td>

                                <td>
                                    <?php if (!empty($facility["image_url"])): ?>

                                        <img
                                           src="../<?= htmlspecialchars($facility["image_url"], ENT_QUOTES, 'UTF-8') ?>"
                                            alt="Facility"
                                            width="80"
                                        >

                                    <?php else: ?>

                                        No image

                                    <?php endif; ?>
                                </td>



                                

<td>
    <div class="facility-actions">

        <a
            href="edit-facility.php?id=<?= (int)$facility["id"] ?>"
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
                value="<?= (int)$facility["id"] ?>"
            >

            <input
                type="hidden"
                name="csrf_token"
                value="<?= htmlspecialchars($_SESSION["csrf_token"], ENT_QUOTES, 'UTF-8') ?>"
            >

            <button type="submit" class="delete-btn">
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

</main>

</body>
</html>
