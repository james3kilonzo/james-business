

<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "car_business";

// Create connection
$conn = mysqli_connect('localhost', 'root', '', 'car_business');

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

?>
