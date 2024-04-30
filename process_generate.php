<?php include 'header.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Generate - Password Manager</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?php
function generateSecurePassword($length = 8) {
    $length = max($length, 8);  // Ensure minimum password length of 8
    $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()_+-=[]{}|;:,.<>?";
    $password = '';
    for ($i = 0; $i < $length; $i++) {
        $password .= $chars[random_int(0, strlen($chars) - 1)];
    }
    return $password;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $length = isset($_POST['length']) ? (int) $_POST['length'] : 8;
    $password = generateSecurePassword($length);
    echo "<div class='container mt-5'>
            <h3>Generated Password:</h3>
            <p class='alert alert-success'>{$password}</p>
          </div>";
} else {
    header("Location: ../generate_password.php");
    exit();
}
?>
</body>
</html>