
<?php

session_start();

// Protect admin page
if (!isset($_SESSION["admin_id"])) {
    header("Location: login.php");
    exit;
}

require_once __DIR__ . "/../classes/Contact.php";

$contactObj = new Contact();

if (empty($_SESSION["csrf_token"])) {
    $_SESSION["csrf_token"] = bin2hex(random_bytes(32));
}

$error = "";
$success = "";

// Delete a message
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $token = $_POST["csrf_token"] ?? "";

    if (!is_string($token) ||
        !hash_equals($_SESSION["csrf_token"], $token)) {
        http_response_code(403);
        exit("Invalid request.");
    }

    $messageId = filter_input(
        INPUT_POST,
        "message_id",
        FILTER_VALIDATE_INT
    );

    if (!$messageId || $messageId <= 0) {
        $error = "Invalid message ID.";
    } else {
        try {
            $deleted = $contactObj->deleteMessage($messageId);

            if ($deleted) {
                $_SESSION["message_success"] =
                    "Message deleted successfully.";
            } else {
                $_SESSION["message_error"] =
                    "Message not found.";
            }

            header("Location: messages.php");
            exit;

        } catch (Exception $e) {
            error_log($e->getMessage());
            $error = "Unable to delete the message.";
        }
    }
}

// Display messages after redirect
if (isset($_SESSION["message_success"])) {
    $success = $_SESSION["message_success"];
    unset($_SESSION["message_success"]);
}

if (isset($_SESSION["message_error"])) {
    $error = $_SESSION["message_error"];
    unset($_SESSION["message_error"]);
}

$messages = $contactObj->getAllMessages();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Guest Messages | Grand Haven Hotel</title>

    <link rel="stylesheet" href="../style/global.css?v=3">
    <link rel="stylesheet" href="../style/admin.css?v=6">
</head>
<body>

<div class="admin-layout">

    <?php include __DIR__ . "/includes/sidebar.php"; ?>

    <main class="admin-main">

        <div class="admin-container">

            <div class="admin-header">
                <div>
                    <h1>Guest Messages</h1>
                    <p>View and manage guest inquiries.</p>
                </div>
            </div>

            <?php if ($success !== ""): ?>
                <p class="success-message">
                    <?= htmlspecialchars($success, ENT_QUOTES, 'UTF-8') ?>
                </p>
            <?php endif; ?>

            <?php if ($error !== ""): ?>
                <p class="error-message">
                    <?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?>
                </p>
            <?php endif; ?>

            <section class="bookings-section">

                <h2>Contact Messages (<?= count($messages) ?>)</h2>

                <?php if (empty($messages)): ?>

                    <p>No messages received yet.</p>

                <?php else: ?>

                    <div class="table-wrapper">

                        <table>
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Subject</th>
                                    <th>Message</th>
                                    <th>Received</th>
                                    <th>Action</th>
                                </tr>
                            </thead>

                            <tbody>

                            <?php foreach ($messages as $message): ?>

                                <tr>
                                    <td>
                                        <?= (int) $message["id"] ?>
                                    </td>

                                    <td>
                                        <?= htmlspecialchars(
                                            $message["name"],
                                            ENT_QUOTES,
                                            'UTF-8'
                                        ) ?>
                                    </td>

                                    <td>
                                        <?= htmlspecialchars(
                                            $message["email"],
                                            ENT_QUOTES,
                                            'UTF-8'
                                        ) ?>
                                    </td>

                                    <td>
                                        <?= htmlspecialchars(
                                            $message["subject"],
                                            ENT_QUOTES,
                                            'UTF-8'
                                        ) ?>
                                    </td>

                                    <td class="message-content">
                                        <?= nl2br(htmlspecialchars(
                                            $message["message"],
                                            ENT_QUOTES,
                                            'UTF-8'
                                        )) ?>
                                    </td>

                                    <td>
                                        <?= htmlspecialchars(
                                            $message["created_at"],
                                            ENT_QUOTES,
                                            'UTF-8'
                                        ) ?>
                                    </td>

                                    <td>
                                        <form
                                            method="POST"
                                            action="messages.php"
                                            onsubmit="return confirm('Delete this message?');"
                                        >

                                            <input
                                                type="hidden"
                                                name="message_id"
                                                value="<?= (int) $message["id"] ?>"
                                            >

                                            <input
                                                type="hidden"
                                                name="csrf_token"
                                                value="<?= htmlspecialchars(
                                                    $_SESSION["csrf_token"],
                                                    ENT_QUOTES,
                                                    'UTF-8'
                                                ) ?>"
                                            >

                                            <button
                                                type="submit"
                                                class="cancel-booking-btn"
                                            >
                                                Delete
                                            </button>

                                        </form>
                                    </td>
                                </tr>

                            <?php endforeach; ?>

                            </tbody>
                        </table>

                    </div>

                <?php endif; ?>

            </section>

        </div>

    </main>

</div>

</body>
</html>
