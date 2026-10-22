
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

    <link rel="stylesheet" href="style/global.css">
    <link rel="stylesheet" href="style/facilities.css">
</head>
<body>

    <header class="page-header">
        <h1>Our Facilities</h1>
        <p>Explore our hotel facilities and book your experience.</p>
    </header>

    <main class="facilities-container">

        <?php foreach ($facilities as $facility): ?>

            <div class="facility-card">

            <?php
$images = [
    "Swimming Pool" => "pool.jpg",
    "Spa" => "spa.jpg",
    "Fitness Center" => "fitness.jpg"
];

$image = $images[$facility["name"]] ?? "pool.jpg";
?>

<img
    src="assets/images/<?= htmlspecialchars($image, ENT_QUOTES, 'UTF-8') ?>"
    alt="<?= htmlspecialchars($facility["name"], ENT_QUOTES, 'UTF-8') ?>"
    class="facility-image"
>

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

</body>
</html>
