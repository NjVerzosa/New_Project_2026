<?php
// $servername = "localhost";
// $username = "u345422617_bot";
// $password = "Nj_verzosa24";
// $dbname = "u345422617_earningsphere";

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "database_2026";


// Attempt to connect to the database using PDO
try {
    $dsn = "mysql:host=$servername;dbname=$dbname;charset=utf8mb4";
    $con = new PDO($dsn, $username, $password);

    // Set PDO error mode to exception
    $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // Handle connection failure
    echo '<h4>Database Connection Failed: ' . $e->getMessage() . '</h4>';
    exit; // Stop execution if the connection fails
}
