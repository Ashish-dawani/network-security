<?php
include 'db_connect.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $otp = $conn->real_escape_string($_POST['otp']);
    $userId = $_SESSION['user_id'];

    // Check if OTP is valid
    if (verifyOTP($conn, $userId, $otp)) {
        // OTP is valid, redirect to dashboard
        header("Location: <div class="">dashboard.php");
        exit();
    } else {
        $_SESSION['error'] = "Invalid OTP.";
        header("Location: ../otp.php");
        exit();
    }
} else {
    header("Location: ../otp.php");
    exit();
}

function verifyOTP($conn, $userId, $otp) {
    $stmt = $conn->prepare("SELECT * FROM otp WHERE user_id = ? AND otp = ? AND created_at >= NOW() - INTERVAL 5 MINUTE");
    $stmt->bind_param("is", $userId, $otp);
    $stmt->execute();
    $result = $stmt->get_result();
    $valid = $result->num_rows > 0;
    $stmt->close();
    return $valid;
}
?>
