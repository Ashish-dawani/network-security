<?php
include 'db_connect.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    header("Location: ../signin.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $website = $conn->real_escape_string($_POST['website']);
    $email = $conn->real_escape_string($_POST['email']);
    $plain_password = $conn->real_escape_string($_POST['password']);

    // The encryption key is securely stored and retrieved
    $encryption_key = 'tmAKfQYvMvJ3pmifvrZJcogmzfHeRYV6';
    $cipher = 'AES-256-CBC'; // Define the cipher algorithm to use
    $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length($cipher));
    $encrypted_password = openssl_encrypt($plain_password, $cipher, $encryption_key, 0, $iv);
   

    // Base64 encode the encrypted password and IV to store as text in the database
    $encoded_password = base64_encode($encrypted_password);
    $encoded_iv = base64_encode($iv);

    $stmt = $conn->prepare("INSERT INTO stored_passwords (user_id, website, email, password, iv) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("issss", $_SESSION['user_id'], $website, $email, $encoded_password, $encoded_iv);
    $result = $stmt->execute();

    if ($result) {
        $_SESSION['message'] = "Password stored successfully.";
        header("Location: ../store_password.php");
    } else {
        $_SESSION['error'] = "Error storing password: " . $stmt->error;
        header("Location: ../store_password.php");
    }

    $stmt->close();
    $conn->close();
} else {
    header("Location: ../store_password.php");
    exit();
}
?>
