
<?php

session_start();

require_once __DIR__ . "/classes/Contact.php";

$contactObj = new Contact();

$error = "";
$success = "";

$name = "";
$email = "";
$subject = "";
$message = "";

if (empty($_SESSION["csrf_token"])) {
    $_SESSION["csrf_token"] = bin2hex(random_bytes(32));
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    if (!hash_equals(
        $_SESSION["csrf_token"],
        $_POST["csrf_token"] ?? ""
    )) {
        http_response_code(403);
        die("Invalid request.");
    }

    $name = trim($_POST["name"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $subject = trim($_POST["subject"] ?? "");
    $message = trim($_POST["message"] ?? "");

    if (
        $name === "" ||
        !filter_var($email, FILTER_VALIDATE_EMAIL) ||
        $subject === "" ||
        $message === ""
    ) {
        $error = "Please complete all fields correctly.";
    } elseif (
        strlen($name) > 100 ||
        strlen($email) > 150 ||
        strlen($subject) > 200 ||
        strlen($message) > 5000
    ) {
        $error = "One or more fields exceed the allowed length.";
    } else {

        try {

            $saved = $contactObj->saveMessage(
                $name,
                $email,
                $subject,
                $message
            );

            if ($saved) {
                $success = "Your message has been sent successfully!";

                $name = "";
                $email = "";
                $subject = "";
                $message = "";
            } else {
                $error = "Unable to send your message.";
            }

        } catch (Exception $e) {
            error_log($e->getMessage());
            $error = "Unable to send your message. Please try again.";
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Contact Us | Grand Haven Hotel</title>

    <link rel="stylesheet" href="style/global.css?v=3">
    <link rel="stylesheet" href="style/contact.css">
</head>
<body>

<?php include __DIR__ . "/includes/header.php"; ?>

<main>

    <section class="contact-hero">
        <p class="contact-label">WE'D LOVE TO HEAR FROM YOU</p>
        <h1>Contact Us</h1>
        <p>
            Have a question about our facilities or reservations?
            Send us a message.
        </p>
    </section>

    <section class="contact-section">

        <div class="contact-intro">
            <p class="contact-label">GET IN TOUCH</p>
            <h2>How Can We Help?</h2>
            <p>
                Whether you have a question about our facilities,
                booking sessions, or an existing reservation,
                we're here to help.
            </p>

            <a href="facilities.php" class="contact-link">
                Explore Facilities →
            </a>
        </div>

        <div class="contact-form-card">

            <h2>Send a Message</h2>

            <?php if ($error !== ""): ?>
                <p class="error-message">
                    <?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?>
                </p>
            <?php endif; ?>

            <?php if ($success !== ""): ?>
                <p class="success-message">
                    <?= htmlspecialchars($success, ENT_QUOTES, 'UTF-8') ?>
                </p>
            <?php endif; ?>

            <form method="POST" action="contact.php">

                <input
                    type="hidden"
                    name="csrf_token"
                    value="<?= htmlspecialchars($_SESSION["csrf_token"], ENT_QUOTES, 'UTF-8') ?>"
                >

                <label for="name">Full Name</label>
                <input
                    type="text"
                    name="name"
                    id="name"
                    maxlength="100"
                    value="<?= htmlspecialchars($name, ENT_QUOTES, 'UTF-8') ?>"
                    required
                >

                <label for="email">Email Address</label>
                <input
                    type="email"
                    name="email"
                    id="email"
                    maxlength="150"
                    value="<?= htmlspecialchars($email, ENT_QUOTES, 'UTF-8') ?>"
                    required
                >

                <label for="subject">Subject</label>
                <input
                    type="text"
                    name="subject"
                    id="subject"
                    maxlength="200"
                    value="<?= htmlspecialchars($subject, ENT_QUOTES, 'UTF-8') ?>"
                    required
                >

                <label for="message">Your Message</label>
                <textarea
                    name="message"
                    id="message"
                    rows="6"
                    maxlength="5000"
                    required
                ><?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8') ?></textarea>

                <button type="submit" class="contact-submit">
                    Send Message
                </button>

            </form>
        </div>

    </section>

</main>

<?php include __DIR__ . "/includes/footer.php"; ?>

</body>
</html>
