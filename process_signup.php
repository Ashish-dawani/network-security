<?php
include 'db_connect.php'; // Make sure this file has proper database connection setup
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fullname = $conn->real_escape_string($_POST['fullname']);
    $email = $conn->real_escape_string($_POST['email']);
    $password = $conn->real_escape_string($_POST['password']);
    $confirm_password = $conn->real_escape_string($_POST['confirm_password']);

    if ($password !== $confirm_password) {
        $_SESSION['error'] = "Passwords do not match.";
        header("Location: ../signup.php"); // Ensure this path is correct
        exit();
    }

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $conn->prepare("INSERT INTO users (fullname, email, password) VALUES (?, ?, ?)");
    if (!$stmt) {
        $_SESSION['error'] = "SQL error: " . $conn->error;
        header("Location: ../signup.php"); // Ensure this path is correct
        exit();
    }

    $stmt->bind_param("sss", $fullname, $email, $hashed_password);
    if ($stmt->execute()) {
        $_SESSION['message'] = "Registration successful. You can now login.";
        header("Location: ../signin.php"); // Ensure this path is correct
        exit();
    } else {
        $_SESSION['error'] = "User could not be registered. Please try again.";
        header("Location: ../signup.php"); // Ensure this path is correct
        exit();
    }

    $stmt->close();
    $conn->close();
} else {
    header("Location: ../signup.php"); // Ensure this path is correct
    exit();
}
?>
