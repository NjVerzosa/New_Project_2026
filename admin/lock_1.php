<?php
session_start();
include 'config.php'; // Make sure this connects to your database


if (isset($_POST['make_it_1'])) {
    $role = 'Admin';
    $newStatus = 1;

    // Update the lock_register status
    $stmt = $con->prepare("UPDATE admins SET lock_register = ? WHERE role = ?");
    $stmt->bind_param("is", $newStatus, $role);

    if ($stmt->execute()) {
        header('Location: admin-Dashboard.php');
        exit();
    } else {
        header('Location: admin-Dashboard.php');
        exit();
    }
    $stmt->close();
    $con->close();
    exit();
}
