<?php

$servername = "localhost";
$username = "xgamblur123345";
$password = "xgamblur123345";
$dbname = "xgamblur123345";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>

