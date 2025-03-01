<?php

$servername = "localhost";
<<<<<<< HEAD
$username = "xgamblur123345";
$password = "xgamblur123345";
$dbname = "xgamblur123345";
=======
$username = "u873167744_fomoplay";
$password = "u873167744_Fomoplay";
$dbname = "u873167744_fomoplay";
>>>>>>> 7b570b3acf7925bce6e596785d2268af1a197263

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>

