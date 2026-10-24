
<?php

require_once __DIR__ . "/../classes/Admin.php";

$message = "";
$name = "";
$email = "";

$admin = new Admin();



if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $name = trim($_POST["name"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $password = $_POST["password"] ?? "";

    if ($name === "") {
        $message = "Please enter your name.";

    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "Please enter a valid email.";

    } elseif (strlen($password) < 8) {
        $message = "Password must be at least 8 characters.";

    } else {

        if ($admin->register($name, $email, $password)) {
            header("Location: login.php");
            exit;
        } else {
            $message = "Email already exists.";
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Admin Registration</title>

    <link rel="stylesheet" href="../style/global.css">
    <link rel="stylesheet" href="../style/register.css">
</head>

<body>

    <h1>Admin Registration</h1>

    <?php if ($message !== ""): ?>
        <p style="color: red;">
            <?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8') ?>
        </p>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data" action="register.php">

        <label for="name">Full Name</label>
        <input
            type="text"
            id="name"
            name="name"
            value="<?= htmlspecialchars($name, ENT_QUOTES, 'UTF-8') ?>"
            required
        >

        <label for="email">Email</label>
        <input
            type="email"
            id="email"
            name="email"
            value="<?= htmlspecialchars($email, ENT_QUOTES, 'UTF-8') ?>"
            required
        >

        <label for="password">Password</label>
        <input
            type="password"
            id="password"
            name="password"
            minlength="8"
            required
        >

        <button type="submit">Register</button>

    </form>

    <p>
        Already have an account?
        <a href="login.php">Login</a>
    </p>

</body>
</html>
