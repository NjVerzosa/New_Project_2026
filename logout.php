<?php
include 'user-sessions.php';

// Check if session has an email
if (isset($_SESSION["acc_number"])) {
    $acc_number = $_SESSION["acc_number"];

    // Update user status
    $stmt = $con->prepare("UPDATE users SET status = 0 WHERE acc_number = ?");
    if ($stmt) {
        $stmt->bind_param("i", $acc_number);
        $stmt->execute();
        $stmt->close();
    }
}

// Destroy session after updating status
$_SESSION = [];
session_destroy();

// Remove session cookie
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(
        session_name(),
        '',
        time() - 42000,
        $params["path"],
        $params["domain"],
        $params["secure"],
        $params["httponly"]
    );
}

// Redirect to login page
header("Location: ../login");
exit();
