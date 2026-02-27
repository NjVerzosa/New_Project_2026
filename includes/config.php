<?php
// $servername = "localhost";
// $username = "u345422617_bot";
// $password = "Nj_verzosa24";
// $dbname = "u345422617_earningsphere";

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "database_2026";

$con = new mysqli($servername, $username, $password, $dbname);

if ($con->connect_error) {
    echo '<h4>404 Not Found</h4>';
}