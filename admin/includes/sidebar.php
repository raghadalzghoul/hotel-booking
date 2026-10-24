
<?php
$currentPage = basename($_SERVER["PHP_SELF"]);
?>

<aside class="admin-sidebar">

    <div class="sidebar-brand">
        <h2>Grand Haven</h2>
        <p>ADMIN PANEL</p>
    </div>

    <nav class="sidebar-nav">

        <a href="dashboard.php"
           class="<?= $currentPage === 'dashboard.php' ? 'active' : '' ?>">
            Dashboard
        </a>

        <a href="facilities.php"
           class="<?= in_array($currentPage, [
               'facilities.php',
               'add-facility.php',
               'edit-facility.php'
           ], true) ? 'active' : '' ?>">
            Facilities
        </a>

        <a href="sessions.php"
           class="<?= in_array($currentPage, [
               'sessions.php',
               'add-session.php',
               'edit-session.php'
           ], true) ? 'active' : '' ?>">
            Sessions
        </a>

        <a href="bookings.php"
           class="<?= $currentPage === 'bookings.php' ? 'active' : '' ?>">
            Bookings
        </a>

        <a href="messages.php"
           class="<?= $currentPage === 'messages.php' ? 'active' : '' ?>">
            Messages
        </a>

    </nav>

    <div class="sidebar-bottom">
        <a href="logout.php" class="logout-link">
            Logout
        </a>
    </div>

</aside>
