
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>About Us | Grand Haven Hotel</title>

    <link rel="stylesheet" href="style/global.css?v=3">
    <link rel="stylesheet" href="style/about.css">
</head>
<body>

<?php include __DIR__ . "/includes/header.php"; ?>

<main>

    <!-- Page Introduction -->
    <section class="about-hero">
        <div class="about-hero-content">
            <p class="section-label">DISCOVER GRAND HAVEN</p>
            <h1>About Our Hotel</h1>
            <p>
                A place where comfort, relaxation,
                and memorable experiences come together.
            </p>
        </div>
    </section>

    <!-- Our Story -->
    <section class="about-section">
        <div class="about-text">
            <p class="section-label">OUR STORY</p>
            <h2>Welcome to Grand Haven</h2>

            <p>
                At Grand Haven Hotel, we believe that
                every guest deserves a comfortable and
                enjoyable experience.
            </p>

            <p>
                Our facilities are designed to help
                guests relax, stay active, and make
                the most of their visit.
            </p>

            <a href="facilities.php" class="about-link">
                Explore Our Facilities
            </a>
        </div>

        <div class="about-image">
            <img
                src="assets/images/hotel-hero.jpg"
                alt="Grand Haven Hotel"
                loading="lazy"
            >
        </div>
    </section>

    <!-- Our Values -->
    <section class="values-section">
        <div class="values-heading">
            <p class="section-label">WHAT MATTERS TO US</p>
            <h2>Our Values</h2>
        </div>

        <div class="values-grid">

            <div class="value-card">
                <h3>Comfort</h3>
                <p>
                    Creating welcoming spaces where
                    every guest can feel relaxed.
                </p>
            </div>

            <div class="value-card">
                <h3>Quality</h3>
                <p>
                    Providing well-maintained facilities
                    and enjoyable experiences.
                </p>
            </div>

            <div class="value-card">
                <h3>Hospitality</h3>
                <p>
                    Making every guest feel valued
                    throughout their visit.
                </p>
            </div>

        </div>
    </section>

    <!-- Call to Action -->
    <section class="about-cta">
        <h2>Ready for Your Next Experience?</h2>

        <p>
            Discover our hotel facilities and
            reserve a session that suits your schedule.
        </p>

        <a href="facilities.php" class="about-link">
            Book a Facility
        </a>
    </section>

</main>

<?php include __DIR__ . "/includes/footer.php"; ?>

</body>
</html>
