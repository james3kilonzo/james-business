<?php
require_once('db.php');

function isUsernameSimilar($newUsername, $existingUsername) {
    // Perform a case-insensitive comparison
    return strtolower($newUsername) === strtolower($existingUsername);
}

function isEmailSimilar($newEmail, $existingEmail) {
    // Perform a case-insensitive comparison
    return strtolower($newEmail) === strtolower($existingEmail);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['signup'])) {
    // Sanitize user inputs
    $username = htmlspecialchars($_POST['username']);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Invalid email format. Please enter a valid email address.";
        exit();
    }

    // Use prepared statements to prevent SQL injection
    $check_username_stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $check_username_stmt->bind_param("s", $username);
    $check_username_stmt->execute();
    $result_username = $check_username_stmt->get_result();

    $check_email_stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $check_email_stmt->bind_param("s", $email);
    $check_email_stmt->execute();
    $result_email = $check_email_stmt->get_result();

    if ($result_username->num_rows > 0) {
        echo "Username already taken. Please choose another one.";
    } elseif ($result_email->num_rows > 0) {
        echo "Email address already in use. Please use a different one.";
    } else {
        // Check for similarity in username or email
        while ($row = $result_username->fetch_assoc()) {
            if (isUsernameSimilar($username, $row['username'])) {
                echo "Username is too similar to an existing one. Please choose another one.";
                exit();
            }
        }

        while ($row = $result_email->fetch_assoc()) {
            if (isEmailSimilar($email, $row['email'])) {
                echo "Email address is too similar to an existing one. Please use a different one.";
                exit();
            }
        }

        // prevent SQL injection
        $insert_user_stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
        $insert_user_stmt->bind_param("sss", $username, $email, password_hash($password, PASSWORD_DEFAULT));

        if ($insert_user_stmt->execute()) {
            // Registration successful, redirect the user 
            header("Location: home.html");
            exit();
        } else {
            echo "Error: " . $insert_user_stmt->error;
        }
    }

    // Close prepared statements
    $check_username_stmt->close();
    $check_email_stmt->close();
}

// Close the database connection
$conn->close();
?>

