
<?php

require_once __DIR__ . "/classes/Facility.php";

$facilityObj = new Facility();
$facilities = $facilityObj->getAllFacilities();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Hotel Facilities</title>

   <link rel="stylesheet" href="style/global.css?v=2">
    <link rel="stylesheet" href="style/facilities.css">
</head>
<body>
    <?php include __DIR__ . "/includes/header.php"; ?>

    <header class="page-header">
        <h1>Our Facilities</h1>
        <p>Explore our hotel facilities and book your experience.</p>
    </header>

    <main class="facilities-container">

        <?php foreach ($facilities as $facility): ?>

            <div class="facility-card">




<?php if (!empty($facility["image_url"])): ?>

    <img
        src="<?= htmlspecialchars($facility["image_url"], ENT_QUOTES, 'UTF-8') ?>"
        alt="<?= htmlspecialchars($facility["name"], ENT_QUOTES, 'UTF-8') ?>"
        class="facility-image"
        loading="lazy"
    >

<?php endif; ?>


                <div class="facility-info">

                    <h2>
                        <?= htmlspecialchars($facility["name"]) ?>
                    </h2>

                    <p>
                        <?= htmlspecialchars($facility["description"] ?? "") ?>
                    </p>

                    <p class="capacity">
                        Capacity: <?= (int) $facility["capacity"] ?>
                    </p>

                    <a
                        href="booking.php?facility_id=<?= (int) $facility["id"] ?>"
                        class="book-btn"
                    >
                        Book Now
                    </a>

                </div>

            </div>

        <?php endforeach; ?>

    </main>
   <?php include __DIR__ . "/includes/footer.php"; ?>
</body>
</html>
