
<?php

session_start();

require_once __DIR__ . "/../classes/Session.php";

if (!isset($_SESSION["admin_id"])) {
    header("Location: login.php");
    exit;
}

// Only allow POST requests
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: sessions.php");
    exit;
}

// Check CSRF token
if (
    empty($_SESSION["csrf_token"]) ||
    !hash_equals(
        $_SESSION["csrf_token"],
        $_POST["csrf_token"] ?? ""
    )
) {
    http_response_code(403);
    die("Invalid request.");
}

$id = filter_input(
    INPUT_POST,
    "id",
    FILTER_VALIDATE_INT
);

if (!$id || $id < 1) {
    die("Invalid session ID.");
}

$sessionObj = new Session();

try {

    $deleted = $sessionObj->deleteSession($id);

    if ($deleted) {
        $_SESSION["session_message"] = "Session deleted successfully.";
    } else {
        $_SESSION["session_message"] = "Session not found.";
    }

} catch (PDOException $e) {

    if ($e->getCode() === "23000") {

        $_SESSION["session_message"] =
            "Cannot delete this session because it has related bookings.";

    } else {

        error_log($e->getMessage());

        $_SESSION["session_message"] =
            "Unable to delete session.";
    }
}

header("Location: sessions.php");
exit;
