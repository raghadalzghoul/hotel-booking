
<?php

session_start();

require_once __DIR__ . "/../classes/Admin.php";

$message = "";
$email = "";

$adminObj = new Admin();

// Redirect already logged-in admins
if (isset($_SESSION["admin_id"])) {
    header("Location: dashboard.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $email = trim($_POST["email"] ?? "");
    $password = $_POST["password"] ?? "";

    if ($email === "" || $password === "") {
        $message = "Please fill in all fields.";
    } else {

        $admin = $adminObj->login($email, $password);

        if ($admin) {

            session_regenerate_id(true);

            $_SESSION["admin_id"] = $admin["id"];
            $_SESSION["admin_name"] = $admin["name"];

            header("Location: dashboard.php");
            exit;

        } else {
            $message = "Invalid email or password.";
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Admin Login</title>

<link rel="stylesheet" href="../style/global.css">
<link rel="stylesheet" href="../style/login.css">
</head>
<body>

    <h1>Admin Login</h1>

    <?php if ($message !== ""): ?>
        <p style="color: red;">
            <?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8') ?>
        </p>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data" action="login.php">

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
            required
        >

        <button type="submit">Login</button>

    </form>

    <p>
        Don't have an account?
        <a href="register.php">Register</a>
    </p>

</body>
</html>
