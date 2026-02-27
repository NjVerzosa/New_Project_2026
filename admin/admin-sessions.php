<?php
session_start();
error_reporting(0);
ini_set('display_errors', 'off');
include './config.php';
if (!isset($_SESSION["email"]) || ($_SESSION['role'] == "Player")) {
    header("Location: ../portal");
    exit();
}
session_regenerate_id(true);
$stmt = $con->prepare("SELECT * FROM admins WHERE id = ?");
$stmt->bind_param("s", $_SESSION["email"]);
$stmt->execute();
$result = $stmt->get_result();
$date = $result->fetch_assoc();
$stmt->close();
