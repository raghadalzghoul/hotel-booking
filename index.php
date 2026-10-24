
<?php

require_once __DIR__ . "/classes/Facility.php";

$facilityObj = new Facility();
$facilities = array_slice(
    $facilityObj->getAllFacilities(),
    0,
    3
);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Grand Haven Hotel | Home</title>

    <link rel="stylesheet" href="style/global.css">
    <link rel="stylesheet" href="style/home.css">
</head>
<body>

    <!-- Navbar -->
   <?php include __DIR__ . "/includes/header.php"; ?>

    <!-- Hero -->
    <section class="hero">
        <div class="hero-content">
            <p class="hero-subtitle">WELCOME TO GRAND HAVEN</p>

            <h1>Experience Comfort & Luxury</h1>

            <p>
                Discover exceptional facilities, peaceful
                surroundings, and unforgettable experiences.
            </p>

            <a href="facilities.php" class="hero-btn">
                Explore Our Facilities
            </a>
        </div>
    </section>

    <!-- Featured Facilities -->
    <section class="featured-section">
        <div class="section-heading">
            <p>DISCOVER MORE</p>
            <h2>Our Featured Facilities</h2>
        </div>

        <div class="featured-grid">

            <?php foreach ($facilities as $facility): ?>

                <div class="featured-card">

                    <?php if (!empty($facility["image_url"])): ?>
                        <img
                            src="<?= htmlspecialchars($facility["image_url"], ENT_QUOTES, 'UTF-8') ?>"
                            alt="<?= htmlspecialchars($facility["name"], ENT_QUOTES, 'UTF-8') ?>"
                            loading="lazy"
                        >
                    <?php endif; ?>

                    <div class="featured-info">
                        <h3>
                            <?= htmlspecialchars($facility["name"], ENT_QUOTES, 'UTF-8') ?>
                        </h3>

                        <p>
                            <?= htmlspecialchars($facility["description"] ?? "", ENT_QUOTES, 'UTF-8') ?>
                        </p>

                        <a href="booking.php?facility_id=<?= (int)$facility["id"] ?>">
                            Book Now →
                        </a>
                    </div>

                </div>

            <?php endforeach; ?>

        </div>

        <a href="facilities.php" class="view-all-btn">
            View All Facilities
        </a>
    </section>

    <!-- About -->
    <section class="about-preview">
        <div class="about-content">
            <p>ABOUT OUR HOTEL</p>

            <h2>Your Perfect Escape Awaits</h2>

            <p>
                At Grand Haven Hotel, we believe every stay
                should be memorable. Enjoy our thoughtfully
                designed facilities and relaxing atmosphere.
            </p>

            <a href="about.php" class="about-btn">
                Learn More
            </a>
        </div>
    </section>

    <!-- Footer -->
  <?php include __DIR__ . "/includes/footer.php"; ?>

</body>
</html>
