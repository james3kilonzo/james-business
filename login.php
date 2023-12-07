<?php
require_once('db.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE username = '$username'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        // Use password_verify to check if the entered password matches the stored hash
        if (password_verify($password, $row['password'])) {
            // Login successful, redirect to home 
            header('Location: home.html');
            exit(); 
        } else {
            echo "Invalid login credentials";
        }
    } else {
        echo "Invalid login credentials";
    }

    $conn->close(); 
}
?>