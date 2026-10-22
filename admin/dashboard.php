
<?php

session_start();

if (!isset($_SESSION["admin_id"])) {
    header("Location: login.php");
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>

    <link rel="stylesheet" href="../style/global.css">
    <link rel="stylesheet" href="../style/admin.css">
</head>
<body>

    <div class="dashboard-container">

        <h1>
            Welcome,
            <?= htmlspecialchars($_SESSION["admin_name"], ENT_QUOTES, 'UTF-8') ?>
        </h1>

        <p>You have successfully logged in to the hotel admin dashboard.</p>

        <a href="logout.php" class="logout-btn">
            Logout
        </a>

    </div>

</body>
</html>
