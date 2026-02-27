<?php
include './config.php';

session_start();

// Update status to 0 if the user is logged out
if (isset($_SESSION["email"])) {
    $email_id = $_SESSION["email"];
}

// Destroy session data
session_destroy();

// Delete session cookie
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

// Redirect to the login page
header("Location: https://earningsphere.online/portal.php");
exit();
