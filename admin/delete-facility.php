
<?php

session_start();

require_once __DIR__ . "/../classes/Facility.php";

// Protect the page
if (!isset($_SESSION["admin_id"])) {
    header("Location: login.php");
    exit;
}

// Only allow POST requests
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: facilities.php");
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
    die("Invalid facility ID.");
}

$facilityObj = new Facility();

try {
    $deleted = $facilityObj->deleteFacility($id);

    if ($deleted) {
        $_SESSION["facility_message"] = "Facility deleted successfully.";
    } else {
        $_SESSION["facility_message"] = "Facility not found.";
    }

} catch (PDOException $e) {

    // A facility with related sessions may be protected by a foreign key
    if ($e->getCode() === "23000") {
        $_SESSION["facility_message"] =
            "Cannot delete this facility because it has related sessions.";
    } else {
        error_log($e->getMessage());
        $_SESSION["facility_message"] = "Unable to delete facility.";
    }
}

header("Location: facilities.php");
exit;
